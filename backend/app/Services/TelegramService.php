<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TelegramService
{
    protected $botToken;
    protected $baseUrl;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token');
        $this->baseUrl = "https://api.telegram.org/bot{$this->botToken}";
    }

    public function notifyNewEntry($entry)
    {
        $user = $entry->user;
        $provinceName = $entry->province ? $entry->province->name : 'N/A';
        $brandName = $entry->brand ? $entry->brand->name : 'N/A';

        $message = "🆕 New Entry Submitted\n\n";
        $message .= "👤 Salesman: {$user->name}\n";
        $message .= "🏷️ Hanger ID: {$entry->hanger_id}\n";
        $message .= "🏢 Outlet: {$entry->outlet_name}\n";
        $message .= "📍 Province: {$provinceName}\n";
        $message .= "🏭 Brand: {$brandName}\n";

        if ($entry->latitude && $entry->longitude) {
            $mapUrl = "https://www.google.com/maps?q={$entry->latitude},{$entry->longitude}";
            $message .= "🗺️ Location: {$mapUrl}\n";
        }

        if ($entry->notes) {
            $message .= "📝 Notes: {$entry->notes}\n";
        }

        // Determine if we have a valid photo
        $photoFullPath = null;
        if ($entry->photo_path) {
            $path = storage_path('app/public/' . $entry->photo_path);
            if (file_exists($path)) {
                $photoFullPath = $path;
            }
        }

        // Collect all recipients (Admins + Group)
        $recipients = [];

        // Add Admins
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            if ($admin->telegram_id) {
                $recipients[] = $admin->telegram_id;
            }
        }

        // Add Group
        $groupChatId = env('TELEGRAM_GROUP_CHAT_ID');
        if ($groupChatId) {
            $recipients[] = $groupChatId;
        }

        // Remove duplicates
        $recipients = array_unique($recipients);

        // Send to all
        foreach ($recipients as $chatId) {
            if ($photoFullPath) {
                // Send Photo with Caption (Priority)
                $this->sendPhoto($chatId, $photoFullPath, $message);
            } else {
                // Fallback to text message if no photo
                $this->sendMessage($chatId, $message);
            }
        }
    }

    public function sendMessage($chatId, $text)
    {
        try {
            $response = Http::post("{$this->baseUrl}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true
            ]);

            if (!$response->successful()) {
                Log::error('Telegram sendMessage failed', ['response' => $response->body()]);
            }

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Telegram sendMessage exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function sendPhoto($chatId, $photoPath, $caption = null)
    {
        try {
            $fileName = basename($photoPath);

            $response = Http::attach(
                'photo',
                file_get_contents($photoPath),
                $fileName
            )->post("{$this->baseUrl}/sendPhoto", [
                        'chat_id' => $chatId,
                        'caption' => $caption,
                        'parse_mode' => 'HTML',
                    ]);

            if (!$response->successful()) {
                Log::error('Telegram sendPhoto failed', ['response' => $response->body()]);
            }

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Telegram sendPhoto exception', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
