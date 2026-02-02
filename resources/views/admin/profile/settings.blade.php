<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>ការកំណត់ (Settings)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>body { font-family: 'Kantumruy Pro', sans-serif; }</style>
</head>
<body class="bg-gray-100 p-6">
    
    <div class="max-w-5xl mx-auto" x-data="{ activeTab: 'shop' }"> 
        
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2 text-gray-500">
                <a href="/admin/dashboard" class="hover:text-blue-600 flex items-center gap-1">
                    <i class="ph ph-squares-four"></i> Dashboard
                </a>
                <span>/</span>
                <span class="font-bold text-gray-800">ការកំណត់ប្រព័ន្ធ</span>
            </div>

            @if(session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 animate-bounce">
                    <i class="ph ph-check-circle text-lg"></i> {{ session('success') }}
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            
            <div class="bg-white p-4 rounded-2xl shadow-sm h-fit">
                <h2 class="text-lg font-bold mb-4 px-2 text-gray-700">ម៉ឺនុយការកំណត់</h2>
                <ul class="space-y-1">
                    <li>
                        <button @click="activeTab = 'shop'" :class="activeTab === 'shop' ? 'bg-blue-50 text-blue-700 font-bold' : 'text-gray-600 hover:bg-gray-50'" class="w-full text-left px-4 py-3 rounded-xl transition flex items-center gap-3">
                            <i class="ph ph-storefront text-lg"></i> ព័ត៌មានហាង
                        </button>
                    </li>
                    <li>
                        <button @click="activeTab = 'receipt'" :class="activeTab === 'receipt' ? 'bg-blue-50 text-blue-700 font-bold' : 'text-gray-600 hover:bg-gray-50'" class="w-full text-left px-4 py-3 rounded-xl transition flex items-center gap-3">
                            <i class="ph ph-receipt text-lg"></i> ការកំណត់វិក្កយបត្រ
                        </button>
                    </li>
                    <li>
                        <a href="/admin/users" class="w-full text-left px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 transition flex items-center gap-3">
                            <i class="ph ph-users text-lg"></i> បុគ្គលិក & សិទ្ធិ
                        </a>
                    </li>
                    <li>
                        <button @click="activeTab = 'backup'" :class="activeTab === 'backup' ? 'bg-blue-50 text-blue-700 font-bold' : 'text-gray-600 hover:bg-gray-50'" class="w-full text-left px-4 py-3 rounded-xl transition flex items-center gap-3">
                            <i class="ph ph-database text-lg"></i> បម្រុងទុកទិន្នន័យ
                        </button>
                    </li>
                </ul>
            </div>

            <div class="md:col-span-3 bg-white p-8 rounded-2xl shadow-sm min-h-[500px]">
                
                <div x-show="activeTab === 'shop'" x-transition.opacity>
                    <h2 class="text-xl font-bold mb-6 flex items-center gap-2 pb-4 border-b">
                        <i class="ph ph-storefront text-blue-600"></i> ព័ត៌មានហាង (Shop Info)
                    </h2>
                    
                    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">ឈ្មោះហាង</label>
                                <input type="text" name="shop_name" value="{{ $setting->shop_name ?? 'KH-SHOP' }}" class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">លេខទូរស័ព្ទ</label>
                                <input type="text" name="phone" value="{{ $setting->phone ?? '' }}" class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">អាស័យដ្ឋាន</label>
                            <textarea name="address" rows="3" class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">{{ $setting->address ?? '' }}</textarea>
                        </div>

                        <button type="submit" class="bg-blue-600 text-white font-bold py-3 px-8 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                            រក្សាទុកព័ត៌មាន
                        </button>
                    </form>
                </div>

                <div x-show="activeTab === 'receipt'" x-transition.opacity style="display: none;">
                    <h2 class="text-xl font-bold mb-6 flex items-center gap-2 pb-4 border-b">
                        <i class="ph ph-receipt text-purple-600"></i> ការកំណត់វិក្កយបត្រ
                    </h2>
                    
                    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="border rounded-xl p-4 bg-gray-50">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Logo ហាង</label>
                                <div class="flex items-center gap-4">
                                    <div class="w-24 h-24 bg-white border rounded-lg flex items-center justify-center p-2">
                                        <img src="{{ asset('uploads/settings/payment_logo.png') }}?v={{ time() }}" class="max-w-full max-h-full object-contain" onerror="this.src='https://via.placeholder.com/100?text=No+Logo'">
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" name="logo" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100"/>
                                        <p class="text-xs text-gray-500 mt-1">ណែនាំ៖ រូប PNG ផ្ទៃថ្លា</p>
                                    </div>
                                </div>
                            </div>

                            <div class="border rounded-xl p-4 bg-gray-50">
                                <label class="block text-sm font-bold text-gray-700 mb-2">QR Code ធនាគារ (ABA/KHQR)</label>
                                <div class="flex items-center gap-4">
                                    <div class="w-24 h-24 bg-white border rounded-lg flex items-center justify-center p-2">
                                        <img src="{{ asset('uploads/settings/payment_qr.png') }}?v={{ time() }}" class="max-w-full max-h-full object-contain" onerror="this.src='https://via.placeholder.com/100?text=No+QR'">
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" name="qr_code" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                                        <p class="text-xs text-gray-500 mt-1">ដាក់ QR ABA របស់អ្នក</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">សារជូនពរខាងក្រោម (Footer Text)</label>
                            <input type="text" name="footer_text" value="{{ $setting->footer_text ?? 'សូមអរគុណ! សូមអញ្ជើញមកម្តងទៀត។' }}" class="w-full border border-gray-300 p-3 rounded-xl outline-none focus:ring-2 focus:ring-purple-500">
                        </div>

                        <button type="submit" class="bg-purple-600 text-white font-bold py-3 px-8 rounded-xl hover:bg-purple-700 transition shadow-lg shadow-purple-200">
                            រក្សាទុកការផ្លាស់ប្តូរ
                        </button>
                    </form>
                </div>

                <div x-show="activeTab === 'backup'" x-transition.opacity style="display: none;">
                    <h2 class="text-xl font-bold mb-6 flex items-center gap-2 pb-4 border-b">
                        <i class="ph ph-database text-green-600"></i> បម្រុងទុកទិន្នន័យ (Backup)
                    </h2>
                    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mb-6">
                        <h3 class="font-bold text-blue-800 text-lg mb-2">តើត្រូវ Backup ដោយរបៀបណា?</h3>
                        <ol class="list-decimal list-inside space-y-2 text-gray-700 bg-white p-4 rounded-xl shadow-sm">
                            <li>ចូលទៅកាន់ <b>phpMyAdmin</b></li>
                            <li>ចុចលើ Database ឈ្មោះ <b>seafood_pos</b></li>
                            <li>ចុចលើ Tab <b>Export</b> -> <b>Go</b></li>
                        </ol>
                    </div>
                    <a href="http://localhost/phpmyadmin" target="_blank" class="inline-flex items-center gap-2 bg-green-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-green-700 transition shadow-lg">
                        <i class="ph ph-arrow-square-out text-xl"></i> ទៅកាន់ phpMyAdmin
                    </a>
                </div>

            </div>
        </div>
    </div>
</body>
</html>