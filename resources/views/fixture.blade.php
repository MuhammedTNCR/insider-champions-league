@extends('layout')

@section('title', 'Page Title')

@section('content')
    <p>Generated Fixtures</p>
    @foreach($fixtures as $week => $fixture)
    <table class="table">
        <thead>
        <tr>
            <th>Week {{ $week }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($fixture as $f)
            @php
            $homeowner = \App\Models\Team::find($f->homeowner_id);
            $guest = \App\Models\Team::find($f->guest_id);
            @endphp
            <tr>
                <td>{{ $homeowner->name }} - {{ $guest->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endforeach
    <a href="{{ route('fixtures.simulation') }}" class="btn btn-secondary">Start Simulation</a>
@endsection
