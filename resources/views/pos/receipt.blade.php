@php
    // ទាញយកទិន្នន័យហាងពី Database
    $setting = \App\Models\Setting::first();
@endphp

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>Receipt #{{ $order->id }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kantumruy Pro', sans-serif;
            width: 80mm; /* ខ្នាតក្រដាសកាស្យេស្តង់ដារ */
            margin: 0 auto;
            padding: 10px;
            color: #000;
            background: #fff;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .line { border-bottom: 1px dashed #000; margin: 10px 0; }
        
        /* CSS សម្រាប់រូបភាព */
        .logo { width: 80px; height: auto; margin-bottom: 5px; }
        .qr-code { width: 120px; height: auto; margin: 10px 0; }

        table { width: 100%; font-size: 11px; border-collapse: collapse; }
        th { text-align: left; padding: 5px 0; }
        td { padding: 2px 0; }
        
        .header-logo {
            font-size: 18px;
            font-weight: 800;
            margin: 0;
            text-transform: uppercase;
        }
        
        .info-table td { padding: 1px 0; }

        /* កុំឱ្យឃើញប៊ូតុង Print ពេល Print ចេញមក */
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 0; }
        }
    </style>
</head>
<body onload="window.print()"> 

    <div class="text-center">
        <img src="{{ asset('logo.png') }}?v={{ time() }}" class="logo" alt="Logo" onerror="this.style.display='none'">
        
        <h2 class="header-logo">{{ $setting->shop_name ?? 'KH-SHOP' }}</h2>
        
        <p style="margin: 2px 0; font-size: 11px; white-space: pre-line;">{{ $setting->address ?? 'អាស័យដ្ឋានមិនទាន់កំណត់' }}</p>
        
        <p style="font-size: 10px;">Tel: {{ $setting->phone ?? '...' }}</p>
    </div>

    <div class="line"></div>

    <table class="info-table" style="font-size: 10px;">
        <tr>
            <td>វិក្កយបត្រ #:</td>
            <td class="text-right">{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td>កាលបរិច្ឆេទ:</td>
            <td class="text-right">{{ $order->created_at->format('d/m/Y h:i A') }}</td>
        </tr>
        <tr>
            <td>អ្នកគិតលុយ:</td>
            <td class="text-right">{{ $order->user->name ?? 'Admin' }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <table>
        <thead>
            <tr style="border-bottom: 1px solid #000;">
                <th style="width: 50%;">មុខទំនិញ</th>
                <th class="text-center" style="width: 15%;">ចំនួន</th>
                <th class="text-right" style="width: 35%;">តម្លៃ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td style="padding-top: 5px;">
                    <span style="font-weight: 600;">{{ $item->product->name }}</span>
                </td>
                <td class="text-center" style="vertical-align: top; padding-top: 5px;">{{ $item->quantity }}</td>
                <td class="text-right" style="vertical-align: top; padding-top: 5px;">${{ number_format($item->quantity * ($item->unit_price ?? $item->product->price), 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <table style="font-size: 12px;">
        <tr style="font-size: 14px;">
            <td class="font-bold">សរុប (Total):</td>
            <td class="text-right font-bold">${{ number_format($order->total_price, 2) }}</td>
        </tr>
        <tr style="font-size: 11px; color: #555;">
            <td>គិតជាប្រាក់រៀល:</td>
            <td class="text-right">{{ number_format($order->total_price * 4100) }} ៛</td>
        </tr>
        
        <tr><td colspan="2" style="padding: 3px;"></td></tr> 
        
        <tr>
            <td>បង់តាម (Type):</td>
            <td class="text-right uppercase" style="font-weight: 600;">
                @if($order->payment_method == 'qr') 
                    KHQR (ស្កេន)
                @elseif($order->payment_method == 'card')
                    Credit Card
                @else
                    Cash (សាច់ប្រាក់)
                @endif
            </td>
        </tr>

        @if($order->payment_method == 'cash')
        <tr>
            <td>ប្រាក់ទទួល (Rec):</td>
            <td class="text-right">${{ number_format($order->received_amount, 2) }}</td>
        </tr>
        <tr>
            <td>ប្រាក់អាប់ (Change):</td>
            <td class="text-right font-bold">${{ number_format($order->change_amount, 2) }}</td>
        </tr>
        @endif
    </table>

    <div class="line"></div>

    <div class="text-center" style="margin-top: 10px;">
        <p style="font-size: 10px; font-weight: bold;">ស្កេនដើម្បីទូទាត់ប្រាក់ (ABA / ACLEDA)</p>
        
        <img src="{{ asset('qr.png') }}?v={{ time() }}" class="qr-code" alt="Bank QR" onerror="this.style.display='none'">

        <p style="font-size: 12px; font-weight: bold; margin-top: 5px;">
            {{ $setting->footer_text ?? 'សូមអរគុណ! សូមអញ្ជើញមកម្តងទៀត។' }}
        </p>

        <p style="font-size: 9px; margin-top: 2px;">Powered by KH-SHOP POS</p>
        
        <div style="margin-top: 10px; font-family: 'Courier New'; letter-spacing: 3px;">
            ||||||||||||||||||||||||
        </div>
    </div>

    <div class="no-print" style="margin-top: 20px; display: flex; gap: 10px;">
        <button onclick="window.print()" style="flex: 1; padding: 10px; background: #000; color: #fff; border: none; border-radius: 5px; cursor: pointer;">
            🖨️ Print Again
        </button>
        <button onclick="window.close()" style="flex: 1; padding: 10px; background: #ddd; border: none; border-radius: 5px; cursor: pointer;">
            Close
        </button>
    </div>

</body>
</html>