<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\HangerEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function users(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return response()->json(User::all());
    }

    public function createUser(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'telegram_id' => 'required|numeric|unique:users',
            'name' => 'required|string',
            'role' => 'required|in:admin,salesman',
        ]);

        $user = User::create([
            'telegram_id' => $request->telegram_id,
            'name' => $request->name,
            'role' => $request->role,
            'email' => $request->telegram_id . '@telegram.user',
            'password' => bcrypt('password'), // Should not be used
            'is_active' => true, // Set active by default
        ]);

        return response()->json($user, 201);
    }

    public function analytics(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $today = now()->startOfDay();
        $weekStart = now()->startOfWeek();

        // Get top performer (by entry count)
        $topPerformer = HangerEntry::select('user_id', DB::raw('count(*) as total'))
            ->with('user:id,name')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->first();

        // Get daily chart data for last 7 days
        $dailyChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $count = HangerEntry::whereDate('created_at', $date)->count();
            $dailyChart[] = [
                'date' => $date->toDateString(),
                'count' => $count,
            ];
        }

        // Get recent activity (last 10 entries from all users)
        $recentActivity = HangerEntry::with('user:id,name')
            ->latest()
            ->take(10)
            ->get();

        // Get top 5 salesmen (and admins) by entry count (Current Month)
        $topSalesmen = User::whereIn('role', ['salesman', 'admin'])
            ->where('is_active', true)
            ->withCount([
                'hangerEntries' => function ($query) {
                    $query->where('created_at', '>=', now()->startOfMonth());
                }
            ])
            ->orderByDesc('hanger_entries_count')
            ->take(5)
            ->get();

        // Get low 5 salesmen (and admins) by entry count (Current Month)
        $lowSalesmen = User::whereIn('role', ['salesman', 'admin'])
            ->where('is_active', true)
            ->withCount([
                'hangerEntries' => function ($query) {
                    $query->where('created_at', '>=', now()->startOfMonth());
                }
            ])
            ->orderBy('hanger_entries_count', 'asc')
            ->take(5)
            ->get();

        $maxCount = $topSalesmen->first()?->hanger_entries_count ?? 1;

        $formatSalesmanData = function ($users) use ($maxCount) {
            return $users->map(function ($user) use ($maxCount) {
                return [
                    'name' => $user->name,
                    'count' => $user->hanger_entries_count,
                    'percentage' => $maxCount > 0 ? round(($user->hanger_entries_count / $maxCount) * 100) : 0
                ];
            });
        };

        // Get top provinces by entry volume
        $provinceStats = HangerEntry::select('province_id', DB::raw('count(*) as total'))
            ->whereNotNull('province_id')
            ->with('province:id,name')
            ->groupBy('province_id')
            ->orderByDesc('total')
            ->take(8)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->province ? $item->province->name : 'Unknown',
                    'count' => $item->total
                ];
            });

        return response()->json([
            'hangers_today' => HangerEntry::whereDate('created_at', $today)->count(),
            'entries_today' => HangerEntry::whereDate('created_at', $today)->count(),
            'weekly_entries' => HangerEntry::where('created_at', '>=', $weekStart)->count(),
            'active_salesmen' => User::where('role', 'salesman')->where('is_active', true)->count(),
            'total_salesmen' => User::where('role', 'salesman')->count(),
            'top_performer' => $topPerformer,
            'daily_chart' => $dailyChart,
            'recent_activity' => $recentActivity,
            'top_salesmen' => $formatSalesmanData($topSalesmen),
            'low_salesmen' => $formatSalesmanData($lowSalesmen),
            'province_stats' => $provinceStats,
        ]);
    }

    public function getAllEntries(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $entries = HangerEntry::with(['user:id,name', 'province', 'brand'])
            ->latest()
            ->get()
            ->map(function ($entry) {
                $provinceName = $entry->getRawOriginal('province');
                if ($entry->relationLoaded('province') && $entry->getRelation('province')) {
                    $provinceName = $entry->getRelation('province')->name;
                }

                $brandName = null;
                if ($entry->relationLoaded('brand') && $entry->getRelation('brand')) {
                    $brandName = $entry->getRelation('brand')->name;
                }

                return [
                    'id' => $entry->id,
                    'user' => $entry->user,
                    'outlet_name' => $entry->outlet_name,
                    'province' => $provinceName,
                    'brand' => $brandName,
                    'hanger_id' => $entry->hanger_id,
                    'latitude' => $entry->latitude,
                    'longitude' => $entry->longitude,
                    'notes' => $entry->notes,
                    'photo_url' => $entry->photo_path ? '/storage/' . $entry->photo_path : null,
                    'created_at' => $entry->created_at,
                    'updated_at' => $entry->updated_at,
                ];
            });

        return response()->json($entries);
    }

    public function show($id)
    {
        // Simple authorization check
        if (request()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);

        // Get stats
        $totalEntries = $user->hangerEntries()->count();
        $todayEntries = $user->hangerEntries()->whereDate('created_at', now())->count();

        // Get recent entries
        $recentEntries = $user->hangerEntries()
            ->with('province')
            ->latest()
            ->take(50)
            ->get()
            ->map(function ($entry) {
                $provinceName = $entry->getRawOriginal('province');
                if ($entry->relationLoaded('province') && $entry->getRelation('province')) {
                    $provinceName = $entry->getRelation('province')->name;
                }

                return [
                    'id' => $entry->id,
                    'outlet_name' => $entry->outlet_name,
                    'province' => $provinceName,
                    'hanger_id' => $entry->hanger_id,
                    'latitude' => $entry->latitude,
                    'longitude' => $entry->longitude,
                    'notes' => $entry->notes,
                    'photo_url' => $entry->photo_path ? '/storage/' . $entry->photo_path : null,
                    'created_at' => $entry->created_at,
                ];
            });

        return response()->json([
            'user' => $user,
            'stats' => [
                'total_entries' => $totalEntries,
                'today_entries' => $todayEntries,
            ],
            'recent_entries' => $recentEntries
        ]);
    }

    public function toggleStatus($id)
    {
        if (request()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);

        // Prevent admin form deactivating themselves
        if ($user->id === request()->user()->id) {
            return response()->json(['error' => 'Cannot deactivate your own account'], 400);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'message' => 'User status updated successfully',
            'is_active' => $user->is_active
        ]);
    }
    public function report(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $query = HangerEntry::with(['user:id,name', 'province'])
            ->latest();

        // Apply filters
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->province_id) {
            $query->where('province_id', $request->province_id);
        }

        // Get stats based on filtered query
        // Clone query for stats to avoid modifying the base query for pagination later if needed, 
        // though aggregators usually run fine.
        $statsQuery = clone $query;
        $stats = [
            'total_entries' => $statsQuery->count(),
            'total_outlets' => $statsQuery->distinct('outlet_name')->count('outlet_name'),
            'total_hangers' => $statsQuery->count(), // Assuming 1 entry = 1 hanger sold, or sum a quantity field if it exists? 
            // Looking at previous code, HangerEntry seems to represent a single transaction. 
            // If there's a quantity field, it should be summed. 
            // Checking previous context, it seems strictly 1 entry per hanger? 
            // Actually, let's assume count() for now. If there's a quantity field, I'd need to know.
            // Based on 'HangerEntry', it sounds singular.
        ];

        // Pagination
        $entries = $query->paginate(15);

        // Transform collection
        $entries->getCollection()->transform(function ($entry) {
            $provinceName = 'N/A';
            if ($entry->relationLoaded('province') && $entry->getRelation('province')) {
                $provinceName = $entry->getRelation('province')->name;
            } else {
                $provinceName = $entry->getRawOriginal('province') ?? 'N/A';
            }

            return [
                'id' => $entry->id,
                'created_at' => $entry->created_at,
                'salesman' => $entry->user ? $entry->user->name : 'Unknown',
                'outlet_name' => $entry->outlet_name,
                'province' => $provinceName,
                'hanger_id' => $entry->hanger_id,
            ];
        });

        return response()->json([
            'stats' => $stats,
            'entries' => $entries
        ]);
    }

    public function exportReport(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $query = HangerEntry::with(['user:id,name', 'province', 'brand'])
            ->latest();

        // Apply filters
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->province_id) {
            $query->where('province_id', $request->province_id);
        }

        $entries = $query->get();

        $filename = 'report-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=" . $filename,
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($entries) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Salesman', 'Outlet', 'Province', 'Brand', 'Hanger ID', 'Notes']);

            foreach ($entries as $entry) {
                $provinceName = 'N/A';
                if ($entry->relationLoaded('province') && $entry->getRelation('province')) {
                    $provinceName = $entry->getRelation('province')->name;
                } else {
                    $provinceName = $entry->getRawOriginal('province') ?? 'N/A';
                }

                $brandName = 'N/A';
                if ($entry->relationLoaded('brand') && $entry->getRelation('brand')) {
                    $brandName = $entry->getRelation('brand')->name;
                }

                fputcsv($file, [
                    $entry->created_at ? ' ' . $entry->created_at->format('Y-m-d H:i') : 'N/A',
                    $entry->user ? $entry->user->name : 'Unknown',
                    $entry->outlet_name,
                    $provinceName,
                    $brandName,
                    $entry->hanger_id,
                    $entry->notes
                ]);
            }

            // Add summary row
            fputcsv($file, []); // Blank row
            fputcsv($file, ['TOTAL HANGERS:', $entries->count()]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
