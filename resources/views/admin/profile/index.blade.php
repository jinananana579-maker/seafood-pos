<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>ប្រវត្តិរូប (Profile)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>body { font-family: 'Kantumruy Pro', sans-serif; }</style>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold flex items-center gap-2">
                <i class="ph ph-user-circle text-blue-600"></i> ប្រវត្តិរូបរបស់ខ្ញុំ
            </h1>
            <a href="/admin/dashboard" class="text-gray-500 hover:text-blue-600 font-bold text-sm">
                <i class="ph ph-arrow-left"></i> ត្រឡប់ក្រោយ
            </a>
        </div>

        <div class="flex flex-col items-center mb-8">
            <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-4xl font-bold mb-4">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
            <p class="text-gray-500">{{ $user->email }}</p>
            <span class="mt-2 bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Admin Account</span>
        </div>

        <div class="border-t pt-6 space-y-4">
            <div class="flex justify-between border-b pb-3">
                <span class="text-gray-500">កាលបរិច្ឆេទចូលរួម:</span>
                <span class="font-bold text-gray-700">{{ $user->created_at->format('d M, Y') }}</span>
            </div>
            <div class="flex justify-between border-b pb-3">
                <span class="text-gray-500">តួនាទី:</span>
                <span class="font-bold text-gray-700">Administrator / Owner</span>
            </div>
        </div>

        <div class="mt-8 flex gap-3">
            <a href="{{ route('profile.edit') }}" class="flex-1 bg-blue-600 text-white text-center py-2.5 rounded-xl font-bold hover:bg-blue-700 transition">
                <i class="ph ph-pencil-simple"></i> កែប្រែព័ត៌មាន
            </a>
            <a href="{{ route('profile.settings') }}" class="flex-1 bg-gray-100 text-gray-700 text-center py-2.5 rounded-xl font-bold hover:bg-gray-200 transition">
                <i class="ph ph-gear"></i> ការកំណត់
            </a>
        </div>
    </div>
</body>
</html>