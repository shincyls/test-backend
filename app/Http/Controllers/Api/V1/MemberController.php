<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::query();

        // Filter by name
        if ($request->has('name') && $request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter by username
        if ($request->has('username') && $request->username) {
            $query->where('username', 'like', '%' . $request->username . '%');
        }

        // Filter by email
        if ($request->has('email') && $request->email) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

       // Filter by phone
        if ($request->has('phone') && $request->phone) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        // Pagination
        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1);

        $total = $query->count();
        $members = $query->skip(($page - 1) * $limit)->take($limit)->get();

        return response()->json([
            'admins' => $members,
            'total' => $total,
            'page' => (int) $page,
            'limit' => (int) $limit,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:members',
            'email' => 'required|string|email|max:255|unique:members',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'role' => 'nullable|in:staff,admin,member',
            'isActive' => 'nullable|boolean',
        ]);

        $member = Member::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'position' => $request->position,
            'department' => $request->department,
            'role' => $request->role ?? 'member',
            'isActive' => $request->isActive ?? true,
            'current_points' => 0,
        ]);

        return response()->json([
            'message' => 'Member created successfully',
            'member' => $member,
        ], 201);
    }

    public function show(string $id)
    {
        $member = Member::findOrFail($id);

        return response()->json($member);
    }

    public function update(Request $request, string $id)
    {
        $member = Member::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:255|unique:members,username,' . $id,
            'email' => 'sometimes|string|email|max:255|unique:members,email,' . $id,
            'password' => 'sometimes|string|min:6',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'role' => 'nullable|in:staff,admin,member',
            'isActive' => 'nullable|boolean',
            'current_points' => 'nullable|integer',
        ]);

        $data = $request->only([
            'name', 'username', 'email', 'phone',
            'position', 'department', 'role', 'isActive', 'current_points'
        ]);

        // Only update password if provided and not empty
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $member->update($data);

        return response()->json([
            'message' => 'Member updated successfully',
            'member' => $member->fresh(),
        ]);
    }

    public function destroy(string $id)
    {
        $member = Member::findOrFail($id);
        $member->delete();

        return response()->json([
            'message' => 'Member deleted successfully',
        ]);
    }

    public function bookings(string $id)
    {
        $member = Member::findOrFail($id);

        return response()->json([
            'bookings' => $member->bookings,
        ]);
    }
}

