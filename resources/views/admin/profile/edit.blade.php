<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>កែប្រែគណនី (Edit Profile)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>body { font-family: 'Kantumruy Pro', sans-serif; }</style>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-xl mx-auto bg-white rounded-2xl shadow-sm p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-bold flex items-center gap-2">
                <i class="ph ph-note-pencil text-orange-500"></i> កែប្រែព័ត៌មានគណនី
            </h1>
            <a href="{{ route('profile.index') }}" class="text-gray-500 hover:text-black font-bold text-sm">
                បោះបង់
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4 text-sm font-bold flex items-center gap-2">
                <i class="ph ph-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">ឈ្មោះអ្នកប្រើប្រាស់</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">អ៊ីមែល (Email)</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div class="border-t pt-4 mt-4">
                <h3 class="text-sm font-bold text-gray-500 mb-3 uppercase">ប្តូរលេខសម្ងាត់ (ទុកទទេបើមិនប្តូរ)</h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">លេខសម្ងាត់ថ្មី</label>
                        <input type="password" name="password" class="w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">បញ្ជាក់លេខសម្ងាត់ថ្មី</label>
                        <input type="password" name="password_confirmation" class="w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 mt-4 transition shadow-lg shadow-blue-200">
                រក្សាទុកការកែប្រែ
            </button>
        </form>
    </div>
</body>
</html>