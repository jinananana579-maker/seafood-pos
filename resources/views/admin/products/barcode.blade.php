<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>Barcode - {{ $product->name }}</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 20px; }
        .sticker { 
            border: 1px dashed #333; 
            padding: 10px; 
            display: inline-flex; /* ប្រើ Flex ដើម្បីកណ្តាល */
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 200px; 
            height: 120px; /* កំណត់កម្ពស់ឱ្យសម */
        }
        @media print { 
            .no-print { display: none; } 
            .sticker { border: none; } 
        }
    </style>
</head>
<body onload="window.print()">
    
    <button onclick="window.print()" class="no-print" style="margin-bottom:20px; padding:10px 20px; cursor:pointer;">Print Now</button><br>
    
    <div class="sticker">
        <p style="margin:0; font-size:12px; font-weight:bold; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; max-width: 100%;">
            {{ $product->name }}
        </p>
        
        <div style="margin: 5px 0;">{!! $barcode !!}</div>
        
        <p style="margin:0; font-size:10px; letter-spacing:2px;">{{ $code }}</p>
        
        <p style="margin:0; font-size:16px; font-weight:bold;">${{ number_format($product->price, 2) }}</p>
    </div>

</body>
</html>