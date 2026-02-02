<!DOCTYPE html>
<html lang="km" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Manager - Red Rain Storm</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Kantumruy Pro', 'sans-serif'] },
                    colors: {
                        primary: '#dc2626', // Red 600
                        primaryHover: '#b91c1c', // Red 700
                        danger: '#ef4444', // Red 500
                        warning: '#f59e0b', // Amber 500
                    },
                    boxShadow: {
                        'card': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                        'glow-red': '0 0 0 4px rgba(220, 38, 38, 0.15)',
                    }
                }
            }
        }
    </script>

    <style>
        /* BASE SETTINGS */
        body {
            background-color: #f8fafc;
            color: #1e293b;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Lightning Canvas (Back Layer) */
        #lightning-canvas {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            z-index: -2; pointer-events: none;
        }

        /* Rain Container (Middle Layer) */
        .rain-container {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1; pointer-events: none;
            overflow: hidden;
            transform: skewX(-5deg); /* ធ្វើឱ្យភ្លៀងធ្លាក់ទ្រេត */
        }

        /* Rain Drop Style */
        .drop {
            position: absolute;
            bottom: 100%;
            width: 2px;
            height: 80px; /* តំណក់វែង */
            /* ពណ៌ក្រហមព្រាលៗ ដើម្បីឱ្យមើលឃើញលើផ្ទៃស */
            background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(220, 38, 38, 0.4));
            animation: fall linear infinite;
        }

        @keyframes fall {
            to { transform: translateY(120vh); }
        }

        /* Card Style */
        .clean-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            box-shadow: var(--tw-shadow-card);
        }

        /* INPUTS: RED BORDER */
        .clean-input {
            background: #ffffff;
            border: 2px solid #dc2626;
            color: #334155;
            transition: all 0.2s ease;
            border-radius: 0.75rem;
        }
        .clean-input:focus {
            border-color: #b91c1c;
            box-shadow: var(--tw-shadow-glow-red);
            outline: none;
        }

        /* Scrollbar */
        .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #ef4444; }
        
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="p-4 md:p-6" 
      x-data="{ 
          showEditModal: false, 
          showDeleteModal: false, 
          deleteUrl: '', 
          editData: { id: '', title: '', amount: '', category: '', date: '', description: '' },
          
          openEdit(expense) {
              this.editData = expense;
              this.showEditModal = true;
          }
      }">

    <canvas id="lightning-canvas"></canvas>

    <div class="rain-container">
        <div class="drop" style="left: 5%; animation-duration: 0.9s; animation-delay: 0.1s;"></div>
        <div class="drop" style="left: 12%; animation-duration: 1.1s; animation-delay: 0.3s;"></div>
        <div class="drop" style="left: 20%; animation-duration: 0.8s; animation-delay: 0.7s;"></div>
        <div class="drop" style="left: 25%; animation-duration: 1.2s; animation-delay: 0.2s;"></div>
        <div class="drop" style="left: 35%; animation-duration: 1.0s; animation-delay: 0.5s;"></div>
        <div class="drop" style="left: 42%; animation-duration: 0.9s; animation-delay: 0.1s;"></div>
        <div class="drop" style="left: 50%; animation-duration: 1.3s; animation-delay: 0.4s;"></div>
        <div class="drop" style="left: 58%; animation-duration: 1.1s; animation-delay: 0.6s;"></div>
        <div class="drop" style="left: 65%; animation-duration: 0.9s; animation-delay: 0.2s;"></div>
        <div class="drop" style="left: 75%; animation-duration: 1.2s; animation-delay: 0.8s;"></div>
        <div class="drop" style="left: 82%; animation-duration: 1.0s; animation-delay: 0.3s;"></div>
        <div class="drop" style="left: 90%; animation-duration: 1.4s; animation-delay: 0.5s;"></div>
        <div class="drop" style="left: 95%; animation-duration: 0.9s; animation-delay: 0.1s;"></div>
        
        <div class="drop" style="left: 8%; animation-duration: 1.5s; animation-delay: 1.1s; opacity: 0.3;"></div>
        <div class="drop" style="left: 30%; animation-duration: 1.7s; animation-delay: 1.5s; opacity: 0.3;"></div>
        <div class="drop" style="left: 48%; animation-duration: 1.6s; animation-delay: 1.2s; opacity: 0.3;"></div>
        <div class="drop" style="left: 68%; animation-duration: 1.8s; animation-delay: 1.8s; opacity: 0.3;"></div>
        <div class="drop" style="left: 88%; animation-duration: 1.5s; animation-delay: 1.4s; opacity: 0.3;"></div>
    </div>

    <div class="max-w-7xl mx-auto w-full h-full flex flex-col relative z-10">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 flex-shrink-0">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-slate-800 flex items-center gap-3">
                    <span class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-red-50 text-primary flex items-center justify-center shadow-sm border border-red-100">
                        <i class="ph ph-lightning text-xl md:text-2xl"></i>
                    </span>
                    Expense <span class="text-primary">Manager</span>
                </h1>
                <p class="text-slate-500 text-sm mt-1 font-medium ml-1">កត់ត្រា និងគ្រប់គ្រងការចំណាយ</p>
            </div>
            
            <a href="/admin/dashboard" class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-xl hover:border-red-200 hover:text-primary transition flex items-center gap-2 group font-bold shadow-sm text-sm">
                <i class="ph ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                <span>ត្រឡប់ទៅ Dashboard</span>
            </a>
        </div>

        <div class="flex-shrink-0 mb-6">
            <form action="{{ route('expenses.index') }}" method="GET" class="clean-card p-4 flex flex-col md:flex-row gap-4 items-end">
                <div class="w-full md:flex-1">
                    <label class="text-xs font-bold text-slate-500 ml-1 mb-1 block uppercase">ស្វែងរក (Search)</label>
                    <div class="relative">
                        <i class="ph ph-magnifying-glass absolute left-3 top-3.5 text-red-500"></i>
                        <input type="text" name="search" value="{{ request('search') }}" class="clean-input w-full pl-10 pr-4 py-2.5" placeholder="ឈ្មោះចំណាយ...">
                    </div>
                </div>
                
                <div class="w-full md:w-48">
                    <label class="text-xs font-bold text-slate-500 ml-1 mb-1 block uppercase">ប្រភេទ (Category)</label>
                    <div class="relative">
                        <select name="category" class="clean-input w-full px-3 py-2.5 appearance-none bg-white cursor-pointer">
                            <option value="all">ទាំងអស់</option>
                            <option value="ប្រតិបត្តិការ" {{ request('category') == 'ប្រតិបត្តិការ' ? 'selected' : '' }}>ប្រតិបត្តិការ</option>
                            <option value="ទឹកភ្លើង" {{ request('category') == 'ទឹកភ្លើង' ? 'selected' : '' }}>ទឹកភ្លើង</option>
                            <option value="ប្រាក់ខែ" {{ request('category') == 'ប្រាក់ខែ' ? 'selected' : '' }}>ប្រាក់ខែ</option>
                            <option value="ទិញចូល" {{ request('category') == 'ទិញចូល' ? 'selected' : '' }}>ទិញចូល</option>
                            <option value="ផ្សេងៗ" {{ request('category') == 'ផ្សេងៗ' ? 'selected' : '' }}>ផ្សេងៗ</option>
                        </select>
                        <i class="ph ph-caret-down absolute right-3 top-3.5 text-red-500 pointer-events-none"></i>
                    </div>
                </div>

                <div class="flex gap-2 w-full md:w-auto">
                    <div class="flex-1">
                        <label class="text-xs font-bold text-slate-500 ml-1 mb-1 block uppercase">ចាប់ពីថ្ងៃ</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="clean-input w-full px-3 py-2.5 cursor-pointer">
                    </div>
                    <div class="flex-1">
                        <label class="text-xs font-bold text-slate-500 ml-1 mb-1 block uppercase">ដល់ថ្ងៃ</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="clean-input w-full px-3 py-2.5 cursor-pointer">
                    </div>
                </div>

                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-bold rounded-xl hover:bg-primaryHover transition shadow-lg shadow-red-200 h-[48px] flex items-center justify-center gap-2 w-full md:w-auto mt-auto border-2 border-primary">
                    <i class="ph ph-funnel text-lg"></i> តម្រង
                </button>
            </form>
        </div>

        <div class="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <div class="lg:col-span-4 flex-shrink-0 overflow-y-auto custom-scroll max-h-full">
                
                @if(session('success'))
                    <div class="clean-card border-l-4 border-green-500 bg-green-50 text-green-700 px-4 py-3 mb-4 flex items-center gap-3 animate-in slide-in-from-top-2 shadow-sm">
                        <i class="ph ph-check-circle text-xl"></i>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="clean-card p-6">
                    <h2 class="text-lg font-bold text-slate-800 mb-5 flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i class="ph ph-plus-circle text-primary text-xl"></i> បញ្ចូលចំណាយថ្មី
                    </h2>

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-600 p-3 rounded-xl mb-4 text-xs">
                            <ul class="list-disc pl-4">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('expenses.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1 ml-1 uppercase">ចំណងជើង</label>
                            <input type="text" name="title" required class="clean-input w-full px-4 py-2.5" placeholder="ឧ. ទិញទឹកកក...">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1 ml-1 uppercase">ប្រភេទចំណាយ</label>
                            <div class="relative">
                                <select name="category" class="clean-input w-full px-4 py-2.5 appearance-none bg-white cursor-pointer">
                                    <option value="ប្រតិបត្តិការ">ប្រតិបត្តិការ</option>
                                    <option value="ទឹកភ្លើង">ទឹកភ្លើង</option>
                                    <option value="ប្រាក់ខែ">ប្រាក់ខែ</option>
                                    <option value="ទិញចូល">ទិញចូល</option>
                                    <option value="ផ្សេងៗ">ផ្សេងៗ</option>
                                </select>
                                <i class="ph ph-caret-down absolute right-3 top-3.5 text-red-500 pointer-events-none"></i>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1 ml-1 uppercase">ចំនួន ($)</label>
                                <input type="number" step="0.01" name="amount" required class="clean-input w-full pl-4 pr-4 py-2.5 font-bold text-primary text-lg" placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1 ml-1 uppercase">កាលបរិច្ឆេទ</label>
                                <input type="date" name="date" required class="clean-input w-full px-4 py-2.5 cursor-pointer" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1 ml-1 uppercase">បរិយាយ</label>
                            <textarea name="description" rows="2" class="clean-input w-full px-4 py-2.5 resize-none" placeholder="ព័ត៌មានបន្ថែម..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-primary text-white font-bold py-3.5 rounded-xl shadow-lg shadow-red-200 hover:bg-primaryHover hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2 mt-2 border-2 border-primary">
                            <i class="ph ph-floppy-disk text-lg"></i> រក្សាទុក (Save)
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-8 flex flex-col h-full min-h-0">
                <div class="clean-card flex flex-col h-full overflow-hidden">
                    
                    <div class="flex justify-between items-center p-5 border-b border-slate-100 bg-slate-50/50 flex-shrink-0">
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">ប្រវត្តិចំណាយ</h2>
                            <p class="text-xs text-slate-500">Transaction History</p>
                        </div>
                        <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm">
                            <span class="text-xs font-bold text-slate-400 uppercase">សរុប</span>
                            <span class="text-xl font-black text-primary">-${{ number_format($expenses->sum('amount'), 2) }}</span>
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto custom-scroll p-3">
                        <table class="w-full text-left border-separate border-spacing-y-2">
                            <thead class="text-slate-500 text-[11px] uppercase font-bold tracking-wider sticky top-0 bg-white/95 backdrop-blur-sm z-10 shadow-sm">
                                <tr>
                                    <th class="p-4 pl-6 rounded-l-lg">ចំណងជើង & ប្រភេទ</th>
                                    <th class="p-4">កាលបរិច្ឆេទ</th>
                                    <th class="p-4">ចំនួន ($)</th>
                                    <th class="p-4 text-center rounded-r-lg">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse($expenses as $expense)
                                <tr class="group hover:bg-slate-50 transition-all duration-200 rounded-2xl bg-white border border-slate-100 hover:border-red-200 hover:shadow-sm">
                                    <td class="p-4 pl-6 rounded-l-2xl">
                                        <p class="font-bold text-slate-700 group-hover:text-primary transition">{{ $expense->title }}</p>
                                        <span class="inline-flex items-center gap-1 mt-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold border border-slate-200 text-slate-500 bg-slate-50">
                                            {{ $expense->category ?? 'General' }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-slate-500 font-medium">{{ \Carbon\Carbon::parse($expense->date)->format('d M, Y') }}</td>
                                    <td class="p-4 font-bold text-primary">-${{ number_format($expense->amount, 2) }}</td>
                                    <td class="p-4 rounded-r-2xl">
                                        <div class="flex justify-center gap-2">
                                            <button @click="openEdit({{ $expense }})" class="w-9 h-9 rounded-xl bg-orange-50 text-warning border border-orange-100 hover:bg-warning hover:text-white transition flex items-center justify-center shadow-sm active:scale-95">
                                                <i class="ph ph-pencil-simple text-lg"></i>
                                            </button>
                                            <button @click="showDeleteModal = true; deleteUrl = '{{ route('expenses.destroy', $expense->id) }}'" class="w-9 h-9 rounded-xl bg-red-50 text-danger border border-red-100 hover:bg-danger hover:text-white transition flex items-center justify-center shadow-sm active:scale-95">
                                                <i class="ph ph-trash text-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-16 text-center text-slate-400 flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-3 shadow-inner">
                                            <i class="ph ph-ghost text-3xl text-slate-400"></i>
                                        </div>
                                        <span class="font-medium">មិនមានទិន្នន័យទេ</span>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-4 border-t border-slate-100 flex-shrink-0 bg-white">
                        {{ $expenses->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const canvas = document.getElementById('lightning-canvas');
        const ctx = canvas.getContext('2d');
        let width, height;

        function resize() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        }
        window.addEventListener('resize', resize);
        resize();

        function random(min, max) { return Math.random() * (max - min) + min; }

        class Lightning {
            constructor() {
                this.x = random(100, width - 100);
                this.y = 0;
                this.segments = [];
                this.life = 0;
                this.opacity = 1;
                this.generate();
            }

            generate() {
                let currentX = this.x;
                let currentY = this.y;
                while (currentY < height) {
                    let nextX = currentX + random(-30, 30);
                    let nextY = currentY + random(10, 40);
                    this.segments.push({x1: currentX, y1: currentY, x2: nextX, y2: nextY});
                    currentX = nextX;
                    currentY = nextY;
                }
            }

            draw() {
                // Flash Effect (Pinkish Red)
                if (this.life < 5) {
                    ctx.fillStyle = `rgba(255, 200, 200, ${0.1 - (this.life * 0.02)})`;
                    ctx.fillRect(0, 0, width, height);
                }

                ctx.beginPath();
                ctx.strokeStyle = `rgba(220, 38, 38, ${this.opacity})`; // Red Color
                ctx.lineWidth = 2;
                ctx.shadowBlur = 10;
                ctx.shadowColor = '#ef4444'; // Red Glow

                for (let segment of this.segments) {
                    ctx.moveTo(segment.x1, segment.y1);
                    ctx.lineTo(segment.x2, segment.y2);
                }
                ctx.stroke();

                this.life++;
                this.opacity -= 0.05;
            }
        }

        let lightnings = [];

        function animate() {
            ctx.clearRect(0, 0, width, height);
            if (Math.random() < 0.01) { lightnings.push(new Lightning()); } // Spawn Chance
            
            for (let i = 0; i < lightnings.length; i++) {
                lightnings[i].draw();
                if (lightnings[i].opacity <= 0) { lightnings.splice(i, 1); i--; }
            }
            requestAnimationFrame(animate);
        }
        animate();
    </script>

    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm" x-cloak x-transition.opacity>
        <div class="clean-card w-[90%] max-w-md p-6 relative animate-in zoom-in-95 shadow-2xl" @click.away="showEditModal = false">
            <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-3">
                <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <i class="ph ph-pencil-simple text-warning text-2xl"></i> កែប្រែចំណាយ
                </h3>
                <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 p-1 hover:bg-slate-100 rounded-full transition"><i class="ph ph-x text-xl"></i></button>
            </div>
            <form :action="'/admin/expenses/' + editData.id" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div><label class="block text-xs font-bold text-slate-500 mb-1">ចំណងជើង</label><input type="text" name="title" x-model="editData.title" required class="clean-input w-full px-4 py-2.5"></div>
                <div><label class="block text-xs font-bold text-slate-500 mb-1">ប្រភេទ</label><select name="category" x-model="editData.category" class="clean-input w-full px-4 py-2.5 appearance-none bg-white cursor-pointer"><option value="ប្រតិបត្តិការ">ប្រតិបត្តិការ</option><option value="ទឹកភ្លើង">ទឹកភ្លើង</option><option value="ប្រាក់ខែ">ប្រាក់ខែ</option><option value="ទិញចូល">ទិញចូល</option><option value="ផ្សេងៗ">ផ្សេងៗ</option></select></div>
                <div class="grid grid-cols-2 gap-3"><div><label class="block text-xs font-bold text-slate-500 mb-1">ចំនួន ($)</label><input type="number" step="0.01" name="amount" x-model="editData.amount" required class="clean-input w-full px-4 py-2.5 font-bold text-primary"></div><div><label class="block text-xs font-bold text-slate-500 mb-1">កាលបរិច្ឆេទ</label><input type="date" name="date" x-model="editData.date" required class="clean-input w-full px-4 py-2.5 cursor-pointer"></div></div>
                <div><label class="block text-xs font-bold text-slate-500 mb-1">បរិយាយ</label><textarea name="description" x-model="editData.description" rows="3" class="clean-input w-full px-4 py-2.5 resize-none"></textarea></div>
                <div class="flex gap-3 mt-6"><button type="button" @click="showEditModal = false" class="flex-1 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition">បោះបង់</button><button type="submit" class="flex-1 py-3 bg-warning text-white rounded-xl font-bold hover:bg-orange-500 shadow-lg transition">រក្សាទុក</button></div>
            </form>
        </div>
    </div>

    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm" x-cloak x-transition.opacity>
        <div class="clean-card w-[90%] max-w-sm p-8 text-center relative animate-in zoom-in-95 shadow-2xl" @click.away="showDeleteModal = false">
            <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm border border-red-100"><i class="ph ph-warning-circle text-4xl text-danger"></i></div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">លុបការចំណាយនេះ?</h3>
            <p class="text-sm text-slate-500 mb-8 leading-relaxed">ទិន្នន័យនឹងត្រូវលុបជាអចិន្ត្រៃយ៍។ <br>តើអ្នកពិតជាចង់បន្តមែនទេ?</p>
            <div class="flex gap-3"><button @click="showDeleteModal = false" class="flex-1 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition">ទេ (Cancel)</button><form :action="deleteUrl" method="POST" class="flex-1">@csrf @method('DELETE')<button type="submit" class="w-full py-3 bg-danger text-white rounded-xl font-bold hover:bg-red-600 shadow-lg shadow-red-200 transition hover:-translate-y-0.5">លុប (Delete)</button></form></div>
        </div>
    </div>

</body>
</html>