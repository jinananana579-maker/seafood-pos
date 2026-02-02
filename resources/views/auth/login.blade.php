<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>Login - Sokha POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;600&display=swap" rel="stylesheet">
    <style>body { font-family: 'Kantumruy Pro', sans-serif; }</style>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-xl shadow-lg w-96">
        <h2 class="text-2xl font-bold text-center text-blue-800 mb-6">🔐 ចូលប្រព័ន្ធ</h2>
        
        @if($errors->any())
            <div class="bg-red-100 text-red-600 p-2 rounded mb-4 text-sm text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/login" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700">អ៊ីមែល</label>
                <input type="email" name="email" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500 outline-none" placeholder="admin@gmail.com" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700">លេខសម្ងាត់</label>
                <input type="password" name="password" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500 outline-none" placeholder="********" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 rounded hover:bg-blue-700 transition">ចូលកម្មវិធី</button>
        </form>
        <a href="/" class="block text-center mt-4 text-sm text-gray-500 hover:underline">ត្រឡប់ទៅ POS វិញ</a>
    </div>
</body>
</html>