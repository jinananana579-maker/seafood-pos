<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;

class ProfileController extends Controller
{
    // 1. Profile Page (បង្ហាញព័ត៌មានផ្ទាល់ខ្លួន)
    public function index()
    {
        $user = Auth::user();
        return view('admin.profile.index', compact('user'));
    }

    // 2. Edit Profile Page (ទំព័រកែប្រែគណនី)
    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    // 3. Update Profile Action (រក្សាទុកការកែប្រែគណនី)
    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'ព័ត៌មានត្រូវបានកែប្រែជោគជ័យ!');
    }

    // 4. Settings Page (ទំព័រការកំណត់ហាង)
    public function settings()
    {
        $setting = Setting::first();
        return view('admin.profile.settings', compact('setting'));
    }

    // 5. Update Settings Action (រក្សាទុក Logo, QR និងព័ត៌មានហាង)
    public function updateSettings(Request $request)
    {
        $request->validate([
            'shop_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'footer_text' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'qr_code' => 'nullable|image|max:2048',
        ]);

        // ទាញយក Setting ចាស់ ឬ បង្កើតថ្មីបើមិនទាន់មាន
        $setting = Setting::firstOrNew(); 
        
        // Save Text Fields
        if ($request->has('shop_name')) $setting->shop_name = $request->shop_name;
        if ($request->has('phone')) $setting->phone = $request->phone;
        if ($request->has('address')) $setting->address = $request->address;
        if ($request->has('footer_text')) $setting->footer_text = $request->footer_text;

        // កំណត់ Path សម្រាប់រក្សាទុក
        $path = public_path('uploads/settings');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        // 🔥 Save QR Code (ដាក់ឈ្មោះថេរ shop_qr.png)
        if ($request->hasFile('qr_code')) {
            $file = $request->file('qr_code');
            // Overwrite ឈ្មោះចាស់
            $file->move($path, 'shop_qr.png'); 
        }

        // 🔥 Save Logo (ដាក់ឈ្មោះថេរ shop_logo.png)
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            // Overwrite ឈ្មោះចាស់
            $file->move($path, 'shop_logo.png');
        }

        $setting->save();

        return back()->with('success', 'ការកំណត់ត្រូវបានរក្សាទុកជោគជ័យ!');
    }
}