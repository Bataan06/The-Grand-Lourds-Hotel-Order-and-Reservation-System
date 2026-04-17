@extends('layouts.app')

@section('title', 'Reserve '.$room->name)

@section('content')
    <h1 class="text-2xl font-bold mb-4">Reserve {{ $room->name }}</h1>

    <form method="POST" action="{{ route('reservations.store', $room) }}" class="space-y-4">
        @csrf
        <div>
            <label for="check_in" class="block font-medium">Check in</label>
            <input type="date" name="check_in" id="check_in" value="{{ old('check_in') }}" class="border rounded w-full" required>
            @error('check_in')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="check_out" class="block font-medium">Check out</label>
            <input type="date" name="check_out" id="check_out" value="{{ old('check_out') }}" class="border rounded w-full" required>
            @error('check_out')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="px-4 py-2 bg-violet-600 text-white rounded hover:bg-violet-700">Confirm Reservation</button>
    </form>
@endsection
