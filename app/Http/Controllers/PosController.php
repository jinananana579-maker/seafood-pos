<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\TelegramService; // 🔥 កុំភ្លេចហៅ Service នេះមកប្រើ

class PosController extends Controller
{
    // 1. បង្ហាញមុខទំនិញនៅ POS
    public function index()
    {
        $products = Product::all();
        return view('welcome', compact('products'));
    }

    // 2. រក្សាទុកការលក់ (Checkout)
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // គណនាប្រាក់អាប់
            $change = $request->received_amount - $request->total_price;

            // បង្កើត Order
            $order = Order::create([
                'user_id' => Auth::id() ?? 1,
                'total_price' => $request->total_price,
                'received_amount' => $request->received_amount,
                'change_amount' => $change,
                'payment_method' => $request->payment_method ?? 'cash',
                'created_at' => now(),
            ]);

            // បញ្ចូលទំនិញក្នុង OrderItems និងកាត់ស្តុក
            foreach ($request->cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'unit_price' => $item['price'],
                ]);

                // កាត់ស្តុកចេញពី Table Products
                Product::where('id', $item['id'])->decrement('stock', $item['qty']);
            }

            DB::commit();

            // 🔥 ផ្ញើសារចូល Telegram (ដាក់នៅទីនេះបន្ទាប់ពី Commit ជោគជ័យ)
            $msg = "💰 <b>ការលក់ថ្មី (New Sale)!</b>\n" .
                   "🧾 វិក្កយបត្រ: #{$order->id}\n" .
                   "💵 សរុប: <b>$" . number_format($order->total_price, 2) . "</b>\n" .
                   "👤 អ្នកលក់: " . (Auth::user()->name ?? 'Cashier') . "\n" .
                   "🕒 ម៉ោង: " . now()->format('h:i A d/m/Y');
                   
            TelegramService::send($msg);

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'ការលក់ជោគជ័យ!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // 3. បោះពុម្ពវិក្កយបត្រ (Receipt)
    public function printReceipt($id)
    {
        $order = Order::with('items.product', 'user')->findOrFail($id);
        return view('pos.receipt', compact('order'));
    }

    // 4. បង្ហាញប្រវត្តិលក់ (History)
    public function history()
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->limit(50)->get();
        return view('pos.history', compact('orders'));
    }

    // 5. លុបវិក្កយបត្រ និងបង្វិលស្តុក
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $order = Order::with('items')->findOrFail($id);

            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)->increment('stock', $item->quantity);
            }

            $order->items()->delete();
            $order->delete();

            DB::commit();
            return back()->with('success', 'លុបវិក្កយបត្រជោគជ័យ!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'មានបញ្ហាពេលលុប៖ ' . $e->getMessage());
        }
    }

    // 6. Export Excel (CSV)
    public function export()
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
        $fileName = 'sales_history_' . date('Y-m-d_H-i') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); 
            fputcsv($file, ['Order ID', 'Date', 'Cashier', 'Payment Method', 'Total Price ($)']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->user->name ?? 'Unknown',
                    strtoupper($order->payment_method),
                    $order->total_price
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}