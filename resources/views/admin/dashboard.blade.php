<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Stormy Edition</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Kantumruy Pro', 'sans-serif'] },
                    colors: {
                        primary: '#4f46e5', // Indigo
                        secondary: '#0ea5e9', // Sky
                    },
                     animation: {
                        'flash': 'flash 10s infinite',
                    },
                    keyframes: {
                        flash: {
                            '0%, 90%, 94%': { opacity: 0 },
                            '91%': { opacity: 0.6 },
                            '92%': { opacity: 0.1 },
                            '93%': { opacity: 0.8 },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* --- New Background Styles --- */
        body {
            /* ផ្ទៃខាងក្រោយពណ៌ងងឹត បែបព្យុះ */
            background: linear-gradient(to bottom, #0f172a, #1e293b, #334155);
            background-attachment: fixed;
            position: relative;
        }

        /* ស្រទាប់ភ្លៀង (Rain Layer) */
        .rain-container {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            pointer-events: none; z-index: -2;
            overflow: hidden;
            transform: skewX(-5deg); /* ធ្វើឱ្យភ្លៀងធ្លាក់ទ្រេតបន្តិច */
        }

        .drop {
            position: absolute;
            bottom: 100%;
            width: 2px;
            height: 60px;
            background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(174, 194, 224, 0.5));
            animation: fall linear infinite;
        }

        @keyframes fall {
            to { transform: translateY(120vh); }
        }

        /* ស្រទាប់ផ្លេកបន្ទោរ (Lightning Layer) */
        .lightning-flash {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: #fff;
            opacity: 0;
            pointer-events: none;
            z-index: -1;
            mix-blend-mode: overlay;
        }

        /* Custom Scrollbar & Glass Effect (Keep existing) */
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #475569; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }

        /* Update Glass to stand out against dark BG */
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.4);
        }
    </style>
