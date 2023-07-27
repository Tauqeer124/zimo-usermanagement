<!-- index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h3>Add User Country</h3>
    <form id="addUserForm" action="" method="post">
        @csrf
      <div class="form-group">
        <label for="countryName">Country Name:</label>
        <input type="text" class="form-control" id="countryName" placeholder="Enter country name" required>
      </div>
      <button type="submit" class="btn btn-primary">Add</button>
    </form>
  </div>
  
@endsection
