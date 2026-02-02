<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Services\TelegramService; // 🔥 កុំភ្លេចហៅ Service នេះ

class ExpenseController extends Controller
{
    // 1. បង្ហាញបញ្ជីចំណាយ (ជាមួយ Search & Filter)
    public function index(Request $request)
    {
        $query = Expense::query();

        // ស្វែងរកតាមចំណងជើង
        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // ស្វែងរកតាមប្រភេទ (Category)
        if ($request->category && $request->category != 'all') {
            $query->where('category', $request->category);
        }

        // ស្វែងរកតាមថ្ងៃចាប់ផ្តើម
        if ($request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        // ស្វែងរកដល់ថ្ងៃបញ្ចប់
        if ($request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // បង្ហាញទិន្នន័យចុងក្រោយគេមុន (Latest) និងដាក់ Paginate
        $expenses = $query->latest('date')->paginate(10);
        
        return view('admin.expenses.index', compact('expenses'));
    }

    // 2. បញ្ចូលចំណាយថ្មី
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'category' => 'nullable|string' // ✅ បន្ថែម validate category
        ]);

        // រក្សាទុកទិន្នន័យ (Save Data)
        Expense::create([
            'title' => $request->title,
            'amount' => $request->amount,
            'category' => $request->category, // ✅ បញ្ចូល category
            'date' => $request->date,
            'description' => $request->description
        ]);

        // 🔥 ផ្ញើសារចូល Telegram (អាប់ដេតថ្មីមាន Category)
        $categoryText = $request->category ? " ({$request->category})" : "";
        
        $msg = "💸 <b>មានការចំណាយ (New Expense)!</b>\n" .
               "📝 ហេតុផល: {$request->title}{$categoryText}\n" .
               "💵 ចំនួន: <b>-$" . number_format($request->amount, 2) . "</b>\n" .
               "📅 កាលបរិច្ឆេទ: " . \Carbon\Carbon::parse($request->date)->format('d/m/Y');

        try {
            TelegramService::send($msg);
        } catch (\Exception $e) {
            // បើផ្ញើមិនចេញ កុំឱ្យ Error (Optional)
        }

        return back()->with('success', 'កត់ត្រាចំណាយជោគជ័យ!');
    }

    // 3. កែប្រែទិន្នន័យ (Update Function) - ✅ ថ្មី
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        $expense = Expense::findOrFail($id);
        
        $expense->update([
            'title' => $request->title,
            'amount' => $request->amount,
            'category' => $request->category, // ✅ កែ category ដែរ
            'date' => $request->date,
            'description' => $request->description
        ]);

        return redirect()->back()->with('success', 'កែប្រែទិន្នន័យជោគជ័យ!');
    }

    // 4. លុបចំណាយ
    public function destroy($id)
    {
        Expense::findOrFail($id)->delete();
        return back()->with('success', 'លុបចំណាយជោគជ័យ!');
    }
}