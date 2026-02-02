<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"> 
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KH-SHOP POS - Modern UI</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6366f1', // Indigo 500
                        secondary: '#8b5cf6', // Violet 500
                        dark: '#1e293b',
                    },
                    fontFamily: {
                        sans: ['Kantumruy Pro', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Kantumruy Pro', sans-serif; touch-action: manipulation; background-color: #f8fafc; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        [x-cloak] { display: none !important; }
        
        /* Animation Classes */
        .fade-in-up { animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; transform: translateY(20px); }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }

        /* Glassmorphism Background */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .bg-pattern {
            background-color: #f1f5f9;
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="bg-pattern h-screen overflow-hidden text-slate-800 selection:bg-indigo-500 selection:text-white">

    <div class="flex h-full" x-data="{ ...posSystem(), userMenuOpen: false }">
        
        <div class="flex-1 flex flex-col h-full relative z-10">
            
            <header class="h-20 px-6 mt-4 mx-6 rounded-3xl glass flex items-center justify-between shadow-sm z-20">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-2xl flex items-center justify-center text-white shadow-indigo-200 shadow-lg transform hover:scale-105 transition-transform duration-300">
                        <i class="ph ph-storefront text-2xl"></i>
                    </div>
                    <div class="hidden sm:block leading-tight">
                        <h1 class="font-bold text-xl tracking-tight text-slate-800">KH-SHOP</h1>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                            <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider">Online POS</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 flex-1 max-w-xl mx-8">
                    <div class="relative w-full group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="ph ph-magnifying-glass text-slate-400 text-xl group-focus-within:text-indigo-500 transition-colors"></i>
                        </div>
                        <input type="text" x-model="barcodeInput" @keydown.enter="scanProduct()" 
                               class="w-full bg-white/80 border border-slate-200 rounded-2xl pl-12 pr-4 py-3 text-sm font-medium focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 outline-none transition-all shadow-sm group-hover:shadow-md placeholder:text-slate-400" 
                               placeholder="ស្វែងរកទំនិញ ឬ ស្កេន Barcode...">
                        <div class="absolute inset-y-0 right-2 flex items-center">
                            <kbd class="hidden md:inline-flex items-center border border-slate-200 rounded px-2 text-[10px] font-sans font-medium text-slate-400 bg-slate-50">Enter</kbd>
                        </div>
                    </div>
                    <button @click="openScanner()" class="bg-slate-800 text-white w-12 h-12 rounded-2xl shadow-lg hover:bg-slate-700 hover:scale-105 active:scale-95 transition flex items-center justify-center tooltip" title="Scan QR">
                        <i class="ph ph-qr-code text-xl"></i>
                    </button>
                </div>

                <div class="flex items-center gap-2">
                    @auth
                        <div class="relative">
                            <button @click="userMenuOpen = !userMenuOpen" class="flex items-center gap-3 hover:bg-white/50 p-1.5 pr-4 rounded-full border border-transparent hover:border-slate-200 transition duration-300 group">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-cyan-500 to-blue-500 p-[2px]">
                                    <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                                         <span class="text-sm font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-500">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                         </span>
                                    </div>
                                </div>
                                <div class="text-left hidden lg:block">
                                    <p class="text-xs font-bold text-slate-700 group-hover:text-indigo-600 transition">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] font-semibold text-slate-400">{{ ucfirst(Auth::user()->role) }}</p>
                                </div>
                                <i class="ph ph-caret-down text-slate-400 text-xs transition-transform duration-300" :class="userMenuOpen ? 'rotate-180' : ''"></i>
                            </button>
                            
                            <div x-show="userMenuOpen" @click.outside="userMenuOpen = false" x-cloak class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-slate-100 py-2 z-50 transform origin-top-right animate-in fade-in zoom-in-95 duration-200">
                                <div class="px-4 py-3 border-b border-slate-50 mb-1 bg-slate-50/50">
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Logged in as</p>
                                    <p class="font-bold text-slate-800 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="p-1 space-y-1">
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl transition font-medium"><i class="ph ph-squares-four text-lg"></i> Dashboard</a>
                                        <a href="{{ route('sales.history') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl transition font-medium"><i class="ph ph-receipt text-lg"></i> ប្រវត្តិលក់</a>
                                    @endif
                                </div>
                                <div class="border-t border-slate-100 mt-1 p-1">
                                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-red-500 hover:bg-red-50 rounded-xl transition font-bold"><i class="ph ph-sign-out text-lg"></i> ចាកចេញ</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-2 bg-slate-900 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all"><span>Login</span><i class="ph ph-arrow-right"></i></a>
                    @endauth
                </div>
            </header>

            <div class="px-6 py-5">
                <div class="flex gap-3 overflow-x-auto no-scrollbar pb-1">
                    <button @click="filter = 'all'" :class="filter === 'all' ? 'bg-slate-800 text-white shadow-lg shadow-slate-300 scale-105 ring-2 ring-slate-800 ring-offset-2' : 'bg-white text-slate-500 border border-slate-200 hover:border-slate-300 hover:bg-white shadow-sm'" class="px-5 py-2.5 rounded-2xl font-bold whitespace-nowrap transition-all duration-300 flex items-center gap-2 text-sm group">
                        <i class="ph ph-squares-four text-lg" :class="filter === 'all' ? 'text-white' : 'text-slate-400'"></i> ទាំងអស់
                    </button>
                    <button @click="filter = 'drink'" :class="filter === 'drink' ? 'bg-pink-500 text-white shadow-lg shadow-pink-200 scale-105 ring-2 ring-pink-500 ring-offset-2' : 'bg-white text-slate-500 border border-slate-200 hover:text-pink-500 hover:border-pink-200 shadow-sm'" class="px-5 py-2.5 rounded-2xl font-bold whitespace-nowrap transition-all duration-300 flex items-center gap-2 text-sm">
                        <i class="ph ph-coffee text-lg"></i> ភេសជ្ជៈ
                    </button>
                    <button @click="filter = 'seafood'" :class="filter === 'seafood' ? 'bg-blue-500 text-white shadow-lg shadow-blue-200 scale-105 ring-2 ring-blue-500 ring-offset-2' : 'bg-white text-slate-500 border border-slate-200 hover:text-blue-500 hover:border-blue-200 shadow-sm'" class="px-5 py-2.5 rounded-2xl font-bold whitespace-nowrap transition-all duration-300 flex items-center gap-2 text-sm">
                        <i class="ph ph-fish text-lg"></i> គ្រឿងសមុទ្រ
                    </button>
                    <button @click="filter = 'beer'" :class="filter === 'beer' ? 'bg-amber-500 text-white shadow-lg shadow-amber-200 scale-105 ring-2 ring-amber-500 ring-offset-2' : 'bg-white text-slate-500 border border-slate-200 hover:text-amber-500 hover:border-amber-200 shadow-sm'" class="px-5 py-2.5 rounded-2xl font-bold whitespace-nowrap transition-all duration-300 flex items-center gap-2 text-sm">
                        <i class="ph ph-beer-bottle text-lg"></i> ស្រាបៀរ
                    </button>
                    <button @click="filter = 'meat'" :class="filter === 'meat' ? 'bg-red-500 text-white shadow-lg shadow-red-200 scale-105 ring-2 ring-red-500 ring-offset-2' : 'bg-white text-slate-500 border border-slate-200 hover:text-red-500 hover:border-red-200 shadow-sm'" class="px-5 py-2.5 rounded-2xl font-bold whitespace-nowrap transition-all duration-300 flex items-center gap-2 text-sm">
                        <i class="ph ph-bone text-lg"></i> សាច់
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-6 pb-24 custom-scrollbar">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
                    @foreach($products as $index => $product)
                    <div x-show="(filter === 'all' || filter === '{{ $product->category }}') && ('{{ strtolower($product->name) }}'.includes(keyword.toLowerCase()))" 
                         class="fade-in-up group relative bg-white rounded-[2rem] p-3 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-transparent hover:border-indigo-100 hover:shadow-[0_20px_40px_rgba(99,102,241,0.15)] transition-all duration-300 cursor-pointer hover:-translate-y-2"
                         style="animation-delay: {{ $index * 50 }}ms"
                         @click="addToCart({{ $product }})">
                        
                        <div class="h-44 sm:h-52 w-full bg-slate-50 rounded-[1.8rem] overflow-hidden relative mb-4 shadow-inner">
                            <div class="absolute top-3 left-3 z-10">
                                <div class="bg-white/90 backdrop-blur-sm text-slate-800 text-[10px] px-2.5 py-1.5 rounded-full font-bold shadow-sm flex items-center gap-1.5 border border-white/50">
                                    <div class="w-2 h-2 rounded-full {{ $product->stock > 10 ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]' : 'bg-red-500 animate-pulse' }}"></div>
                                    <span class="tracking-wide text-slate-600">ស្តុក: {{ $product->stock }}</span>
                                </div>
                            </div>
                            <img src="{{ $product->image }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-out" loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        
                        <div class="px-2 pb-1">
                            <h3 class="font-bold text-slate-700 text-base leading-tight line-clamp-1 mb-2 group-hover:text-indigo-600 transition-colors">{{ $product->name }}</h3>
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Price</p>
                                    <div class="text-slate-800 font-black text-xl flex items-start leading-none gap-0.5">
                                        <span class="text-xs mt-1 text-slate-400">$</span>{{ number_format($product->price, 2) }}
                                    </div>
                                </div>
                                <button class="w-11 h-11 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 shadow-sm group-hover:shadow-indigo-300/50 group-hover:scale-110 group-active:scale-90">
                                    <i class="ph ph-plus-bold text-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div :class="showCartMobile ? 'translate-x-0' : 'translate-x-full'" class="fixed inset-y-0 right-0 w-full md:w-[420px] bg-white/90 backdrop-blur-xl border-l border-white/50 shadow-[-10px_0_40px_rgba(0,0,0,0.05)] flex flex-col z-40 transition-transform duration-500 cubic-bezier(0.32, 0.72, 0, 1) md:translate-x-0 md:static">
            
            <button @click="showCartMobile = false" class="md:hidden absolute top-4 left-4 bg-white p-2 rounded-full shadow-lg z-50 text-slate-500"><i class="ph ph-arrow-right text-xl"></i></button>

            <div class="h-24 flex flex-col justify-center px-8 border-b border-slate-100 bg-white/50">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="font-black text-2xl text-slate-800 flex items-center gap-2">
                            ការកម្ម៉ង់ <span class="bg-indigo-100 text-indigo-600 text-xs px-2 py-0.5 rounded-md" x-text="cart.length"></span>
                        </h2>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            <p class="text-xs text-slate-500 font-mono">Invoice: #{{ date('ymd') }}-{{ rand(100,999) }}</p>
                        </div>
                    </div>
                    <button @click="cart = []" x-show="cart.length > 0" class="group bg-red-50 text-red-500 hover:bg-red-500 hover:text-white p-2.5 rounded-xl transition-all duration-300 tooltip shadow-sm" title="សម្អាត">
                        <i class="ph ph-trash text-lg group-hover:animate-bounce"></i>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar">
                <template x-if="cart.length === 0">
                    <div class="h-full flex flex-col items-center justify-center text-slate-300 gap-6">
                        <div class="relative">
                            <div class="w-32 h-32 bg-slate-50 rounded-full flex items-center justify-center animate-pulse"><i class="ph ph-shopping-cart text-5xl opacity-20"></i></div>
                            <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md text-slate-400"><i class="ph ph-plus text-xl"></i></div>
                        </div>
                        <p class="font-bold text-slate-400">មិនទាន់មានទំនិញទេ</p>
                    </div>
                </template>
                
                <template x-for="(item, index) in cart" :key="item.id">
                    <div class="relative flex gap-4 p-3 pr-4 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-lg hover:border-indigo-100 transition-all group animate-in slide-in-from-right-8 duration-500 cursor-default">
                        <div class="w-20 h-20 bg-slate-50 rounded-xl overflow-hidden shrink-0 border border-slate-100 relative group-hover:shadow-md transition-shadow">
                            <img :src="item.image" class="w-full h-full object-cover">
                        </div>
                        
                        <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                            <div class="flex justify-between items-start gap-2">
                                <h4 class="font-bold text-slate-700 text-sm leading-snug line-clamp-2" x-text="item.name"></h4>
                                <span class="font-black text-indigo-600 text-sm whitespace-nowrap" x-text="'$' + (item.price * item.qty).toFixed(2)"></span>
                            </div>
                            
                            <div class="flex justify-between items-end mt-2">
                                <div class="bg-slate-50 px-2 py-1 rounded-lg border border-slate-100">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Unit Price</p>
                                    <p class="text-xs font-bold text-slate-600" x-text="'$' + item.price"></p>
                                </div>
                                
                                <div class="flex items-center gap-1 bg-slate-900 rounded-xl p-1 shadow-md">
                                    <button @click="updateQty(index, -1)" class="w-7 h-7 flex items-center justify-center bg-transparent rounded-lg text-white hover:bg-white/20 transition"><i class="ph ph-minus-bold text-xs"></i></button>
                                    <span class="text-sm font-bold w-6 text-center text-white" x-text="item.qty"></span>
                                    <button @click="updateQty(index, 1)" class="w-7 h-7 flex items-center justify-center bg-white rounded-lg text-slate-900 shadow-sm hover:scale-105 transition"><i class="ph ph-plus-bold text-xs"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="bg-white p-8 pb-10 shadow-[0_-10px_60px_rgba(0,0,0,0.05)] z-20 rounded-t-[2.5rem] relative">
                <div class="absolute top-3 left-1/2 -translate-x-1/2 w-12 h-1 bg-slate-200 rounded-full"></div>
                
                <div class="space-y-4 mb-8">
                    <div class="flex justify-between items-center text-sm font-medium text-slate-500">
                        <span>សរុប (Subtotal)</span>
                        <span class="font-bold text-slate-700">$<span x-text="totalPrice()"></span></span>
                    </div>
                    <div class="flex justify-between items-center text-sm font-medium text-slate-500">
                        <span>ពន្ធ (Tax 0%)</span>
                        <span class="font-bold text-slate-700">$0.00</span>
                    </div>
                    <div class="h-px bg-slate-100 my-2"></div>
                    <div class="flex justify-between items-center">
                        <span class="font-black text-slate-800 text-xl">ត្រូវបង់ (Total)</span>
                        <span class="font-black text-indigo-600 text-4xl tracking-tight">$<span x-text="totalPrice()"></span></span>
                    </div>
                </div>
                
                <button @click="openModal()" :disabled="cart.length === 0" 
                        :class="cart.length === 0 ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'bg-gradient-to-r from-slate-900 to-slate-800 text-white shadow-xl shadow-slate-300 hover:shadow-2xl hover:scale-[1.02]'" 
                        class="w-full py-4 rounded-2xl font-bold text-lg transition-all duration-300 flex items-center justify-center gap-3 relative overflow-hidden group">
                    <span class="relative z-10">គិតលុយ (Checkout)</span>
                    <i class="ph ph-arrow-right-bold relative z-10 group-hover:translate-x-1 transition-transform"></i>
                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 skew-y-12"></div>
                </button>
            </div>
        </div>

        <button @click="showCartMobile = true" class="md:hidden fixed bottom-6 right-6 w-16 h-16 bg-gradient-to-r from-indigo-600 to-violet-600 text-white rounded-full shadow-2xl flex items-center justify-center z-30 hover:scale-110 transition-transform border-4 border-white">
            <i class="ph ph-shopping-cart text-2xl"></i>
            <div x-show="cart.length > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-6 h-6 rounded-full flex items-center justify-center border-2 border-white shadow-sm" x-text="cart.reduce((a, b) => a + b.qty, 0)"></div>
        </button>

        <div x-show="showPaymentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-md" x-cloak x-transition.opacity>
            <div class="bg-white rounded-[2.5rem] shadow-2xl w-[90%] md:w-[500px] p-0 relative overflow-hidden animate-in zoom-in-95 slide-in-from-bottom-10 duration-300">
                
                <div class="bg-slate-50 p-8 text-center border-b border-slate-100 relative">
                    <button @click="closeModal()" class="absolute top-6 right-6 w-9 h-9 bg-white rounded-full flex items-center justify-center text-slate-400 hover:bg-red-50 hover:text-red-500 transition shadow-sm border border-slate-100"><i class="ph ph-x text-lg"></i></button>
                    <h2 class="text-2xl font-black text-slate-800 mb-1">ការបង់ប្រាក់</h2>
                    <p class="text-slate-400 text-sm">សូមជ្រើសរើសវិធីបង់ប្រាក់</p>
                    <div class="mt-6 inline-flex flex-col items-center">
                        <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-1">Total Amount</span>
                        <span class="text-5xl font-black text-indigo-600 tracking-tight">$<span x-text="totalPrice()"></span></span>
                    </div>
                </div>

                <div class="p-8">
                    <div x-show="!selectedPaymentMethod" class="grid gap-4">
                        <button @click="selectPayment('cash')" class="flex items-center gap-5 p-5 border border-slate-100 rounded-[1.5rem] hover:border-green-500 hover:bg-green-50/30 hover:shadow-lg hover:shadow-green-100/50 transition-all group bg-white relative overflow-hidden">
                            <div class="w-14 h-14 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center text-3xl group-hover:scale-110 group-hover:rotate-3 transition duration-300"><i class="ph ph-money"></i></div>
                            <div class="text-left relative z-10">
                                <h3 class="font-bold text-slate-800 text-lg">សាច់ប្រាក់ (Cash)</h3>
                                <p class="text-xs text-slate-400 font-medium">គណនាលុយអាប់ដោយស្វ័យប្រវត្តិ</p>
                            </div>
                            <i class="ph ph-caret-right ml-auto text-slate-300 text-xl group-hover:text-green-500 group-hover:translate-x-1 transition"></i>
                        </button>
                        
                        <button @click="selectPayment('qr')" class="flex items-center gap-5 p-5 border border-slate-100 rounded-[1.5rem] hover:border-blue-500 hover:bg-blue-50/30 hover:shadow-lg hover:shadow-blue-100/50 transition-all group bg-white relative overflow-hidden">
                            <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-3xl group-hover:scale-110 group-hover:rotate-3 transition duration-300"><i class="ph ph-qr-code"></i></div>
                            <div class="text-left relative z-10">
                                <h3 class="font-bold text-slate-800 text-lg">ស្កេន (KHQR)</h3>
                                <p class="text-xs text-slate-400 font-medium">ABA, ACLEDA, Wing...</p>
                            </div>
                            <i class="ph ph-caret-right ml-auto text-slate-300 text-xl group-hover:text-blue-500 group-hover:translate-x-1 transition"></i>
                        </button>
                        
                        <button @click="selectPayment('card')" class="flex items-center gap-5 p-5 border border-slate-100 rounded-[1.5rem] hover:border-purple-500 hover:bg-purple-50/30 hover:shadow-lg hover:shadow-purple-100/50 transition-all group bg-white relative overflow-hidden">
                            <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center text-3xl group-hover:scale-110 group-hover:rotate-3 transition duration-300"><i class="ph ph-credit-card"></i></div>
                            <div class="text-left relative z-10">
                                <h3 class="font-bold text-slate-800 text-lg">កាត (Credit Card)</h3>
                                <p class="text-xs text-slate-400 font-medium">Visa, Master, UnionPay</p>
                            </div>
                            <i class="ph ph-caret-right ml-auto text-slate-300 text-xl group-hover:text-purple-500 group-hover:translate-x-1 transition"></i>
                        </button>
                    </div>

                    <div x-show="selectedPaymentMethod === 'cash'" class="space-y-6 animate-in slide-in-from-right-8 duration-300">
                        <div class="relative group">
                            <label class="absolute -top-3 left-4 bg-white px-2 text-xs font-bold text-slate-400 group-focus-within:text-indigo-500 transition-colors">ប្រាក់ទទួលបាន ($)</label>
                            <input type="number" x-model="amountReceived" class="w-full text-4xl font-black p-5 bg-white border-2 border-slate-100 rounded-2xl focus:border-green-500 focus:ring-4 focus:ring-green-500/10 outline-none text-center text-slate-800 placeholder:text-slate-200 shadow-sm transition-all" placeholder="0.00" autofocus>
                        </div>
                        
                        <div class="flex justify-between items-center p-6 bg-green-50 rounded-2xl border border-green-100 border-dashed">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-green-200 rounded-full text-green-700"><i class="ph ph-arrow-u-down-left"></i></div>
                                <span class="font-bold text-green-800 text-sm">ប្រាក់អាប់ (Change)</span>
                            </div>
                            <span class="font-black text-3xl text-green-600">$<span x-text="(amountReceived - totalPrice()).toFixed(2)"></span></span>
                        </div>
                        
                        <div class="flex gap-4 pt-2">
                            <button @click="selectedPaymentMethod = null" class="w-1/3 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition">ត្រឡប់</button>
                            <button @click="confirmPayment()" class="w-2/3 py-4 bg-green-600 text-white rounded-2xl font-bold hover:bg-green-700 shadow-xl shadow-green-200 hover:scale-[1.02] transition transform">បង់ប្រាក់ (Pay)</button>
                        </div>
                    </div>
                    
                    <div x-show="selectedPaymentMethod === 'qr'" class="text-center space-y-6 animate-in slide-in-from-right-8 duration-300">
                        <div class="bg-white p-3 rounded-3xl inline-block border-2 border-blue-100 shadow-xl shadow-blue-50 relative group cursor-pointer hover:border-blue-400 transition-colors">
                            <div class="absolute inset-0 bg-blue-500/5 blur-xl rounded-full -z-10"></div>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" class="w-56 h-56 mx-auto mix-blend-multiply rounded-2xl">
                            <div class="absolute bottom-4 left-0 right-0 text-center"><span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-1 rounded-full">Scan Me</span></div>
                        </div>
                        <p class="text-sm font-medium text-slate-500">សូមស្កេនដើម្បីបង់ប្រាក់តាមរយៈ App ធនាគារ</p>
                        <div class="flex gap-4">
                            <button @click="selectedPaymentMethod = null" class="w-1/3 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition">ត្រឡប់</button>
                            <button @click="confirmPayment()" class="w-2/3 py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 shadow-xl shadow-blue-200 hover:scale-[1.02] transition transform">ទទួលបាន (Done)</button>
                        </div>
                    </div>

                    <div x-show="selectedPaymentMethod === 'card'" class="space-y-6 animate-in slide-in-from-right-8 duration-300">
                        <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-6 rounded-3xl text-white shadow-xl shadow-slate-300 mb-6 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-10 -mt-10 blur-2xl"></div>
                            <div class="flex justify-between items-start mb-8">
                                <i class="ph ph-sim-card text-4xl text-yellow-500/80"></i>
                                <i class="ph ph-wifi-high text-2xl text-white/50"></i>
                            </div>
                            <div class="mb-4">
                                <input type="text" x-model="cardNumber" class="w-full bg-transparent border-b border-white/20 px-0 py-2 text-2xl font-mono text-white placeholder:text-white/30 outline-none focus:border-white transition" placeholder="0000 0000 0000 0000">
                            </div>
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest text-white/50 mb-1">Card Holder</p>
                                    <p class="font-bold tracking-wide">CUSTOMER</p>
                                </div>
                                <i class="ph ph-credit-card text-3xl opacity-80"></i>
                            </div>
                        </div>
                        
                        <div class="flex gap-4">
                            <button @click="selectedPaymentMethod = null" class="w-1/3 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition">ត្រឡប់</button>
                            <button @click="confirmPayment()" class="w-2/3 py-4 bg-purple-600 text-white rounded-2xl font-bold hover:bg-purple-700 shadow-xl shadow-purple-200 hover:scale-[1.02] transition transform">បង់ប្រាក់ (Pay)</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showMessageModal" class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" x-cloak x-transition.opacity>
            <div class="bg-white rounded-[2rem] w-[90%] max-w-sm p-8 relative shadow-2xl text-center transform transition-all animate-in zoom-in-95" @click.away="showMessageModal = false">
                <div class="mx-auto mb-5 w-20 h-20 rounded-full flex items-center justify-center shadow-lg border-4" :class="isError ? 'bg-red-50 text-red-500 border-red-100' : 'bg-green-50 text-green-500 border-green-100'">
                    <i class="ph text-4xl" :class="isError ? 'ph-warning-octagon' : 'ph-check-circle'"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 mb-2" x-text="messageTitle"></h3>
                <p class="text-slate-500 mb-8 text-base leading-relaxed" x-text="messageBody"></p>
                <button @click="showMessageModal = false" class="w-full py-3.5 rounded-2xl font-bold text-white transition-all shadow-lg hover:-translate-y-1 hover:shadow-xl" :class="isError ? 'bg-red-600 hover:bg-red-500 shadow-red-200' : 'bg-green-600 hover:bg-green-500 shadow-green-200'">
                    យល់ព្រម (OK)
                </button>
            </div>
        </div>

        <div x-show="showScanner" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/95 backdrop-blur-md" x-cloak x-transition.opacity>
            <div class="bg-black w-full h-full md:w-[500px] md:h-auto md:aspect-[3/4] md:rounded-3xl overflow-hidden relative border border-slate-800 shadow-2xl">
                <button @click="stopScanner()" class="absolute top-6 right-6 bg-white/20 text-white w-12 h-12 rounded-full flex items-center justify-center hover:bg-white/30 transition z-50 backdrop-blur-sm"><i class="ph ph-x text-2xl"></i></button>
                <div id="reader" class="w-full h-full object-cover"></div>
                
                <div class="absolute inset-0 pointer-events-none flex flex-col items-center justify-center">
                    <div class="w-64 h-64 border-2 border-white/50 rounded-3xl relative">
                        <div class="absolute top-0 left-0 w-6 h-6 border-t-4 border-l-4 border-green-500 -mt-1 -ml-1 rounded-tl-lg"></div>
                        <div class="absolute top-0 right-0 w-6 h-6 border-t-4 border-r-4 border-green-500 -mt-1 -mr-1 rounded-tr-lg"></div>
                        <div class="absolute bottom-0 left-0 w-6 h-6 border-b-4 border-l-4 border-green-500 -mb-1 -ml-1 rounded-bl-lg"></div>
                        <div class="absolute bottom-0 right-0 w-6 h-6 border-b-4 border-r-4 border-green-500 -mb-1 -mr-1 rounded-br-lg"></div>
                        <div class="w-full h-0.5 bg-green-500/50 absolute top-1/2 -translate-y-1/2 shadow-[0_0_10px_rgba(34,197,94,0.8)] animate-pulse"></div>
                    </div>
                    <p class="text-white/90 text-sm bg-black/60 px-6 py-3 rounded-full backdrop-blur-md mt-8 font-medium border border-white/10">តម្រង់កាមេរ៉ាទៅលើ Barcode</p>
                </div>
            </div>
        </div>

    </div>

    <script>
        function posSystem() {
            return {
                allProducts: {!! json_encode($products) !!}, 
                filter: 'all',
                keyword: '',
                cart: [],
                currentTime: '',
                barcodeInput: '',
                
                showPaymentModal: false,
                selectedPaymentMethod: null,
                amountReceived: 0,
                cardNumber: '', 
                
                showCartMobile: false,
                showScanner: false,
                html5QrcodeScanner: null,

                showMessageModal: false,
                messageTitle: '',
                messageBody: '',
                isError: false,

                init() {
                    setInterval(() => {
                        this.currentTime = new Date().toLocaleTimeString('km-KH', { hour: '2-digit', minute: '2-digit' });
                    }, 1000);
                },

                showMsg(title, body, error = false) {
                    this.messageTitle = title;
                    this.messageBody = body;
                    this.isError = error;
                    this.showMessageModal = true;
                },

                openScanner() {
                    this.showScanner = true;
                    this.$nextTick(() => {
                        const html5QrCode = new Html5Qrcode("reader");
                        this.html5QrcodeScanner = html5QrCode;
                        const config = { fps: 10, qrbox: { width: 250, height: 250 } };
                        html5QrCode.start({ facingMode: "environment" }, config, (decodedText) => {
                            this.handleScannedCode(decodedText);
                        }, (errorMessage) => {}).catch(err => {
                            this.showMsg('បរាជ័យ', 'មិនអាចបើកកាមេរ៉ាបានទេ! សូមពិនិត្យមើល Permission។', true);
                            this.showScanner = false;
                        });
                    });
                },

                stopScanner() {
                    if (this.html5QrcodeScanner) {
                        this.html5QrcodeScanner.stop().then(() => {
                            this.html5QrcodeScanner.clear();
                            this.showScanner = false;
                        });
                    } else { this.showScanner = false; }
                },

                handleScannedCode(code) {
                    let product = this.allProducts.find(p => p.barcode == code);
                    if (product) {
                        this.addToCart(product);
                        new Audio('https://www.soundjay.com/buttons/sounds/button-3.mp3').play().catch(e => {});
                    } else { 
                        this.showMsg('រកមិនឃើញ', 'មិនមានទំនិញដែលមានកូដនេះទេ', true);
                    }
                },

                scanProduct() {
                    let product = this.allProducts.find(p => p.barcode == this.barcodeInput);
                    if (product) {
                        this.addToCart(product);
                        this.barcodeInput = '';
                        new Audio('https://www.soundjay.com/buttons/sounds/button-3.mp3').play().catch(e => {});
                    } else {
                        this.showMsg('រកមិនឃើញ', 'មិនមានទំនិញដែលមានកូដនេះទេ', true);
                        this.barcodeInput = '';
                    }
                },

                addToCart(product) {
                    let existing = this.cart.find(i => i.id === product.id);
                    if (existing) { existing.qty++; } else { this.cart.push({ ...product, qty: 1 }); }
                },

                updateQty(index, amount) {
                    if (this.cart[index].qty + amount <= 0) {
                        if(confirm("លុបទំនិញនេះ?")) { this.cart.splice(index, 1); }
                    } else { this.cart[index].qty += amount; }
                },

                totalPrice() {
                    return this.cart.reduce((total, item) => total + (item.price * item.qty), 0).toFixed(2);
                },

                openModal() {
                    if (this.cart.length === 0) return;
                    this.showPaymentModal = true;
                    this.selectedPaymentMethod = null;
                    this.amountReceived = 0;
                    this.cardNumber = ''; 
                },

                closeModal() { this.showPaymentModal = false; },

                selectPayment(method) {
                    this.selectedPaymentMethod = method;
                    if (method !== 'cash') { this.amountReceived = this.totalPrice(); }
                },

                confirmPayment() {
                    if (this.selectedPaymentMethod === 'cash' && this.amountReceived < this.totalPrice()) {
                        this.showMsg('បរាជ័យ!', 'ទឹកប្រាក់ដែលទទួលបានមិនគ្រប់គ្រាន់ទេ។', true);
                        return;
                    }

                    fetch('/pos/store', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            cart: this.cart,
                            total_price: this.totalPrice(),
                            received_amount: this.amountReceived,
                            payment_method: this.selectedPaymentMethod,
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            this.closeModal();
                            let printUrl = '/pos/print/' + data.order_id;
                            window.open(printUrl, '_blank', 'width=400,height=600');
                            this.cart = []; 
                        } else {
                            this.showMsg('មានបញ្ហា', data.message, true);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        this.closeModal();
                        this.showMsg('ការតភ្ជាប់បរាជ័យ', 'មិនអាចតភ្ជាប់ទៅកាន់ Server បានទេ។', true);
                    });
                }
            }
        }
    </script>
</body>
</html>