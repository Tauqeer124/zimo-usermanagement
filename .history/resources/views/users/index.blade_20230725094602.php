@extends('layouts.app')

@section('content')
    <!-- Users List -->
    <div class="container">
        <h1>Users record</h1>
        <table class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>phone</th>
                    <th>Country</th>
                    <th width="100px">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
       
    </body>
       
    <script type="text/javascript">
      $(function () {
    const userTable = $('#userTable').DataTable({
        serverSide: true,
        ajax: '/users',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            {
                data: null,
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function (data) {
                    return `
                        <button class="editBtn" data-id="${data.id}">Edit</button>
                        <button class="deleteBtn" data-id="${data.id}">Delete</button>
                    `;
                }
            }
        ]
    });
    </script>
    @endsection
