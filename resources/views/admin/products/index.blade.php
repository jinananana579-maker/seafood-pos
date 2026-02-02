<!DOCTYPE html>
<html lang="km" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management - Blue Edition</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Kantumruy Pro', 'sans-serif'] },
                    colors: {
                        primary: '#2563eb', // Blue 600
                        primaryHover: '#1d4ed8', // Blue 700
                        secondary: '#0ea5e9', // Sky 500
                        surface: '#f0f9ff', // Light Blue Surface
                        dark: '#0f172a', // Slate 900
                    },
                    boxShadow: {
                        'glow-blue': '0 0 20px -5px rgba(37, 99, 235, 0.4)',
                    }
                }
            }
        }
    </script>

    <style>
        body { 
            background-color: #f8fafc; 
            background-image: radial-gradient(#e0f2fe 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 10px 15px -3px rgba(37, 99, 235, 0.1);
        }
        .input-blue {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        .input-blue:focus {
            background: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            outline: none;
        }
        .table-row-hover:hover td { background-color: #eff6ff; }
        .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #93c5fd; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #3b82f6; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="text-slate-600 antialiased h-full flex flex-col"
      x-data="{ 
          showEditModal: false, 
          showDeleteModal: false, 
          deleteUrl: '', 
          editData: { id: '', name: '', barcode: '', price: '', stock: '', unit: '', category: '', image_url: '' },
          
          openEdit(product) {
              this.editData = product;
              this.showEditModal = true;
          }
      }">

    <div class="max-w-[1400px] mx-auto w-full p-4 md:p-6 flex-1 flex flex-col min-h-0">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-cyan-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                        <i class="ph ph-cube-transparent text-2xl"></i>
                    </div>
                    <span>Product <span class="text-blue-600">Manager</span></span>
                </h1>
                <p class="text-slate-400 text-sm mt-1 ml-1 font-medium">គ្រប់គ្រងទំនិញ និងស្តុករបស់អ្នក (Blue Edition)</p>
            </div>
            
            <div class="flex gap-3">
                <a href="/admin/dashboard" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition flex items-center gap-2 shadow-sm">
                    <i class="ph ph-arrow-left"></i> ត្រឡប់ក្រោយ
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 px-4 py-3 rounded-r-xl mb-6 flex items-center gap-3 shadow-sm animate-in fade-in slide-in-from-top-2">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="ph ph-check-circle text-lg"></i>
                </div>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-6 h-full min-h-0">
            
            <div class="w-full lg:w-1/3 xl:w-[350px] flex-shrink-0">
                <div class="glass-card rounded-[2rem] p-6 sticky top-6">
                    <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2 pb-4 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class="ph ph-plus-bold"></i>
                        </div>
                        បញ្ចូលទំនិញថ្មី
                    </h2>
                    
                    <form action="/admin/products" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        
                        <div class="group">
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5 ml-1">Barcode</label>
                            <div class="relative">
                                <input type="text" name="barcode" class="input-blue w-full rounded-xl px-4 py-3 pl-10 font-mono text-slate-700 font-bold" placeholder="Scan...">
                                <i class="ph ph-barcode absolute left-3 top-3.5 text-blue-400 text-lg"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5 ml-1">ឈ្មោះទំនិញ</label>
                            <input type="text" name="name" required class="input-blue w-full rounded-xl px-4 py-3 text-slate-700 font-bold" placeholder="Product Name...">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5 ml-1">តម្លៃ ($)</label>
                                <input type="number" step="0.01" name="price" required class="input-blue w-full rounded-xl px-4 py-3 text-slate-700 font-bold" placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5 ml-1">ស្តុក</label>
                                <input type="number" name="stock" value="0" class="input-blue w-full rounded-xl px-4 py-3 text-slate-700 font-bold">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5 ml-1">ខ្នាត</label>
                                <div class="relative">
                                    <select name="unit" class="input-blue w-full rounded-xl px-3 py-3 text-slate-700 font-bold appearance-none cursor-pointer">
                                        <option value="can">កំប៉ុង</option>
                                        <option value="bottle">ដប</option>
                                        <option value="kg">គីឡូ</option>
                                        <option value="plate">ចាន</option>
                                        <option value="box">កេស</option>
                                        <option value="pcs">ដុំ/គ្រាប់</option>
                                    </select>
                                    <i class="ph ph-caret-down absolute right-3 top-3.5 text-blue-400 pointer-events-none"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5 ml-1">ប្រភេទ</label>
                                <div class="relative">
                                    <select name="category" class="input-blue w-full rounded-xl px-3 py-3 text-slate-700 font-bold appearance-none cursor-pointer">
                                        <option value="drink">ភេសជ្ជៈ (Drink)</option>
                                        <option value="beer">ស្រាបៀរ (Beer)</option>
                                        <option value="seafood">គ្រឿងសមុទ្រ (Seafood)</option>
                                        <option value="fruit">ផ្លែឈើ (Fruit)</option>
                                        <option value="vegetable">បន្លែ (Vegetable)</option>
                                        <option value="meat">សាច់ (Meat)</option>
                                        <option value="milk">ទឹកដោះគោ (Milk)</option>
                                        <option value="food">អាហារ (Food)</option>
                                        <option value="snack">នំចំណី (Snack)</option>
                                        <option value="other">ផ្សេងៗ (Other)</option>
                                    </select>
                                    <i class="ph ph-caret-down absolute right-3 top-3.5 text-blue-400 pointer-events-none"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5 ml-1">រូបភាព</label>
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-blue-100 border-dashed rounded-2xl cursor-pointer bg-blue-50/50 hover:bg-blue-100/50 hover:border-blue-300 transition-all group">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="ph ph-cloud-arrow-up text-3xl text-blue-400 mb-2 group-hover:scale-110 transition-transform"></i>
                                    <p class="text-xs text-slate-500 font-bold">ចុចដើម្បីដាក់រូបភាព</p>
                                </div>
                                <input type="file" name="image" class="hidden" />
                            </label>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-500 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-300/50 hover:shadow-blue-400/60 hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-2">
                            <i class="ph ph-floppy-disk text-lg"></i> រក្សាទុក
                        </button>
                    </form>
                </div>
            </div>

            <div class="flex-1 min-w-0 glass-card rounded-[2rem] flex flex-col overflow-hidden h-full">
                
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white/50">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <span class="w-2 h-6 bg-blue-500 rounded-full"></span>
                            បញ្ជីទំនិញ
                        </h2>
                    </div>
                    <div class="flex gap-2">
                        {{ $products->links('pagination::simple-tailwind') }}
                    </div>
                </div>

                <div class="flex-1 overflow-auto custom-scroll p-4 bg-slate-50/30">
                    <table class="w-full text-left border-separate border-spacing-y-3">
                        <thead class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-2 pl-8">ផលិតផល</th>
                                <th class="px-6 py-2">ប្រភេទ</th>
                                <th class="px-6 py-2">តម្លៃ</th>
                                <th class="px-6 py-2">ស្តុក</th>
                                <th class="px-6 py-2 text-center pr-8">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr class="group bg-white rounded-2xl shadow-sm hover:shadow-md hover:shadow-blue-200/50 hover:-translate-y-0.5 transition-all duration-300 border border-transparent hover:border-blue-100">
                                <td class="px-6 py-4 pl-8 rounded-l-2xl">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-xl bg-blue-50 border border-blue-100 p-1 flex-shrink-0 relative overflow-hidden group-hover:scale-105 transition-transform">
                                            <img src="{{ $product->image_url }}" 
                                                 class="w-full h-full object-cover rounded-lg"
                                                 onerror="this.src='https://placehold.co/100?text=No+Img'">
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-700 text-base group-hover:text-blue-600 transition">{{ $product->name }}</p>
                                            <p class="text-xs font-mono text-slate-400 mt-0.5">{{ $product->barcode ?? '---' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-cyan-50 text-cyan-600 border border-cyan-100 uppercase tracking-wide">
                                        {{ $product->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-black text-slate-800 text-base">${{ number_format($product->price, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="h-2.5 w-2.5 rounded-full {{ $product->stock > 10 ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]' : 'bg-red-500 animate-pulse' }}"></div>
                                        <span class="text-sm font-bold text-slate-600">{{ $product->stock }} <span class="text-xs text-slate-400">{{ $product->unit }}</span></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 rounded-r-2xl">
                                    <div class="flex justify-center items-center gap-2 opacity-70 group-hover:opacity-100 transition-all">
                                        
                                        <a href="{{ route('products.printBarcode', $product->id) }}" target="_blank" 
                                           class="w-9 h-9 rounded-xl bg-slate-100 text-slate-500 hover:bg-slate-800 hover:text-white transition flex items-center justify-center" title="Print Barcode">
                                            <i class="ph ph-barcode text-lg"></i>
                                        </a>

                                        <button @click="openEdit({{ $product }})" 
                                                class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition flex items-center justify-center border border-blue-100">
                                            <i class="ph ph-pencil-simple text-lg"></i>
                                        </button>

                                        <button @click="showDeleteModal = true; deleteUrl = '{{ route('products.destroy', $product->id) }}'" 
                                                class="w-9 h-9 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition flex items-center justify-center border border-red-100">
                                            <i class="ph ph-trash text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak x-transition.opacity>
        <div class="bg-white w-full max-w-lg rounded-3xl p-8 relative shadow-2xl animate-in zoom-in-95" @click.away="showEditModal = false">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                    <i class="ph ph-pencil-simple text-blue-600"></i> កែប្រែទំនិញ
                </h3>
                <button @click="showEditModal = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition"><i class="ph ph-x"></i></button>
            </div>
            
            <form :action="'/admin/products/' + editData.id" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf @method('PUT')
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase">Barcode</label>
                        <input type="text" name="barcode" x-model="editData.barcode" class="input-blue w-full rounded-xl px-4 py-2.5 font-bold text-slate-700 mt-1">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase">ឈ្មោះ</label>
                        <input type="text" name="name" x-model="editData.name" required class="input-blue w-full rounded-xl px-4 py-2.5 font-bold text-slate-700 mt-1">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase">តម្លៃ ($)</label>
                        <input type="number" step="0.01" name="price" x-model="editData.price" required class="input-blue w-full rounded-xl px-4 py-2.5 font-bold text-slate-700 mt-1">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase">ស្តុក</label>
                        <input type="number" name="stock" x-model="editData.stock" class="input-blue w-full rounded-xl px-4 py-2.5 font-bold text-slate-700 mt-1">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase">ខ្នាត</label>
                        <select name="unit" x-model="editData.unit" class="input-blue w-full rounded-xl px-4 py-2.5 font-bold text-slate-700 mt-1 appearance-none">
                            <option value="can">កំប៉ុង</option>
                            <option value="bottle">ដប</option>
                            <option value="kg">គីឡូ</option>
                            <option value="plate">ចាន</option>
                            <option value="box">កេស</option>
                            <option value="pcs">ដុំ/គ្រាប់</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase">ប្រភេទ</label>
                        <select name="category" x-model="editData.category" class="input-blue w-full rounded-xl px-4 py-2.5 font-bold text-slate-700 mt-1 appearance-none">
                            <option value="drink">ភេសជ្ជៈ</option>
                            <option value="beer">ស្រាបៀរ</option>
                            <option value="seafood">គ្រឿងសមុទ្រ</option>
                            <option value="fruit">ផ្លែឈើ</option>
                            <option value="vegetable">បន្លែ</option>
                            <option value="meat">សាច់</option>
                            <option value="milk">ទឹកដោះគោ</option>
                            <option value="food">អាហារ</option>
                            <option value="snack">នំចំណី</option>
                            <option value="other">ផ្សេងៗ</option>
                        </select>
                    </div>
                </div>

                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 flex items-center gap-4">
                    <div class="w-16 h-16 bg-white rounded-lg overflow-hidden border border-blue-100">
                        <img :src="editData.image_url" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <label class="text-xs font-bold text-blue-500 uppercase">ប្តូររូបភាព</label>
                        <input type="file" name="image" class="block w-full text-xs text-slate-500 mt-1 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-white file:text-blue-600 hover:file:bg-blue-100 transition cursor-pointer">
                    </div>
                </div>

                <div class="flex gap-3 mt-6 pt-4 border-t border-slate-100">
                    <button type="button" @click="showEditModal = false" class="flex-1 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold hover:bg-slate-50 transition">បោះបង់</button>
                    <button type="submit" class="flex-1 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" x-cloak x-transition.opacity>
        <div class="bg-white w-[90%] max-w-sm p-8 text-center rounded-3xl shadow-2xl relative animate-in zoom-in-95" @click.away="showDeleteModal = false">
            <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-red-100 animate-bounce">
                <i class="ph ph-trash text-4xl text-red-500"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800 mb-2">លុបទំនិញនេះ?</h3>
            <p class="text-slate-500 mb-8 font-medium">ទិន្នន័យនឹងត្រូវបានលុបជាអចិន្ត្រៃយ៍។</p>
            <div class="flex gap-3">
                <button @click="showDeleteModal = false" class="flex-1 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold hover:bg-slate-50 transition">ទេ</button>
                <form :action="deleteUrl" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full py-3 bg-red-500 text-white rounded-xl font-bold hover:bg-red-600 shadow-lg shadow-red-200 transition">លុប</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>