</head>
<body class="h-screen flex flex-col overflow-hidden text-slate-800" x-data="{ userDropdownOpen: false, logoutModalOpen: false }">

    <div class="lightning-flash animate-flash"></div>
    <div class="rain-container">
        <div class="drop" style="left: 5%; animation-duration: 1.2s; animation-delay: 0.1s;"></div>
        <div class="drop" style="left: 12%; animation-duration: 1.5s; animation-delay: 0.3s;"></div>
        <div class="drop" style="left: 18%; animation-duration: 1.1s; animation-delay: 0.7s;"></div>
        <div class="drop" style="left: 24%; animation-duration: 1.7s; animation-delay: 0.2s;"></div>
        <div class="drop" style="left: 31%; animation-duration: 1.3s; animation-delay: 0.5s;"></div>
        <div class="drop" style="left: 38%; animation-duration: 1.4s; animation-delay: 0.8s;"></div>
        <div class="drop" style="left: 45%; animation-duration: 1.2s; animation-delay: 0.1s;"></div>
        <div class="drop" style="left: 52%; animation-duration: 1.6s; animation-delay: 0.4s;"></div>
        <div class="drop" style="left: 58%; animation-duration: 1.3s; animation-delay: 0.6s;"></div>
        <div class="drop" style="left: 65%; animation-duration: 1.5s; animation-delay: 0.2s;"></div>
        <div class="drop" style="left: 72%; animation-duration: 1.1s; animation-delay: 0.9s;"></div>
        <div class="drop" style="left: 79%; animation-duration: 1.8s; animation-delay: 0.3s;"></div>
        <div class="drop" style="left: 86%; animation-duration: 1.4s; animation-delay: 0.5s;"></div>
        <div class="drop" style="left: 93%; animation-duration: 1.2s; animation-delay: 0.1s;"></div>
        <div class="drop" style="left: 8%; animation-duration: 1.9s; animation-delay: 1.1s; opacity: 0.5;"></div>
        <div class="drop" style="left: 28%; animation-duration: 1.6s; animation-delay: 1.5s; opacity: 0.5;"></div>
        <div class="drop" style="left: 48%; animation-duration: 2.1s; animation-delay: 1.2s; opacity: 0.5;"></div>
        <div class="drop" style="left: 68%; animation-duration: 1.8s; animation-delay: 1.8s; opacity: 0.5;"></div>
        <div class="drop" style="left: 88%; animation-duration: 2.0s; animation-delay: 1.4s; opacity: 0.5;"></div>
    </div>
    <nav class="glass sticky top-0 z-40 px-6 py-3 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-4">
            <a href="/" class="group flex items-center gap-2 bg-slate-900 text-white px-4 py-2.5 rounded-xl hover:bg-primary transition-all duration-300 shadow-lg shadow-blue-900/20">
                <i class="ph ph-storefront text-xl group-hover:rotate-12 transition-transform"></i> 
                <span class="font-bold text-sm">POS System</span>
            </a>
            <div class="hidden md:block h-6 w-px bg-slate-200"></div>
            <h1 class="text-xl font-bold text-slate-700 hidden md:flex items-center gap-2">
                <i class="ph ph-squares-four text-primary"></i> ផ្ទាំងគ្រប់គ្រង
            </h1>
        </div>
        
        <div class="relative">
            <button @click="userDropdownOpen = !userDropdownOpen" class="flex items-center gap-3 hover:bg-white/50 p-1.5 pr-4 rounded-full border border-transparent hover:border-slate-200 transition duration-300 group">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-secondary p-[2px]">
                    <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                        <i class="ph ph-user text-xl text-primary"></i>
                    </div>
                </div>
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-slate-700 group-hover:text-primary transition">{{ Auth::user()->name ?? 'Admin' }}</p>
                    <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wide">Administrator</p>
                </div>
                <i class="ph ph-caret-down text-slate-500 text-xs transition-transform duration-300" :class="userDropdownOpen ? 'rotate-180' : ''"></i>
            </button>

            <div x-show="userDropdownOpen" 
                 @click.outside="userDropdownOpen = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-cloak
                 class="absolute right-0 mt-3 w-60 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl py-2 border border-slate-100 z-50">
                
                <div class="px-5 py-3 border-b border-slate-50 bg-slate-50/50">
                    <p class="text-xs text-slate-400 font-bold uppercase mb-1">Signed in as</p>
                    <p class="font-bold text-slate-800 truncate">{{ Auth::user()->email }}</p>
                </div>

                <div class="p-2 space-y-1">
                    <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-indigo-50 hover:text-primary rounded-xl transition">
                        <i class="ph ph-user text-lg"></i> Profile
                    </a>
                    <a href="{{ route('sales.history') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-indigo-50 hover:text-primary rounded-xl transition">
                        <i class="ph ph-clock-counter-clockwise text-lg"></i> ប្រវត្តិលក់
                    </a>
                    <a href="/admin/users" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-indigo-50 hover:text-primary rounded-xl transition">
                        <i class="ph ph-users text-lg"></i> បុគ្គលិក
                    </a>
                    <a href="{{ route('profile.settings') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-indigo-50 hover:text-primary rounded-xl transition">
                        <i class="ph ph-gear text-lg"></i> ការកំណត់
                    </a>
                </div>

                <div class="border-t border-slate-100 mt-1 p-2">
                    <button @click="logoutModalOpen = true; userDropdownOpen = false" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-red-500 hover:bg-red-50 rounded-xl transition">
                        <i class="ph ph-sign-out text-lg"></i> ចាកចេញ
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-1 overflow-y-auto p-4 md:p-8 scroll-smooth relative z-10">
        <div class="max-w-7xl mx-auto pb-10">
            
            <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-white drop-shadow-md">សួស្តី, {{ Auth::user()->name ?? 'Admin' }}! 👋</h2>
                    <p class="text-slate-300 text-sm mt-1 drop-shadow-sm">នេះជារបាយការណ៍សង្ខេបសម្រាប់ហាងរបស់អ្នក</p>
                </div>

                <div class="glass p-1.5 rounded-2xl shadow-sm flex flex-wrap items-center gap-2">
                    <form action="/admin/dashboard" method="GET" class="contents">
                        <div class="flex bg-slate-100/80 rounded-xl p-1">
                            <a href="/admin/dashboard?start_date={{ date('Y-m-d') }}&end_date={{ date('Y-m-d') }}" 
                               class="px-4 py-2 text-sm font-bold rounded-lg transition-all {{ request('start_date') == date('Y-m-d') ? 'bg-white text-primary shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                                ថ្ងៃនេះ
                            </a>
                            <a href="/admin/dashboard?start_date={{ date('Y-m-01') }}&end_date={{ date('Y-m-t') }}" 
                               class="px-4 py-2 text-sm font-bold rounded-lg transition-all {{ request('start_date') == date('Y-m-01') ? 'bg-white text-primary shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                                ខែនេះ
                            </a>
                        </div>

                        <div class="flex items-center gap-2 px-3 py-2 bg-slate-50/80 rounded-xl border border-slate-100/50">
                            <input type="date" name="start_date" value="{{ $startDate }}" class="bg-transparent border-none text-sm text-slate-600 focus:ring-0 cursor-pointer font-medium">
                            <span class="text-slate-300">|</span>
                            <input type="date" name="end_date" value="{{ $endDate }}" class="bg-transparent border-none text-sm text-slate-600 focus:ring-0 cursor-pointer font-medium">
                        </div>

                        <button type="submit" class="w-10 h-10 flex items-center justify-center bg-primary text-white rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                            <i class="ph ph-magnifying-glass text-lg"></i>
                        </button>

                        <button type="button" onclick="this.form.action='/admin/export-csv'; this.form.submit(); setTimeout(() => { this.form.action='/admin/dashboard'; }, 100);" 
                                class="h-10 px-4 flex items-center gap-2 bg-emerald-50/80 text-emerald-600 border border-emerald-100/50 rounded-xl hover:bg-emerald-100 transition font-bold text-sm ml-2">
                            <i class="ph ph-microsoft-excel-logo text-lg"></i>
                            <span class="hidden sm:inline">Export</span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
                <div class="bg-white/95 backdrop-blur-sm p-6 rounded-[2rem] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] border border-white/20 hover:-translate-y-1 transition-transform duration-300 group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-[4rem] -mr-4 -mt-4 transition-all group-hover:scale-110"></div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">ចំណូល (Income)</p>
                            <h3 class="text-3xl font-black text-slate-800 group-hover:text-blue-600 transition-colors">${{ number_format($totalSales, 2) }}</h3>
                            <div class="flex items-center gap-1 mt-2 text-xs font-medium text-emerald-500 bg-emerald-50 px-2 py-1 rounded-full w-fit">
                                <i class="ph ph-trend-up"></i> <span>Sales</span>
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm">
                            <i class="ph ph-currency-dollar"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white/95 backdrop-blur-sm p-6 rounded-[2rem] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] border border-white/20 hover:-translate-y-1 transition-transform duration-300 group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-red-50 rounded-bl-[4rem] -mr-4 -mt-4 transition-all group-hover:scale-110"></div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">ចំណាយ (Expense)</p>
                            <h3 class="text-3xl font-black text-slate-800 group-hover:text-red-500 transition-colors">${{ number_format($totalExpenses, 2) }}</h3>
                            <div class="flex items-center gap-1 mt-2 text-xs font-medium text-slate-400 bg-slate-50 px-2 py-1 rounded-full w-fit">
                                <i class="ph ph-arrow-down"></i> <span>Outgoing</span>
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-red-100 text-red-500 rounded-2xl flex items-center justify-center text-2xl shadow-sm">
                            <i class="ph ph-receipt"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white/95 backdrop-blur-sm p-6 rounded-[2rem] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] border border-white/20 hover:-translate-y-1 transition-transform duration-300 group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-[4rem] -mr-4 -mt-4 transition-all group-hover:scale-110"></div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">ប្រាក់ចំណេញ (Net)</p>
                            <h3 class="text-3xl font-black {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-red-500' }}">${{ number_format($netProfit, 2) }}</h3>
                            <div class="flex items-center gap-1 mt-2 text-xs font-medium text-emerald-600 bg-emerald-100 px-2 py-1 rounded-full w-fit">
                                <i class="ph ph-wallet"></i> <span>Real Profit</span>
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm">
                            <i class="ph ph-chart-line-up"></i>
                        </div>
                    </div>
                </div>

                <a href="/admin/products" class="bg-white/95 backdrop-blur-sm p-6 rounded-[2rem] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] border border-white/20 hover:-translate-y-1 transition-transform duration-300 group relative overflow-hidden cursor-pointer">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-bl-[4rem] -mr-4 -mt-4 transition-all group-hover:scale-110"></div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">ទំនិញក្នុងស្តុក</p>
                            <h3 class="text-3xl font-black text-slate-800 group-hover:text-purple-600 transition-colors">{{ $totalProducts }}</h3>
                            <div class="flex items-center gap-1 mt-2 text-xs font-medium text-purple-500 bg-purple-50 px-2 py-1 rounded-full w-fit">
                                <span>View All Products</span> <i class="ph ph-arrow-right"></i>
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm">
                            <i class="ph ph-package"></i>
                        </div>
                    </div>
                </a>
            </div>

            @if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
            <div class="mb-8 animate-fade-in-up">
                <div class="bg-red-50/95 backdrop-blur-sm border border-red-200 rounded-[1.5rem] p-6 relative overflow-hidden shadow-sm">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <i class="ph ph-warning-octagon text-9xl text-red-500"></i>
                    </div>
                    
                    <div class="flex items-center gap-3 mb-4 relative z-10">
                        <div class="w-10 h-10 rounded-full bg-red-500 text-white flex items-center justify-center shadow-lg shadow-red-200">
                            <i class="ph ph-bell-ringing text-xl animate-bounce"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-red-900">Low Stock Alert!</h3>
                            <p class="text-sm text-red-700 font-medium">មានទំនិញជិតអស់ស្តុក សូមពិនិត្យមើលឡើងវិញ។</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 relative z-10">
                        @foreach($lowStockProducts as $product)
                        <div class="flex items-center gap-3 bg-white/90 p-3 rounded-2xl shadow-sm border border-red-100/50">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden shrink-0">
                                <img src="{{ $product->image }}" class="w-full h-full object-cover">
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-700 truncate">{{ $product->name }}</p>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-slate-400">${{ $product->price }}</span>
                                    <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded font-bold">Left: {{ $product->stock }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white/95 backdrop-blur-sm p-6 md:p-8 rounded-[2rem] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-white/20">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <i class="ph ph-chart-line-up text-primary"></i> ចំណូលលក់ប្រចាំថ្ងៃ
                            </h2>
                            <p class="text-xs text-slate-400">ទិន្នន័យ 30 ថ្ងៃចុងក្រោយ</p>
                        </div>
                        <button class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center transition">
                            <i class="ph ph-dots-three text-xl"></i>
                        </button>
                    </div>
                    <div class="h-80 w-full">
                        <canvas id="dailySalesChart"></canvas>
                    </div>
                </div>

                <div class="lg:col-span-1 bg-white/95 backdrop-blur-sm p-6 md:p-8 rounded-[2rem] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-white/20">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <i class="ph ph-trophy text-yellow-500"></i> លក់ដាច់បំផុត
                            </h2>
                            <p class="text-xs text-slate-400">Top 5 Products</p>
                        </div>
                    </div>
                    <div class="h-80 w-full flex items-center justify-center">
                        <canvas id="bestSellerChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex gap-4">
                 <a href="/admin/expenses" class="flex items-center gap-3 bg-white/95 backdrop-blur-sm px-6 py-4 rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition border border-white/20 group">
                    <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 group-hover:scale-110 transition">
                        <i class="ph ph-money text-xl"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-bold text-slate-700">កត់ត្រាចំណាយ</p>
                        <p class="text-xs text-slate-400">Manage Expenses</p>
                    </div>
                </a>
                <a href="/admin/users" class="flex items-center gap-3 bg-white/95 backdrop-blur-sm px-6 py-4 rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition border border-white/20 group">
                    <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 group-hover:scale-110 transition">
                        <i class="ph ph-users-three text-xl"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-bold text-slate-700">បុគ្គលិក</p>
                        <p class="text-xs text-slate-400">Manage Users</p>
                    </div>
                </a>
            </div>

        </div>
    </main>

    <div x-show="logoutModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" x-cloak x-transition.opacity>
        <div class="bg-white rounded-[2rem] shadow-2xl w-[90%] max-w-sm p-8 text-center relative animate-in zoom-in-95 duration-200" @click.outside="logoutModalOpen = false">
            <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-lg">
                <i class="ph ph-sign-out text-4xl text-red-500 ml-1"></i>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 mb-2">ចាកចេញ?</h2>
            <p class="text-slate-500 mb-8 text-sm leading-relaxed">តើអ្នកពិតជាចង់ចាកចេញពីប្រព័ន្ធមែនទេ? <br>អ្នកត្រូវ Login ម្តងទៀតដើម្បីចូលប្រើ។</p>
            <div class="flex gap-3">
                <button @click="logoutModalOpen = false" class="flex-1 py-3.5 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition">
                    ទេ (Cancel)
                </button>
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full py-3.5 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 shadow-lg shadow-red-200 transition transform hover:scale-[1.02]">
                        បាទ/ចាស
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Chart Config
        Chart.defaults.font.family = "'Kantumruy Pro', sans-serif";
        Chart.defaults.color = '#64748b';

        // Daily Sales Chart
        const ctxSales = document.getElementById('dailySalesChart').getContext('2d');
        
        // Gradient for Line Chart
        let gradientSales = ctxSales.createLinearGradient(0, 0, 0, 400);
        gradientSales.addColorStop(0, 'rgba(79, 70, 229, 0.4)'); // Primary Color
        gradientSales.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

        new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: {!! json_encode($salesLabels) !!},
                datasets: [{
                    label: 'ចំណូល ($)',
                    data: {!! json_encode($salesData) !!},
                    borderColor: '#4f46e5',
                    backgroundColor: gradientSales,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4 // Smooth Curve
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 13 },
                        bodyFont: { size: 14, weight: 'bold' },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '$ ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#f1f5f9' },
                        ticks: { callback: (val) => '$' + val }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // Best Seller Chart
        const ctxBest = document.getElementById('bestSellerChart');
        new Chart(ctxBest, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($bsLabels) !!},
                datasets: [{
                    data: {!! json_encode($bsData) !!},
                    backgroundColor: [
                        '#4f46e5', '#0ea5e9', '#10b981', '#f59e0b', '#ef4444'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                }
            }
        });
    </script>
</body>
</html>