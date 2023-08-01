@extends('layouts.app')

@section('content')
<div class="container">
    <h1>User Details</h1>
    <a href="{{ route('user_details.create') }}" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createUserDetailModal">Add New User Detail</a>
    <table class="table">
        <thead>
            <tr>
                <!-- Add table header columns for user details, e.g., address, city, zip code, etc. -->
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($userDetails as $userDetail)
            <tr>
                <!-- Display user details data in each row, e.g., {{ $userDetail->address }}, {{ $userDetail->city }}, etc. -->
                <td>
                    <a href="{{ route('user_details.show', $userDetail->id) }}" class="btn btn-info" data-toggle="modal" data-target="#showUserDetailModal{{ $userDetail->id }}">View</a>
                    <a href="{{ route('user_details.edit', $userDetail->id) }}" class="btn btn-primary" data-toggle="modal" data-target="#editUserDetailModal{{ $userDetail->id }}">Edit</a>
                    <form action="{{ route('user_details.destroy', $userDetail->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Create User Detail Modal -->
<div class="modal fade" id="createUserDetailModal" tabindex="-1" role="dialog" aria-labelledby="createUserDetailModalLabel" aria-hidden="true">
    <!-- Add the modal content for creating user detail here -->
</div>

<!-- Show User Detail Modal -->
@foreach ($userDetails as $userDetail)
<div class="modal fade" id="showUserDetailModal{{ $userDetail->id }}" tabindex="-1" role="dialog" aria-labelledby="showUserDetailModalLabel{{ $userDetail->id }}" aria-hidden="true">
    <!-- Add the modal content for showing user detail here -->
</div>
@endforeach

<!-- Edit User Detail Modal -->
@foreach ($userDetails as $userDetail)
<div class="modal fade" id="editUserDetailModal{{ $userDetail->id }}" tabindex="-1" role="dialog" aria-labelledby="editUserDetailModalLabel{{ $userDetail->id }}" aria-hidden="true">
    <!-- Add the modal content for editing user detail here -->
</div>
@endforeach

@endsection
