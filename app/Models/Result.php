<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;

class Result extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'fixture_id', 'winner_id', 'loser_id', 'drawn', 'goals_for', 'goals_against', 'deleted_at'
    ];
    use HasFactory;

    public static function teamResults($fixtures): Collection|array
    {
        return self::query()->whereIn('fixture_id', $fixtures->pluck('id')->toArray())->get();
    }
}
