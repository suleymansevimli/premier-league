<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class PremierLeagueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teams = [
            [
                'team' => 'Manchester City',
                'point' => 0,
                'won' => 0,
                'equalization' => 0,
                'lose' => 0
            ],
            [
                'team' => 'Manchester United',
                'point' => 0,
                'won' => 0,
                'equalization' => 0,
                'lose' => 0
            ],
            [
                'team' => 'Leicester City',
                'point' => 0,
                'won' => 0,
                'equalization' => 0,
                'lose' => 0
            ],
            [
                'team' => 'Chelsea',
                'point' => 0,
                'won' => 0,
                'equalization' => 0,
                'lose' => 0
            ]
        ];

        foreach ($teams as $team) {
            DB::table('premier_leagues')->insert([
                'team' => $team['team'],
                'point' => $team['point'],
                'won' => $team['won'],
                'equalization' => $team['equalization'],
                'lose' => $team['lose'],
                'weekly_score' => 0,
                'week' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);
        }

    }
}
