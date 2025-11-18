<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'team' => null, 
        ]);

        // Ketua Tim per PIC
        $teams = ['Umum', 'Produksi', 'Distribusi', 'Neraca', 'Sosial', 'IPDS'];
        
        foreach ($teams as $team) {
            // Ketua Tim
            User::create([
                'name' => "Ketua Tim {$team}",
                'email' => strtolower("ketua.{$team}@example.com"),
                'password' => Hash::make('password'),
                'role' => 'ketua_tim',
                'team' => $team,
            ]);

            // Operator/Anggota Tim
            User::create([
                'name' => "Operator {$team}",
                'email' => strtolower("operator.{$team}@example.com"),
                'password' => Hash::make('password'),
                'role' => 'operator',
                'team' => $team,
            ]);
        }

        // Viewer 
        User::create([
            'name' => 'Viewer',
            'email' => 'viewer@example.com',
            'password' => Hash::make('password'),
            'role' => 'viewer',
            'team' => null, 
        ]);
    }
}