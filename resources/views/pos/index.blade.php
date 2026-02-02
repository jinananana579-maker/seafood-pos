@extends('layouts.master')

@section('content')
<div class="flex h-full" x-data="posSystem()">
    
    <div class="w-3/4 flex flex-col h-full">
        
        <div class="p-4 bg-white border-b flex gap-3 overflow-x-auto shadow-sm">
            <button @click="filter = 'all'" :class="filter === 'all' ? 'bg-slate-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-6 py-2 rounded-xl font-bold transition flex items-center gap-2">
                <i class="ph ph-squares-four"></i> ទាំងអស់
            </button>
            <button @click="filter = 'seafood'" :class="filter === 'seafood' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-6 py-2 rounded-xl font-bold transition flex items-center gap-2">
                <i class="ph ph-fish"></i> គ្រឿងសមុទ្រ
            </button>
            <button @click="filter = 'beer'" :class="filter === 'beer' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-6 py-2 rounded-xl font-bold transition flex items-center gap-2">
                <i class="ph ph-beer-bottle"></i> ស្រាបៀរ
            </button>
            <button @click="filter = 'vegetable'" :class="filter === 'vegetable' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-6 py-2 rounded-xl font-bold transition flex items-center gap-2">
                <i class="ph ph-carrot"></i> បន្លែ
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-4 bg-slate-50">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 pb-20">
                @foreach($products as $product)
                <div 
                    x-show="filter === 'all' || filter === '{{ $product->category }}'"
                    @click="addToCart({{ $product }})"
                    class="group bg-white rounded-2xl p-3 shadow-sm hover:shadow-xl hover:ring-2 hover:ring-blue-500 transition cursor-pointer flex flex-col h-full border border-gray-100"
                >
                    <div class="relative h-32 mb-3 overflow-hidden rounded-xl bg-gray-100">
                        <img src="{{ $product->image_url }}" 
                             onerror="this.src='https://placehold.co/400x300?text=No+Image'"
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        
                        <span class="absolute top-2 right-2 bg-white/90 backdrop-blur text-xs font-bold px-2 py-1 rounded shadow-sm">
                            {{ $product->unit }}
                        </span>
                    </div>
                    <div class="mt-auto">
                        <h3 class="font-bold text-gray-700 line-clamp-1">{{ $product->name }}</h3>
                        <div class="flex justify-between items-end mt-1">
                            <span class="text-lg font-extrabold text-blue-600">${{ $product->price }}</span>
                            <i class="ph ph-plus-circle text-2xl text-blue-500 group-hover:text-blue-700 transition"></i>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="w-1/4 bg-white border-l shadow-2xl flex flex-col h-full z-10 relative">
        <div class="p-5 border-b bg-gray-50">
            <h2 class="text-xl font-extrabold text-gray-800 flex items-center gap-2">
                <i class="ph ph-shopping-cart"></i> ការកម្ម៉ង់របស់អ្នក
            </h2>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-4">
            <template x-if="cart.length === 0">
                <div class="flex flex-col items-center justify-center h-full text-gray-400 space-y-3 opacity-60">
                    <i class="ph ph-basket text-6xl"></i>
                    <p>មិនទាន់មានទំនិញ</p>
                </div>
            </template>

            <template x-for="(item, index) in cart" :key="item.id">
                <div class="flex gap-3 items-center bg-white border p-3 rounded-xl shadow-sm relative group hover:border-blue-300 transition">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                        <img :src="item.image_url" 
                             onerror="this.src='https://placehold.co/100?text=No+Image'"
                             class="w-full h-full object-cover">
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-gray-800 text-sm truncate" x-text="item.name"></h4>
                        <div class="text-xs text-gray-500 flex justify-between mt-1">
                            <span>x <span x-text="item.qty"></span></span>
                            <span class="font-bold text-blue-600" x-text="'$' + (item.price * item.qty).toFixed(2)"></span>
                        </div>
                    </div>

                    <div class="flex flex-col items-center gap-1">
                        <button @click="updateQty(index, 1)" class="w-6 h-6 rounded bg-green-100 text-green-600 flex items-center justify-center hover:bg-green-200">
                            <i class="ph ph-plus text-xs"></i>
                        </button>
                        <button @click="updateQty(index, -1)" class="w-6 h-6 rounded bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-200">
                            <i class="ph ph-minus text-xs"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <div class="p-5 bg-white border-t space-y-3 shadow-[0_-5px_20px_rgba(0,0,0,0.05)]">
            <div class="flex justify-between text-gray-500 text-sm">
                <span>សរុប (Subtotal)</span>
                <span class="font-bold text-gray-800">$<span x-text="totalPrice()"></span></span>
            </div>
            
            <div class="bg-gray-100 p-3 rounded-xl flex justify-between items-center mb-2">
                <span class="font-bold text-gray-700">សរុបត្រូវបង់</span>
                <span class="text-2xl font-extrabold text-blue-700">$<span x-text="totalPrice()"></span></span>
            </div>

            <button @click="openPaymentModal()" class="w-full py-4 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-lg shadow-lg active:scale-95 transition flex justify-center items-center gap-2">
                <i class="ph ph-printer"></i> គិតលុយ & ព្រីន
            </button>
        </div>
    </div>

    <div x-show="showPaymentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md p-8 relative animate-in zoom-in-95 duration-200">
            <button @click="showPaymentModal = false" class="absolute top-6 right-6 text-slate-400 hover:text-red-500 transition"><i class="ph ph-x text-2xl"></i></button>
            
            <div class="text-center mb-6">
                <h2 class="text-2xl font-black text-slate-800">ការបង់ប្រាក់</h2>
                <p class="text-slate-500 text-sm">សូមស្កេន QR ខាងក្រោមដើម្បីបង់ប្រាក់</p>
                <div class="mt-4 text-4xl font-black text-blue-600">$<span x-text="totalPrice()"></span></div>
            </div>

            <div class="flex justify-center mb-8">
                <div class="p-2 bg-white rounded-2xl shadow-lg border border-slate-100">
                    <img :src="'{{ asset('uploads/settings/shop_qr.png') }}?v=' + qrTimestamp" 
                         class="w-48 h-48 object-cover mx-auto rounded-xl"
                         onerror="this.src='https://placehold.co/200x200?text=No+QR+Set'">
                </div>
            </div>

            <button @click="submitOrder()" class="w-full py-4 bg-blue-600 text-white rounded-xl font-bold text-lg hover:bg-blue-700 shadow-lg shadow-blue-200 transition flex items-center justify-center gap-2">
                <i class="ph ph-check-circle text-xl"></i> ទទួលបានលុយ (Confirm)
            </button>
        </div>
    </div>

