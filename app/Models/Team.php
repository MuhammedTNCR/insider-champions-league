<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Team extends Model
{
    protected $fillable = ['name', 'strength'];
    use HasFactory;

    public static function generateFixtures($teams)
    {
        $fixtures = [];
        foreach ($teams as $team) {
            $other_teams = Team::query()->whereNot('id', $team->id)->get();
            foreach ($other_teams as $other_team) {
                $fixtures[] = [
                    'homeowner_id' => $team->id,
                    'guest_id' => $other_team->id,
                ];
                $fixtures[] = [
                    'homeowner_id' => $other_team->id,
                    'guest_id' => $team->id,
                ];
            }
        }

        $final_fixtures = [];

        foreach ($fixtures as $fixture) {
            if (!in_array($fixture, $final_fixtures)) {
                $final_fixtures[] = $fixture;
            }
        }

        $final_fixtures = Arr::shuffle($final_fixtures);
        foreach ($final_fixtures as $final_fixture) {
            Fixture::addFixture($final_fixture);
        }
    }

    public function getPointsAttribute(): int
    {
        $fixtures = Fixture::selfFixtures($this);
        $results = Result::teamResults($fixtures);

        $points = 0;
        foreach ($results as $result) {
            if ($result->drawn) {
                $points +=1;
                continue;
            }

            if ($result->winner_id == $this->id) {
                $points +=3;
            }
        }

        return $points;
    }

    public function getWDLAttribute()
    {
        $fixtures = Fixture::selfFixtures($this);
        $results = Result::teamResults($fixtures);

        $wins = 0;
        $drawns = 0;
        $loses = 0;

        foreach ($results as $result) {
            if ($result->winner_id == $this->id) {
                $wins +=1;
                continue;
            }
            if ($result->drawn) {
                $drawns +=1;
                continue;
            }

            $loses +=1;
        }

        return [
            'wins' => $wins,
            'drawns' => $drawns,
            'loses' => $loses
        ];
    }

    public function getGoalDifferenceAttribute()
    {
        $fixtures = Fixture::selfFixtures($this);
        $results = Result::teamResults($fixtures);

        $goals_for = 0;
        $goals_against = 0;

        foreach ($results as $result) {
            if ($result->winner_id == $this->id || $result->drawn) {
                $goals_for +=$result->goals_for;
                $goals_against +=$result->goals_against;
                continue;
            }

            $goals_for +=$result->goals_against;
            $goals_against +=$result->goals_for;


        }

        return $goals_for - $goals_against;
    }

    public static function getOrdering()
    {
        $teams = Team::query()->get();
        $ordering = [];
        foreach ($teams as $team) {
            $wdl = $team->WDL;
            $ordering[$team->name] = [
                'points' => $team->points,
                'wins' => $wdl['wins'],
                'drawns' => $wdl['drawns'],
                'loses' => $wdl['loses'],
                'goal_difference' => $team->goal_difference
            ];
        }

        $ordering = collect($ordering);

        return $ordering->sortBy([
            fn (array $a, array $b) => $b['points'] <=> $a['points'],
            fn (array $a, array $b) => $b['wins'] <=> $a['wins'],
            fn (array $a, array $b) => $a['drawns'] <=> $b['drawns'],
            fn (array $a, array $b) => $a['loses'] <=> $b['loses'],
            fn (array $a, array $b) => $b['goal_difference'] <=> $a['goal_difference'],
        ]);
    }

    public static function getPredictions($teams)
    {
        $sum = $teams->sum('points');

        $predictions = [];

        foreach ($teams as $name => $team) {
            $predictions[$name] = floor($team['points'] * 100 / $sum);
        }

        return $predictions;
    }
}
