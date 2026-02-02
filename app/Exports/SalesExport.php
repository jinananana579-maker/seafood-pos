<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Order::with('user')->latest()->get();
    }

    public function headings(): array
    {
        return ['Order ID', 'Date', 'Total Price ($)', 'Payment Method', 'Cashier'];
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->created_at->format('d/m/Y H:i'),
            number_format($order->total_price, 2),
            strtoupper($order->payment_method),
            $order->user->name ?? 'Admin',
        ];
    }
}