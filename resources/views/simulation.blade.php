@extends('layout')

@section('title', 'Page Title')

@section('content')
    <p>Simulation</p>
    <table class="table">
        <thead>
        <tr>
            <th>Team Name</th>
            <th>P</th>
            <th>W</th>
            <th>D</th>
            <th>L</th>
            <th>GD</th>
        </tr>
        </thead>
        <tbody>
        @foreach($teams as $name => $team)
            <tr>
                <td>{{ $name }}</td>
                <td>{{ $team['points'] }}</td>
                <td>{{ $team['wins'] }}</td>
                <td>{{ $team['drawns'] }}</td>
                <td>{{ $team['loses'] }}</td>
                <td>{{ $team['goal_difference'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <hr>
    @if($next_week)
    <table class="table">
        <thead>
        <tr>
            <th>Week {{ $next_week->first()->week }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($next_week as $week)
            <tr>
                <td>{{ $week->homeowner->name }} - {{ $week->guest->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
        <hr>
    @endif
    @if(!$next_week || $next_week->first()->week > 4)
        <table class="table">
            <thead>
            <tr>
                <th>Championship Predictions</th>
                <th>%</th>
            </tr>
            </thead>
            <tbody>
            @foreach($predictions as $name => $prediction)
                <tr>
                    <td>{{ $name }}</td>
                    <td>{{ $prediction }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
    @if ($next_week)
    <form method="POST" action="{{route('fixtures.play_next_week')}}">
        @csrf
        <input type="submit" value="Play Next Week">
    </form>
    @endif
@endsection
