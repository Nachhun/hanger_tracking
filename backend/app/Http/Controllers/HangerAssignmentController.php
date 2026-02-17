<?php

namespace App\Http\Controllers;

use App\Models\HangerAssignment;
use App\Models\User;
use Illuminate\Http\Request;

class HangerAssignmentController extends Controller
{
    public function store(Request $request, User $user)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:255',
        ]);

        $assignment = HangerAssignment::create([
            'user_id' => $user->id,
            'admin_id' => auth()->id(), // Assumes logged in admin
            'quantity' => $request->quantity,
            'note' => $request->note,
        ]);

        return response()->json([
            'message' => 'Hangers assigned successfully',
            'assignment' => $assignment,
        ]);
    }

    public function storeAll(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:255',
        ]);

        $users = User::where('role', 'salesman')
            ->where('is_active', true)
            ->get();

        if ($users->isEmpty()) {
            return response()->json(['message' => 'No active salesmen found'], 404);
        }

        $assignments = [];
        $now = now();
        $adminId = auth()->id();

        foreach ($users as $user) {
            $assignments[] = [
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'quantity' => $request->quantity,
                'note' => $request->note,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        HangerAssignment::insert($assignments);

        return response()->json([
            'message' => 'Hangers assigned to all active salesmen successfully',
            'count' => count($assignments),
        ]);
    }
}
