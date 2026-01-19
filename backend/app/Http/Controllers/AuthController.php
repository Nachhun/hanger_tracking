<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function telegramAuth(Request $request)
    {
        $initData = $request->input('initData');

        if (!$initData) {
            Log::error('TelegramAuth: No initData provided');
            return response()->json(['error' => 'No initData provided'], 400);
        }

        Log::info('TelegramAuth: Received initData', ['initData' => $initData]);

        parse_str($initData, $data);
        $hash = $data['hash'] ?? null;

        Log::info('TelegramAuth: Parsed data', ['data' => $data, 'hash' => $hash]);

        if (!$hash) {
            Log::warning('TelegramAuth: Hash missing from parsed data');
            return response()->json(['error' => 'Hash missing'], 400);
        }

        unset($data['hash']);

        // Remove signature if present (it breaks the hash check if included in data-check-string)
        if (isset($data['signature'])) {
            unset($data['signature']);
        }

        // Data-check-string is a chain of all received fields, sorted alphabetically
        ksort($data);
        $dataCheckString = '';
        foreach ($data as $key => $value) {
            $dataCheckString .= ($dataCheckString ? "\n" : "") . $key . '=' . $value;
        }

        // Secret key calculation
        $botToken = trim(config('services.telegram.bot_token'));
        Log::info('TelegramAuth: Checking Bot Token', ['token_exists' => !empty($botToken)]);

        if (!$botToken) {
            Log::error('Telegram Bot Token not configured in config/services.php');
            return response()->json(['error' => 'Server configuration error'], 500);
        }

        $secretKey = hash_hmac('sha256', $botToken, 'WebAppData', true);
        $generatedHash = bin2hex(hash_hmac('sha256', $dataCheckString, $secretKey, true));

        // Log for debugging
        Log::info('Auth Debug', [
            'dataCheckString' => $dataCheckString,
            'generatedHash' => $generatedHash,
            'receivedHash' => $hash,
            'match' => hash_equals($hash, $generatedHash)
        ]);

        // Verify hash
        if (!hash_equals($hash, $generatedHash)) {
            Log::warning('TelegramAuth: Integrity failed');
            return response()->json(['error' => 'Data integrity check failed'], 403);
        }

        // Check authentication date (prevent replay attacks, allow 24h)
        $authDate = $data['auth_date'] ?? 0;
        if (time() - $authDate > 172800) {
            return response()->json(['error' => 'Data is outdated'], 403);
        }

        $userJson = $data['user'] ?? null;

        if (!$userJson) {
            return response()->json(['error' => 'User data missing'], 400);
        }

        $telegramUser = json_decode($userJson, true);
        $telegramId = $telegramUser['id'];

        $user = User::where('telegram_id', $telegramId)->first();

        if (!$user) {
            return response()->json(['error' => 'Account not activated. Please contact admin.'], 403);
        }

        if (!$user->is_active) {
            return response()->json(['error' => 'Account is inactive'], 403);
        }

        $token = $user->createToken('telegram-auth')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function loginByTelegramId(Request $request)
    {
        $request->validate(['telegram_id' => 'required|numeric']);

        $user = User::where('telegram_id', $request->telegram_id)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if (!$user->is_active) {
            return response()->json(['error' => 'Account is inactive'], 403);
        }

        $token = $user->createToken('manual-auth')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }
}
