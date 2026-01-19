<?php

namespace App\Http\Controllers;

use App\Models\HangerEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HangerEntryController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->hangerEntries()
            ->with('province', 'brand')
            ->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('outlet_name', 'like', "%{$search}%")
                    ->orWhere('hanger_id', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        $paginated = $query->paginate(10);

        $transformedData = collect($paginated->items())->map(function ($entry) {
            return [
                'id' => $entry->id,
                'outlet_name' => $entry->outlet_name,
                'province' => optional($entry->province)->name ?? 'N/A',
                'brand' => optional($entry->brand)->name ?? 'N/A',
                'brand_id' => $entry->brand_id,
                'province_id' => $entry->province_id,
                'hanger_id' => $entry->hanger_id,
                'latitude' => $entry->latitude,
                'longitude' => $entry->longitude,
                'notes' => $entry->notes,
                'photo_url' => $entry->photo_path ? Storage::url($entry->photo_path) : null,
                'created_at' => $entry->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'data' => $transformedData,
            'current_page' => $paginated->currentPage(),
            'last_page' => $paginated->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'outlet_name' => 'required|string',
            'province_id' => 'required|exists:provinces,id',
            'brand_id' => 'required|exists:brands,id',
            'hanger_id' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|max:10240',
        ]);

        $validated['user_id'] = $request->user()->id;

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('photos', 'public');
        }

        $entry = HangerEntry::create($validated);
        $entry->load(['province', 'brand', 'user']);

        // Send Telegram notification
        $telegramService = app(\App\Services\TelegramService::class);
        $telegramService->notifyNewEntry($entry);

        return response()->json($entry, 201);
    }

    public function update(Request $request, $id)
    {
        $entry = $request->user()->hangerEntries()->findOrFail($id);

        $validated = $request->validate([
            'outlet_name' => 'sometimes|required|string',
            'province_id' => 'sometimes|required|exists:provinces,id',
            'brand_id' => 'sometimes|required|exists:brands,id',
            'hanger_id' => 'sometimes|required|string',
            'notes' => 'nullable|string',
        ]);

        $entry->update($validated);

        return response()->json($entry);
    }

    public function destroy(Request $request, $id)
    {
        $entry = $request->user()->hangerEntries()->findOrFail($id);

        if ($entry->photo_path) {
            Storage::disk('public')->delete($entry->photo_path);
        }

        $entry->delete();

        return response()->json(['message' => 'Entry deleted successfully']);
    }

    public function statsToday(Request $request)
    {
        $user = $request->user();
        $today = now()->startOfDay();

        $entriesToday = $user->hangerEntries()
            ->whereDate('created_at', $today)
            ->count();

        $totalHangers = $user->hangerEntries()->count();

        return response()->json([
            'entries_today' => $entriesToday,
            'total_hangers' => $totalHangers
        ]);
    }

    public function dashboardStats(Request $request)
    {
        $user = $request->user();
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfToday = $now->copy()->startOfDay();

        // 1. Basic Stats
        $stats = [
            'today' => $user->hangerEntries()->where('created_at', '>=', $startOfToday)->count(),
            'this_month' => $user->hangerEntries()->where('created_at', '>=', $startOfMonth)->count(),
            'total' => $user->hangerEntries()->count(),
        ];

        // 2. Weekly Trend (Last 7 Days)
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $count = $user->hangerEntries()
                ->whereDate('created_at', $date)
                ->count();

            $chartData[] = [
                'date' => $date->format('D, d'), // Mon, 01
                'count' => $count
            ];
        }

        // 3. Top Brands
        $topBrands = $user->hangerEntries()
            ->select('brand_id', \DB::raw('count(*) as total'))
            ->whereNotNull('brand_id')
            ->with('brand')
            ->groupBy('brand_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($item) use ($stats) {
                return [
                    'name' => $item->brand ? $item->brand->name : 'Unknown',
                    'count' => $item->total,
                    'percentage' => $stats['total'] > 0 ? round(($item->total / $stats['total']) * 100) : 0
                ];
            });

        return response()->json([
            'stats' => $stats,
            'chart_data' => $chartData,
            'top_brands' => $topBrands
        ]);
    }

    public function exportCSV(Request $request)
    {
        // Fetch entries for the authenticated user
        $query = auth()->user()->hangerEntries()->with('province', 'brand')->latest();

        // Apply filters
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $entries = $query->get();

        // Prepare all province names upfront
        $provinces = \App\Models\Province::pluck('name', 'id')->toArray();

        // Prepare all brand names upfront
        $brands = \App\Models\Brand::pluck('name', 'id')->toArray();

        // CSV Headers
        $csvData = "Salesman,Outlet Name,Province,Brand,Hanger ID,Notes,Created At\n";

        foreach ($entries as $entry) {
            $provinceName = isset($provinces[$entry->province_id]) ? $provinces[$entry->province_id] : 'N/A';
            $brandName = isset($brands[$entry->brand_id]) ? $brands[$entry->brand_id] : 'N/A';

            $csvData .= '"' . auth()->user()->name . '","' .
                $entry->outlet_name . '","' .
                $provinceName . '","' .
                $brandName . '","' .
                $entry->hanger_id . '","' .
                ($entry->notes ?? '') . '",' .
                ' ' . $entry->created_at->format('Y-m-d H:i') . "\n";
        }

        // Save CSV to temporary file
        $filename = 'my-entries-' . now()->format('Y-m-d') . '.csv';
        $tempPath = storage_path('app/temp/' . $filename);

        // Create temp directory if it doesn't exist
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        file_put_contents($tempPath, $csvData);

        // Send to Telegram
        try {
            $telegramId = auth()->user()->telegram_id;
            $botToken = env('TELEGRAM_BOT_TOKEN');

            $url = "https://api.telegram.org/bot{$botToken}/sendDocument";

            $postFields = [
                'chat_id' => $telegramId,
                'document' => new \CURLFile($tempPath, 'text/csv', $filename),
                'caption' => "📊 Your Sales Export\n\nTotal Entries: " . $entries->count() . "\nGenerated: " . now()->format('Y-m-d H:i')
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($ch);
            curl_close($ch);

            // Delete temp file
            unlink($tempPath);

            return response()->json([
                'message' => 'CSV file sent to your Telegram!',
                'success' => true
            ]);

        } catch (\Exception $e) {
            // If Telegram fails, delete temp and return error
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }

            return response()->json([
                'message' => 'Failed to send to Telegram',
                'success' => false
            ], 500);
        }
    }
}
