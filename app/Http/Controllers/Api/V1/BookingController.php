<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Member;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with('member');

        // Filter by member_id
        if ($request->has('member_id') && $request->member_id) {
            $query->where('member_id', $request->member_id);
        }

        // Filter by class_name
        if ($request->has('class_name') && $request->class_name) {
            $query->where('class_name', 'like', '%' . $request->class_name . '%');
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->has('booking_date') && $request->booking_date) {
            $query->whereDate('booking_date', $request->booking_date);
        }

        // Filter by username
        if ($request->has('username') && $request->username) {
            $query->whereHas('member', function ($q) use ($request) {
                $q->where('username', 'like', '%' . $request->username . '%');
            });
        }

        // Filter by phone
        if ($request->has('phone') && $request->phone) {
            $query->whereHas('member', function ($q) use ($request) {
                $q->where('phone', 'like', '%' . $request->phone . '%');
            });
        }

        // Pagination
        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1);

        $total = $query->count();
        $bookings = $query->skip(($page - 1) * $limit)->take($limit)->get();

        return response()->json([
            'bookings' => $bookings,
            'total' => $total,
            'page' => (int) $page,
            'limit' => (int) $limit,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'class_name' => 'required|string|max:255',
            'room_name' => 'nullable|string|max:255',
            'booking_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'nullable|in:pending,confirmed,cancelled',
        ]);

        // format booking_date to Y-m-d and start_time, end_time to H:i:s
        $request->merge([
            'booking_date' => date('Y-m-d', strtotime($request->booking_date)),
            'start_time' => date('H:i:s', strtotime($request->start_time)),
            'end_time' => date('H:i:s', strtotime($request->end_time)),
        ]);

        $booking = Booking::create([
            'member_id' => $request->member_id,
            'class_name' => $request->class_name,
            'room_name' => $request->room_name,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => $request->status ?? 'pending',
        ]);

        // Update Earn Point
        $member = Member::findOrFail($request->member_id);
        $member->current_points = Booking::where('member_id', $request->member_id)->sum('earn_points');
        $member->save();

        return response()->json([
            'message' => 'Booking created successfully, User points updated to ' . $member->current_points,
            'booking' => $booking->load('member'),
        ], 201);
    }

    public function show(string $id)
    {
        $booking = Booking::with('member')->findOrFail($id);

        return response()->json($booking);
    }

    public function update(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);

        // format booking_date to Y-m-d and start_time, end_time to H:i:s
        $request->merge([
            'booking_date' => date('Y-m-d', strtotime($request->booking_date)),
            'start_time' => date('H:i:s', strtotime($request->start_time)),
            'end_time' => date('H:i:s', strtotime($request->end_time)),
        ]);

        $request->validate([
            'member_id' => 'sometimes|exists:members,id',
            'class_name' => 'sometimes|string|max:255',
            'room_name' => 'nullable|string|max:255',
            'status' => 'nullable|in:pending,confirmed,cancelled',
            'earn_points' => 'nullable|integer',
        ]);

        $booking->update($request->only([
            'member_id', 'class_name', 'room_name',
            'booking_date', 'start_time', 'end_time', 'status', 'earn_points'
        ]));

        // Update Earn Point
        $member = Member::findOrFail($request->member_id);
        $member->current_points = Booking::where('member_id', $request->member_id)->sum('earn_points');
        $member->save();

        return response()->json([
            'message' => 'Booking updated successfully. User points updated to ' . $member->current_points,
            'booking' => $booking->fresh()->load('member'),
        ]);
    }

    public function destroy(string $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return response()->json([
            'message' => 'Booking deleted successfully',
        ]);
    }
}

