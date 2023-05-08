<?php

namespace App\Models;

use App\Enums\ResultFactor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fixture extends Model
{
    protected $fillable = [
        'homeowner_id', 'guest_id', 'week'
    ];
    use HasFactory;

    public static function addFixture($final_fixture, $week = 1)
    {
        $fixture = Fixture::query()->where([
            'homeowner_id' => $final_fixture['homeowner_id'],
            'guest_id' =>  $final_fixture['guest_id']
        ])->first();

        if (!$fixture) {
            $busy = false;
            $week_fixtures = Fixture::query()->where('week', $week)->get();
            if ($week_fixtures->count()) {
                foreach ($week_fixtures as $week_fixture) {
                    $homeowner_id = $week_fixture->homeowner_id;
                    $guest_id = $week_fixture->guest_id;
                    if (
                        in_array($homeowner_id, array_values($final_fixture))
                        ||
                        in_array($guest_id, array_values($final_fixture))
                    ) {
                        $busy = true;
                        break;
                    }
                }
            }
            if ($busy) {
                self::addFixture($final_fixture, $week + 1);
            } else {
                Fixture::create([
                    'week' => $week,
                    'homeowner_id' => $final_fixture['homeowner_id'],
                    'guest_id' => $final_fixture['guest_id']
                ]);
            }
        }
    }

    public static function selfFixtures(Team $team): Collection|array
    {
        return Fixture::query()->where(function ($query) use ($team){
            $query->where('homeowner_id', '=', $team->id)
                ->orWhere('guest_id', '=', $team->id);
        })->get();
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'fixture_id');
    }

    public function homeowner(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'homeowner_id');
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'guest_id');
    }

    public static function getNextWeek()
    {
        return Fixture::query()->whereDoesntHave('results')->orderBy('week')->get()
            ->groupBy('week')->first();
    }

    public function estimateResult()
    {
        $homeowner = $this->homeowner;
        $guest = $this->guest;

        $homeowner_score = 0;
        $guest_score = 0;
        $homeowner_score += floor($homeowner->strength / ResultFactor::StrengthDivider->value);
        $guest_score += floor($guest->strength / ResultFactor::StrengthDivider->value);

        $homeowner_score += ResultFactor::HomeownerGuestChanger->value;
        $guest_score -= ResultFactor::HomeownerGuestChanger->value;

        $winner = null;
        $loser = null;
        $drawn = false;
        $goals_for = $homeowner_score;
        $goals_against = $guest_score;

        if ($homeowner_score > $guest_score) {
            $winner = $homeowner;
            $loser = $guest;
        }

        if ($homeowner_score < $guest_score) {
            $winner = $guest;
            $goals_for = $guest_score;
            $loser = $homeowner;
            $goals_against = $homeowner_score;
        }

        if ($homeowner_score == $guest_score) {
            $drawn = true;

        }

        Result::create([
            'fixture_id' => $this->id,
            'winner_id' => !is_null($winner) ? $winner->id : null,
            'loser_id' => !is_null($loser) ? $loser->id : null,
            'drawn' => $drawn,
            'goals_for' => $goals_for,
            'goals_against' => $goals_against,
        ]);

    }
}
