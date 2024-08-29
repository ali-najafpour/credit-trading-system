<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // manager:
        $users = [
            [
                'first_name' => 'ali',
                'last_name' => 'najafpour',
                'username' => 'ali.najafpour',
                'cell_number' => '9118775280',
                'cell_number_verified_at' => now(),
                'email' => 'alinajafpouret@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'manager'
            ],
            [
                'first_name' => 'ali 1',
                'last_name' => 'najafpour',
                'username' => 'ali.najafpour.1',
                'cell_number' => '9118775281',
                'cell_number_verified_at' => now(),
                'email' => 'ali_najafpour@ut.ac.ir',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'manager'
            ],
        ];

        foreach ($users as $user) {
            $userModel = User::updateOrCreate(['cell_number' => $user['cell_number']], $user);
        }

        $manager_seed_count = 2;

        $manager_count = User::query()->isManager()->isActive()->count();
        if($manager_count < $manager_seed_count){
            $count = $manager_seed_count - $manager_count;
            $tasks = User::factory()->count($count)->manager()->create();
        }


        // admin
        $users = [
            [
                'first_name' => 'ali 2',
                'last_name' => 'najafpour',
                'username' => 'ali.najafpour.2',
                'cell_number' => '9118775282',
                'cell_number_verified_at' => now(),
                'email' => null,
                'email_verified_at' => null,
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]
        ];

        foreach ($users as $user) {
            $userModel = User::updateOrCreate(['cell_number' => $user['cell_number']], $user);
        }

        $admin_seed_count = 3;

        $admin_count = User::query()->isAdmin()->isActive()->count();
        if($admin_count < $admin_seed_count){
            $count = $admin_seed_count - $admin_count;
            $tasks = User::factory()->count($count)->admin()->create();
        }

        // client
        $client_seed_count = 10;

        $client_count = User::query()->isClient()->isActive()->count();
        if($client_count < $client_seed_count){
            $count = $client_seed_count - $client_count;
            $tasks = User::factory()->count($count)->create();
        }

    }
}
