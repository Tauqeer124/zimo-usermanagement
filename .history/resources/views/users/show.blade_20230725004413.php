

@extends('layouts.app')

@section('content')
    <h1>User Details</h1>
    <p><strong>Name:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Phone:</strong> {{ $user->phone }}</p>
    <p><strong>Country:</strong> {{ $user->country }}</p>
    <a href="{{ route('user.index') }}">Back to Users</a>
@endsection