</div>

<script>
    function posSystem() {
        return {
            filter: 'all',
            cart: [],
            showPaymentModal: false,
            qrTimestamp: new Date().getTime(), // 1. Initial Timestamp

            addToCart(product) {
                let existing = this.cart.find(i => i.id === product.id);
                if (existing) {
                    existing.qty++;
                } else {
                    this.cart.push({ ...product, qty: 1 });
                }
            },
            updateQty(index, val) {
                if (this.cart[index].qty + val <= 0) {
                    this.cart.splice(index, 1);
                } else {
                    this.cart[index].qty += val;
                }
            },
            totalPrice() {
                return this.cart.reduce((total, item) => total + (item.price * item.qty), 0).toFixed(2);
            },
            
            // Open Modal
            openPaymentModal() {
                if (this.cart.length === 0) return alert('សូមជ្រើសរើសទំនិញ!');
                
                // 🔥 2. Update Timestamp when modal opens to force refresh image
                this.qrTimestamp = new Date().getTime();
                
                this.showPaymentModal = true;
            },

            // Submit Order
            submitOrder() {
                fetch('/pos/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        cart: this.cart,
                        total_price: this.totalPrice(),
                        received_amount: this.totalPrice()
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        alert('ការលក់ជោគជ័យ!');
                        this.cart = [];
                        this.showPaymentModal = false;
                    } else {
                        alert('មានបញ្ហា: ' + data.message);
                    }
                });
            }
        }
    }
</script>
@endsection