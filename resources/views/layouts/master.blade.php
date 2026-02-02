<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ប្រព័ន្ធលក់ (POS)</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        body { font-family: 'Kantumruy Pro', sans-serif; }
        /* Style សម្រាប់ Scrollbar */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 4px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 antialiased">
    
    <nav class="bg-white shadow-sm border-b h-16 flex items-center px-6 justify-between fixed w-full top-0 z-50">
        <div class="flex items-center gap-2">
            <div class="bg-blue-600 text-white p-2 rounded-lg">
                <i class="ph ph-storefront text-2xl"></i>
            </div>
            <h1 class="text-xl font-bold text-blue-900">Seafood & Beer POS</h1>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm font-bold text-gray-500">{{ now()->format('d/m/Y H:i') }}</span>
            <div class="flex items-center gap-2 bg-gray-100 px-3 py-1.5 rounded-full">
                <i class="ph ph-user-circle text-xl text-gray-600"></i>
                <span class="text-sm font-bold">Admin/អ្នកលក់</span>
            </div>
        </div>
    </nav>

    <main class="pt-16 h-screen overflow-hidden">
        @yield('content')
    </main>

</body>
</html>