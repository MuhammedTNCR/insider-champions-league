<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFixtureRequest;
use App\Http\Requests\UpdateFixtureRequest;
use App\Models\Fixture;
use App\Models\Team;

class FixtureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFixtureRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Fixture $fixture)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fixture $fixture)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFixtureRequest $request, Fixture $fixture)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fixture $fixture)
    {
        //
    }

    public function simulation()
    {
        $teams = Team::getOrdering();
        $predictions = Team::getPredictions($teams);
        $next_week = Fixture::getNextWeek();

        return view('simulation', compact('teams', 'next_week', 'predictions'));
    }

    public function playNextWeek()
    {
        $next_week = Fixture::getNextWeek();

        foreach ($next_week as $week) {
            $week->estimateResult();
        }

        return redirect()->route('fixtures.simulation');
    }
}
