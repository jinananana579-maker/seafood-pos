<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>ប្រវត្តិការលក់ - KH-SHOP</title>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body { font-family: 'Kantumruy Pro', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 p-6 font-sans text-slate-800" x-data="{ showDeleteModal: false, deleteUrl: '' }">

    <div class="max-w-7xl mx-auto">
        
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl relative mb-6 flex items-center gap-3 shadow-sm" role="alert">
                <i class="ph ph-check-circle text-xl"></i>
                <span class="block sm:inline font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative mb-6 flex items-center gap-3 shadow-sm" role="alert">
                <i class="ph ph-warning-circle text-xl"></i>
                <span class="block sm:inline font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <h1 class="text-3xl font-extrabold text-slate-800 flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                    <i class="ph ph-receipt text-xl"></i>
                </div>
                ប្រវត្តិការលក់
            </h1>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('sales.export') }}" class="bg-white border border-slate-200 text-slate-700 px-5 py-2.5 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition flex items-center gap-2 font-bold shadow-sm">
                    <i class="ph ph-microsoft-excel-logo text-green-600 text-xl"></i> Export Excel
                </a>

                <a href="/" class="bg-slate-900 text-white px-5 py-2.5 rounded-xl hover:bg-slate-800 transition flex items-center gap-2 font-bold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transform duration-200">
                    <i class="ph ph-storefront text-lg"></i> ត្រឡប់ទៅលក់វិញ
                </a>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="p-5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">លេខវិក្កយបត្រ</th>
                            <th class="p-5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">កាលបរិច្ឆេទ</th>
                            <th class="p-5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">អ្នកលក់</th>
                            <th class="p-5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">សរុប ($)</th>
                            <th class="p-5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">បង់តាម</th>
                            <th class="p-5 text-xs font-extrabold text-slate-500 uppercase tracking-wider text-center">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($orders as $order)
                        <tr class="hover:bg-blue-50/30 transition duration-200 group">
                            <td class="p-5">
                                <span class="font-mono font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="p-5">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-700">{{ $order->created_at->format('d-M-Y') }}</span>
                                    <span class="text-xs text-slate-400 font-medium">{{ $order->created_at->format('h:i A') }}</span>
                                </div>
                            </td>
                            <td class="p-5">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500">
                                        {{ substr($order->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <span class="font-medium text-slate-600">{{ $order->user->name ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td class="p-5">
                                <span class="font-black text-slate-800 text-lg">${{ number_format($order->total_price, 2) }}</span>
                            </td>
                            <td class="p-5">
                                @if($order->payment_method == 'cash')
                                    <span class="inline-flex items-center gap-1.5 bg-green-100 text-green-700 px-2.5 py-1 rounded-lg text-xs font-bold border border-green-200">
                                        <i class="ph ph-money"></i> Cash
                                    </span>
                                @elseif($order->payment_method == 'qr')
                                    <span class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-700 px-2.5 py-1 rounded-lg text-xs font-bold border border-blue-200">
                                        <i class="ph ph-qr-code"></i> KHQR
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-purple-100 text-purple-700 px-2.5 py-1 rounded-lg text-xs font-bold border border-purple-200">
                                        <i class="ph ph-credit-card"></i> Card
                                    </span>
                                @endif
                            </td>
                            
                            <td class="p-5">
                                <div class="flex items-center justify-center gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                    <a href="/pos/print/{{ $order->id }}" target="_blank" class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition shadow-sm" title="បោះពុម្ព">
                                        <i class="ph ph-printer text-lg"></i>
                                    </a>

                                    @if(Auth::user() && Auth::user()->role === 'admin')
                                        <button 
                                            @click="showDeleteModal = true; deleteUrl = '{{ route('sales.destroy', $order->id) }}'"
                                            class="w-8 h-8 rounded-full bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition shadow-sm" 
                                            title="លុប">
                                            <i class="ph ph-trash text-lg"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-5 border-t border-slate-100 bg-slate-50/50">
                @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $orders->links() }}
                @endif
            </div>
        </div>
    </div>

    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" x-cloak x-transition.opacity>
        <div class="bg-white rounded-3xl shadow-2xl w-[90%] max-w-sm p-6 relative animate-in zoom-in-95 duration-200 border border-white/20" @click.away="showDeleteModal = false">
            
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl shadow-inner">
                    <i class="ph ph-warning-circle"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">លុបវិក្កយបត្រ?</h3>
                <p class="text-sm text-slate-500 mb-6 px-4">
                    តើអ្នកពិតជាចង់លុបមែនទេ? <br>
                    <span class="text-red-500 font-medium text-xs">⚠️ ទំនិញនឹងត្រូវបង្វិលចូលស្តុកវិញដោយស្វ័យប្រវត្តិ។</span>
                </p>
            </div>

            <div class="flex gap-3">
                <button @click="showDeleteModal = false" class="flex-1 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition">
                    ទេ (Cancel)
                </button>
                
                <form :action="deleteUrl" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 shadow-lg shadow-red-200 transition">
                        លុប (Delete)
                    </button>
                </form>
            </div>

        </div>
    </div>

</body>
</html>