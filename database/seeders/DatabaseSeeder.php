<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Booking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = Member::create([
            'name' => 'First Member',
            'username' => 'admin',
            'email' => 'admin@fitness.com',
            'password' => 'password123',
            'phone' => '0123456789',
            'position' => 'Manager',
            'department' => 'Administration',
            'role' => 'admin',
            'isActive' => true,
            'current_points' => 100,
        ]);

        // Create staff user
        $staff = Member::create([
            'name' => 'Staff User',
            'username' => 'staff',
            'email' => 'staff@fitness.com',
            'password' => 'password123',
            'phone' => '0123456788',
            'position' => 'Trainer',
            'department' => 'Fitness',
            'role' => 'staff',
            'isActive' => true,
            'current_points' => 50,
        ]);

        // Create some members
        $member1 = Member::create([
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'phone' => '0123456787',
            'role' => 'member',
            'isActive' => true,
            'current_points' => 25,
        ]);

        $member2 = Member::create([
            'name' => 'Jane Smith',
            'username' => 'janesmith',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'phone' => '0123456786',
            'role' => 'member',
            'isActive' => true,
            'current_points' => 30,
        ]);

        // Create some bookings
        Booking::create([
            'member_id' => $member1->id,
            'class_name' => 'Yoga',
            'room_name' => 'Room A',
            'booking_date' => now()->addDays(1)->toDateString(),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'status' => 'confirmed',
            'payment_cost' => 10.00,
            'payment_status' => 'confirmed',
            'payment_method' => 'cash',
            'payment_transaction_id' => '123456',
            'earn_points' => 10,
        ]);

        Booking::create([
            'member_id' => $member1->id,
            'class_name' => 'Spinning',
            'room_name' => 'Room B',
            'booking_date' => now()->addDays(2)->toDateString(),
            'start_time' => '14:00',
            'end_time' => '15:00',
            'status' => 'pending',
            'payment_cost' => 15.00,
            'payment_status' => 'pending',
            'payment_method' => 'credit_card',
            'payment_transaction_id' => '789012',
            'earn_points' => 15,
        ]);

        Booking::create([
            'member_id' => $member2->id,
            'class_name' => 'Pilates',
            'room_name' => 'Room A',
            'booking_date' => now()->addDays(1)->toDateString(),
            'start_time' => '11:00',
            'end_time' => '12:00',
            'status' => 'confirmed',
            'payment_cost' => 20.00,
            'payment_status' => 'confirmed',
            'payment_method' => 'debit_card',
            'payment_transaction_id' => '345678',
            'earn_points' => 20,
        ]);
    }
}
