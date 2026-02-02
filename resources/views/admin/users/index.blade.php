<!DOCTYPE html>
<html lang="km" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Light Gray</title>
    
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
                    },
                    boxShadow: {
                        'card': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
                        'glow-red': '0 0 0 4px rgba(220, 38, 38, 0.1)',
                    }
                }
            }
        }
    </script>

    <style>
        /* LIGHT GRAY BACKGROUND - FIXED LAYOUT */
        body {
            background-color: #f3f4f6; /* Gray 100 - ពណ៌ប្រផេះស្រាល */
            color: #1f2937; /* Gray 800 */
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Subtle Pattern */
        body::before {
            content: "";
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-image: radial-gradient(#d1d5db 1px, transparent 1px);
            background-size: 24px 24px;
            opacity: 0.5; z-index: -3;
        }

        /* --- RAIN EFFECT (Very Subtle Red) --- */
        .rain-container {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            z-index: -2; pointer-events: none; overflow: hidden; transform: skewX(-10deg);
        }
        .drop {
            position: absolute; bottom: 100%; width: 1px; height: 60px;
            /* ពណ៌ក្រហមស្រាលបំផុត */
            background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(220, 38, 38, 0.15));
            animation: fall linear infinite;
        }

        /* --- HAIL EFFECT (Subtle White/Grey) --- */
        .hail-container {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            z-index: -2; pointer-events: none; overflow: hidden;
        }
        .hail {
            position: absolute; bottom: 100%; width: 3px; height: 3px;
            background: #9ca3af; /* Gray 400 */
            border-radius: 50%;
            opacity: 0.4;
            animation: fall-fast linear infinite;
        }

        @keyframes fall { to { transform: translateY(120vh); } }
        @keyframes fall-fast { to { transform: translateY(120vh) translateX(-10px); } }

        /* CLEAN WHITE CARD */
        .clean-card {
            background: #ffffff;
            border-radius: 1rem;
            border: 1px solid #e5e7eb; /* Gray 200 */
            box-shadow: var(--tw-shadow-card);
        }

        /* INPUTS: RED BORDER */
        .clean-input {
            background: #ffffff;
            border: 2px solid #dc2626; /* Red Border */
            color: #374151;
            transition: all 0.2s ease;
            border-radius: 0.75rem;
        }
        .clean-input:focus {
            border-color: #b91c1c;
            box-shadow: var(--tw-shadow-glow-red);
            outline: none;
        }

        /* Custom Scrollbar */
        .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #dc2626; }
        
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="p-4 md:p-6" x-data="{ showDeleteModal: false, deleteUrl: '' }">

    <div class="rain-container">
        <script>
            for(let i=0; i<15; i++) {
                let d = document.createElement('div'); d.className = 'drop';
                d.style.left = Math.random() * 100 + '%';
                d.style.animationDuration = (Math.random() * 0.5 + 0.7) + 's';
                d.style.animationDelay = Math.random() + 's';
                document.write(d.outerHTML);
            }
        </script>
    </div>
    <div class="hail-container">
        <script>
            for(let i=0; i<10; i++) {
                let h = document.createElement('div'); h.className = 'hail';
                h.style.left = Math.random() * 100 + '%';
                h.style.animationDuration = (Math.random() * 0.5 + 0.5) + 's';
                h.style.animationDelay = Math.random() + 's';
                document.write(h.outerHTML);
            }
        </script>
    </div>

    <div class="max-w-7xl mx-auto w-full h-full flex flex-col relative z-10">
        
        <div class="flex justify-between items-center mb-6 flex-shrink-0">
            <div>
                <h1 class="text-2xl font-black text-gray-800 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-red-50 text-primary flex items-center justify-center border border-red-100 shadow-sm">
                        <i class="ph ph-users text-2xl"></i>
                    </span>
                    User <span class="text-primary">Management</span>
                </h1>
                <p class="text-gray-500 text-xs mt-1 font-bold ml-1 uppercase tracking-wide">គ្រប់គ្រងបុគ្គលិក (Light Edition)</p>
            </div>
            
            <a href="/admin/dashboard" class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-xl hover:border-red-200 hover:text-primary transition flex items-center gap-2 group font-bold shadow-sm text-sm">
                <i class="ph ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                <span>ត្រឡប់ក្រោយ</span>
            </a>
        </div>

        @if(session('success'))
            <div class="clean-card border-l-4 border-green-500 bg-green-50 text-green-700 px-4 py-3 mb-6 flex items-center gap-3 animate-in slide-in-from-top-2">
                <i class="ph ph-check-circle text-xl"></i> {{ session('success') }}
            </div>
        @endif

        <div class="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <div class="lg:col-span-1 flex-shrink-0 overflow-y-auto custom-scroll max-h-full">
                <div class="clean-card p-6 border-t-4 border-t-primary">
                    <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="ph ph-user-plus text-primary text-xl"></i> បង្កើតបុគ្គលិកថ្មី
                    </h2>
                    
                    <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1 ml-1">ឈ្មោះ (Name)</label>
                            <input type="text" name="name" required class="clean-input w-full px-4 py-2.5" placeholder="ឈ្មោះបុគ្គលិក">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1 ml-1">អ៊ីមែល (Login Email)</label>
                            <input type="email" name="email" required class="clean-input w-full px-4 py-2.5" placeholder="email@example.com">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1 ml-1">លេខសម្ងាត់ (Password)</label>
                            <input type="password" name="password" required class="clean-input w-full px-4 py-2.5" placeholder="******">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1 ml-1">តួនាទី (Role)</label>
                            <div class="relative">
                                <select name="role" class="clean-input w-full px-4 py-2.5 appearance-none bg-white cursor-pointer">
                                    <option value="cashier">Cashier (អ្នកលក់)</option>
                                    <option value="admin">Admin (អ្នកគ្រប់គ្រង)</option>
                                </select>
                                <i class="ph ph-caret-down absolute right-3 top-3.5 text-primary pointer-events-none"></i>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-primary text-white font-bold py-3.5 rounded-xl shadow-md hover:bg-primaryHover hover:-translate-y-0.5 transition mt-2">
                            <i class="ph ph-floppy-disk text-lg"></i> រក្សាទុក (Save)
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2 flex flex-col h-full min-h-0">
                <div class="clean-card flex flex-col h-full overflow-hidden border-t-4 border-t-gray-300">
                    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center flex-shrink-0">
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">បញ្ជីបុគ្គលិក</h2>
                            <p class="text-xs text-gray-500">Users List</p>
                        </div>
                        <span class="text-xs text-gray-500 font-bold bg-white px-3 py-1 rounded-full border border-gray-200 shadow-sm">Total: {{ $users->count() }}</span>
                    </div>

                    <div class="flex-1 overflow-y-auto custom-scroll p-3">
                        <table class="w-full text-left border-separate border-spacing-y-2">
                            <thead class="text-gray-500 text-[11px] uppercase font-bold tracking-wider sticky top-0 bg-white/95 backdrop-blur-sm z-10 shadow-sm">
                                <tr>
                                    <th class="p-4 pl-6 rounded-l-lg">ឈ្មោះ</th>
                                    <th class="p-4">អ៊ីមែល</th>
                                    <th class="p-4">តួនាទី</th>
                                    <th class="p-4 text-center rounded-r-lg">សកម្មភាព</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @foreach($users as $user)
                                <tr class="group hover:bg-red-50/30 transition duration-200 rounded-2xl bg-white border border-gray-100 hover:border-red-200 hover:shadow-sm">
                                    <td class="p-4 pl-6 rounded-l-2xl">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold border border-gray-200 group-hover:bg-primary group-hover:text-white transition shadow-sm">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <span class="font-bold text-gray-700 group-hover:text-primary transition">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="p-4 text-gray-500">{{ $user->email }}</td>
                                    <td class="p-4">
                                        @if($user->role === 'admin')
                                            <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full text-[10px] font-bold border border-purple-100 flex items-center gap-1 w-fit">
                                                <i class="ph ph-crown"></i> Admin
                                            </span>
                                        @else
                                            <span class="bg-green-50 text-green-600 px-3 py-1 rounded-full text-[10px] font-bold border border-green-100 flex items-center gap-1 w-fit">
                                                <i class="ph ph-storefront"></i> Cashier
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center rounded-r-2xl">
                                        <button @click="showDeleteModal = true; deleteUrl = '{{ route('users.destroy', $user->id) }}'" 
                                                class="w-9 h-9 rounded-xl bg-red-50 text-danger border border-red-100 hover:bg-danger hover:text-white transition flex items-center justify-center shadow-sm active:scale-95">
                                            <i class="ph ph-trash text-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/40 backdrop-blur-sm" x-cloak x-transition.opacity>
        <div class="clean-card w-[90%] max-w-sm p-8 text-center relative animate-in zoom-in-95 shadow-2xl" @click.away="showDeleteModal = false">
            <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-5 border border-red-100 shadow-sm">
                <i class="ph ph-warning-circle text-4xl text-danger"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">លុបបុគ្គលិកនេះ?</h3>
            <p class="text-sm text-gray-500 mb-8 leading-relaxed">សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ។ <br>តើអ្នកពិតជាចង់បន្តមែនទេ?</p>
            <div class="flex gap-3">
                <button @click="showDeleteModal = false" class="flex-1 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold hover:bg-gray-200 transition">ទេ (Cancel)</button>
                <form :action="deleteUrl" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full py-3 bg-danger text-white rounded-xl font-bold hover:bg-red-600 shadow-lg shadow-red-200 transition hover:-translate-y-0.5">លុប (Delete)</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>