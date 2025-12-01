<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat admin default
        $admin = User::firstOrCreate(
            ['email' => 'admin@bpkad.local'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
            ]
        );

        // Buat beberapa user contoh
        $user1 = User::firstOrCreate(
            ['email' => 'karyawan1@bpkad.local'],
            [
                'name' => 'Karyawan 1',
                'password' => bcrypt('password123'),
                'role' => 'user',
            ]
        );
        if (!$user1->profile) {
            UserProfile::create([
                'user_id' => $user1->id,
                'school_name' => 'SMK Negeri 1 Garut',
                'division' => 'akuntansi',
            ]);
        }

        $user2 = User::firstOrCreate(
            ['email' => 'karyawan2@bpkad.local'],
            [
                'name' => 'Karyawan 2',
                'password' => bcrypt('password123'),
                'role' => 'user',
            ]
        );
        if (!$user2->profile) {
            UserProfile::create([
                'user_id' => $user2->id,
                'school_name' => 'SMK Negeri 2 Garut',
                'division' => 'sekretariat',
            ]);
        }

        $user3 = User::firstOrCreate(
            ['email' => 'karyawan3@bpkad.local'],
            [
                'name' => 'Karyawan 3',
                'password' => bcrypt('password123'),
                'role' => 'user',
            ]
        );
        if (!$user3->profile) {
            UserProfile::create([
                'user_id' => $user3->id,
                'school_name' => 'SMK Negeri 3 Garut',
                'division' => 'keuangan',
            ]);
        }
    }
}
