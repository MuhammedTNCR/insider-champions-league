@extends('layout')

@section('title', 'Page Title')

@section('content')
    <p>Tournament Teams</p>
    <table class="table">
        <thead>
        <tr>
            <th>Team Name</th>
        </tr>
        </thead>
        <tbody>
        @foreach($teams as $team)
        <tr>
            <td>{{ $team->name }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <form method="POST" action="{{route('teams.generate_fixtures')}}">
        @csrf
        <input type="submit" value="Generate Fixtures">
    </form>
@endsection
