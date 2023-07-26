@extends('layouts.app')

@section('content')
    <!-- Users List -->
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Users List') }}</div>
                <div class="card-body">
                    @if (count($users) > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Country</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->country }}</td>
                                        <td>
                                            <a href="{{ route('user.show', ['id' => $user->id]) }}">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No users found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
