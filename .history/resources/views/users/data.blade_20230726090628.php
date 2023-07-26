@extends('layouts.app')

@section('content')
<table id="userDataTable" class="table table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Country</th>

            <!-- Add more columns as needed -->
        </tr>
    </thead>
</table>

<script>
    $(document).ready(function() {
        $('#userDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('user-data', ['country' => $country]) }}",
           
            columns: [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'country', name: 'country' },
                // Add more columns as needed
            ]
        });
    });
</script>

@endsection