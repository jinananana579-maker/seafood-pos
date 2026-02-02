<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    public static function send($message)
    {
        try {
            // 🔥 ដាក់លេខកូដបងផ្ទាល់នៅទីនេះ
            $token = '8223390982:AAGy_IHN9ZHTvZYZh04jm54IfvAEzB69hI4';
            $chatId = '1660281294';

            $url = "https://api.telegram.org/bot{$token}/sendMessage";

            Http::post($url, [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML'
            ]);
        } catch (\Exception $e) {
            // បើមានបញ្ហាអ៊ីនធឺណិត ឬផ្ញើមិនចេញ កុំឱ្យវា Error ដល់ប្រព័ន្ធ
        }
    }
}