<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // សម្រាប់លុបរូបភាពចាស់
use Picqer\Barcode\BarcodeGeneratorHTML; // សម្រាប់បង្កើត Barcode

class ProductController extends Controller
{
    // 1. បង្ហាញតារាងទំនិញ
    public function index()
    {
        // បង្ហាញទំនិញថ្មីៗបំផុតមុនគេ (១០ មុខក្នុង ១ ទំព័រ)
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    // 2. រក្សាទុកទំនិញថ្មី (Store)
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload Image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // បង្កើត Folder ក្នុង public/uploads/products បើមិនទាន់មាន
            $path = public_path('uploads/products');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            
            $file->move($path, $filename);
            $imagePath = '/uploads/products/' . $filename;
        }

        // Create Product
        Product::create([
            'name' => $request->name,
            'barcode' => $request->barcode,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock ?? 0, // បើអត់ដាក់ ចាត់ទុកថា 0
            'unit' => $request->unit ?? 'pcs', // 🔥 បើអត់ដាក់ ចាត់ទុកថា 'pcs'
            'image' => $imagePath,
        ]);

        return back()->with('success', 'ទំនិញត្រូវបានបន្ថែមដោយជោគជ័យ!');
    }

    // 3. កែប្រែទំនិញ (Update)
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['unit'] = $request->unit ?? 'pcs'; // ការពារ Error Unit

        // Handle Image Update
        if ($request->hasFile('image')) {
            // លុបរូបចាស់ចោលសិន (ដើម្បីកុំឱ្យពេញ Server)
            if ($product->image && File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }

            // Upload រូបថ្មី
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $filename);
            
            $data['image'] = '/uploads/products/' . $filename;
        }

        $product->update($data);

        return back()->with('success', 'កែប្រែទំនិញជោគជ័យ!');
    }

    // 4. លុបទំនិញ (Destroy) - ការពារ Error ពេលទំនិញធ្លាប់លក់
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            // លុបរូបភាពផងដែរ
            if ($product->image && File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }

            $product->delete();
            return back()->with('success', 'ទំនិញត្រូវបានលុបជោគជ័យ!');

        } catch (\Illuminate\Database\QueryException $e) {
            // Error Code 23000 គឺបញ្ហា Foreign Key (ទំនិញជាប់ក្នុងវិក្កយបត្រ)
            if ($e->getCode() == "23000") {
                return back()->with('error', 'បរាជ័យ! ទំនិញនេះធ្លាប់លក់ចេញហើយ មិនអាចលុបបានទេ។');
            }
            return back()->with('error', 'មានបញ្ហា: ' . $e->getMessage());
        }
    }

    // 5. បោះពុម្ព Barcode
    public function printBarcode($id)
    {
        $product = Product::findOrFail($id);
        
        // ប្រើ Barcode របស់ទំនិញ បើអត់មាន យក ID មកធ្វើ Barcode (000001)
        $code = $product->barcode;
        if (empty($code)) {
            $code = str_pad($product->id, 6, '0', STR_PAD_LEFT); 
        }

        $generator = new BarcodeGeneratorHTML();
        $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);

        return view('admin.products.barcode', compact('product', 'barcode', 'code'));
    }
}