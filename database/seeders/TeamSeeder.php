<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            [
                'name' => 'Chelsea',
                'strength' => 50
            ],
            [
                'name' => 'Arsenal',
                'strength' => 90
            ],
            [
                'name' => 'Manchester City',
                'strength' => 100
            ],
            [
                'name' => 'Liverpool',
                'strength' => 70
            ]
        ];

        foreach ($teams as $team) {
            Team::create([
                'name' => $team['name'],
                'strength' => $team['strength']
            ]);
        }
    }
}
