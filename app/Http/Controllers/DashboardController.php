<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Expense; 
use Carbon\Carbon;      
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // 1. បង្ហាញ Dashboard
    public function index(Request $request)
    {
        // កំណត់កាលបរិច្ឆេទ (Default: ខែនេះ)
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        // គណនាចំណូលលក់ (Total Sales)
        $totalSales = Order::whereDate('created_at', '>=', $startDate)
                           ->whereDate('created_at', '<=', $endDate)
                           ->sum('total_price');

        // គណនាចំណាយ (Total Expenses)
        $totalExpenses = Expense::whereDate('date', '>=', $startDate)
                                ->whereDate('date', '<=', $endDate)
                                ->sum('amount');

        // ប្រាក់ចំណេញសុទ្ធ (Net Profit)
        $netProfit = $totalSales - $totalExpenses;

        // ទិន្នន័យផ្សេងៗ
        $totalProducts = Product::count();

        // 🔥 ទាញយកទំនិញដែលជិតអស់ស្តុក (Low Stock)
        $lowStockProducts = Product::where('stock', '<=', 5)
                                   ->orderBy('stock', 'asc')
                                   ->limit(10)
                                   ->get();

        // --- ផ្នែក Chart (30 ថ្ងៃចុងក្រោយ) ---
        $salesDataQuery = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as total'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $salesLabels = [];
        $salesData = [];
        foreach($salesDataQuery as $data) {
            $salesLabels[] = Carbon::parse($data->date)->format('d M');
            $salesData[] = $data->total;
        }

        // Best Seller Chart
        $bestSellers = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_qty'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $bsLabels = $bestSellers->pluck('name');
        $bsData = $bestSellers->pluck('total_qty');

        return view('admin.dashboard', compact(
            'totalSales', 'totalExpenses', 'netProfit', 'totalProducts', 
            'salesLabels', 'salesData', 'bsLabels', 'bsData',
            'startDate', 'endDate',
            'lowStockProducts'
        ));
    }

    // 2. 🔥 Function Export Excel (CSV)
    public function exportCsv(Request $request) 
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        
        // ទាញយកទិន្នន័យលក់តាមថ្ងៃដែលរើស
        $orders = Order::with('user')
                       ->whereDate('created_at', '>=', $startDate)
                       ->whereDate('created_at', '<=', $endDate)
                       ->orderBy('created_at', 'desc')
                       ->get();

        $fileName = 'sales_report_' . $startDate . '_to_' . $endDate . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // ដាក់ UTF-8 BOM ដើម្បីឱ្យ Excel បង្ហាញអក្សរខ្មែរបានត្រឹមត្រូវ
            fputs($file, "\xEF\xBB\xBF"); 
            
            // សរសេរក្បាលតារាង (Header)
            fputcsv($file, ['Order ID', 'Date', 'Cashier', 'Payment Method', 'Total Price ($)']);

            // សរសេរទិន្នន័យនីមួយៗ
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->created_at->format('Y-m-d H:i'),
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