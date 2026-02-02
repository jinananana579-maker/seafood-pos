<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>Admin - គ្រប់គ្រងទំនិញ</title>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;600&display=swap" rel="stylesheet">
    <style>body { font-family: 'Kantumruy Pro', sans-serif; }</style>
</head>
<body class="bg-gray-100 p-6">

    <div class="max-w-7xl mx-auto flex gap-6">
        
        <div class="w-1/3 bg-white p-6 rounded-xl shadow-md h-fit sticky top-6">
            <h2 class="text-xl font-bold mb-4 text-blue-800">➕ បញ្ចូលទំនិញថ្មី</h2>

            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">{{ session('success') }}</div>
            @endif

            <form action="/admin/products" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                
                <div>
                    <label class="text-sm font-bold text-gray-600">Barcode (ស្កេនដាក់ទីនេះ)</label>
                    <div class="flex gap-2">
                        <input type="text" name="barcode" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500 bg-yellow-50 font-bold text-blue-800" placeholder="ស្កេន ឬ វាយលេខ..." autofocus>
                        <button type="button" class="bg-gray-200 p-2 rounded text-gray-600"><i class="ph ph-barcode text-xl"></i></button>
                    </div>
                </div>

                <div>
                    <label class="text-sm font-bold text-gray-600">ឈ្មោះទំនិញ</label>
                    <input type="text" name="name" required class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-sm font-bold text-gray-600">តម្លៃ ($)</label>
                        <input type="number" step="0.01" name="price" required class="w-full border p-2 rounded">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-gray-600">ស្តុក</label>
                        <input type="number" name="stock" value="100" class="w-full border p-2 rounded">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-sm font-bold text-gray-600">ខ្នាត (Unit)</label>
                        <select name="unit" class="w-full border p-2 rounded bg-white">
                            <option value="kg">គីឡូ (kg)</option>
                            <option value="can">កំប៉ុង (can)</option>
                            <option value="bottle">ដប (bottle)</option>
                            <option value="plate">ចាន (plate)</option>
                            <option value="box">កេស (box)</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-gray-600">ប្រភេទ</label>
                        <select name="category" class="w-full border p-2 rounded bg-white">
                            <option value="seafood">គ្រឿងសមុទ្រ</option>
                            <option value="beer">ស្រាបៀរ</option>
                            <option value="vegetable">បន្លែ</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="text-sm font-bold text-gray-600">រូបភាព</label>
                    <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <button type="submit" class="w-full bg-blue-700 text-white font-bold py-2 rounded hover:bg-blue-800 transition">រក្សាទុក</button>
            </form>

            <a href="/" class="block text-center mt-4 text-gray-500 underline text-sm">ត្រឡប់ទៅកន្លែងលក់ (POS)</a>
        </div>

        <div class="w-2/3">
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-bold mb-4 text-gray-800">📦 បញ្ជីទំនិញក្នុងស្តុក</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 text-sm uppercase">
                                <th class="p-3">រូប</th>
                                <th class="p-3">Barcode</th> <th class="p-3">ឈ្មោះ</th>
                                <th class="p-3">តម្លៃ</th>
                                <th class="p-3">ស្តុក</th>
                                <th class="p-3 text-right">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($products as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3">
                                    <img src="{{ $product->image ?? 'https://via.placeholder.com/50' }}" class="w-10 h-10 rounded object-cover border">
                                </td>
                                <td class="p-3 text-xs font-mono text-gray-500">{{ $product->barcode ?? '-' }}</td> <td class="p-3 font-bold text-gray-700">{{ $product->name }}</td>
                                <td class="p-3 text-blue-600 font-bold">${{ $product->price }}</td>
                                <td class="p-3">
                                    <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded">{{ $product->stock }} {{ $product->unit }}</span>
                                </td>
                                <td class="p-3 text-right">
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('តើអ្នកចង់លុបមែនទេ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-sm">លុប</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</body>
</html>