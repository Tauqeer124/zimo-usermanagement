@extends('layouts.app')

@section('content')
<!-- Users List -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Users record</h1>
            <div class="text-right mb-3">
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Add New User</a>
            </div>
            <table class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Country</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add New User Form -->
                <form id="addUserForm">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" class="form-control" id="country" name="country" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addUserBtn">Save User</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Edit User Form -->
                <form id="editUserForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editUserId" name="id">
                    <div class="form-group">
                        <label for="editName">Name</label>
                        <input type="text" class="form-control" id="editName" name="name" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="editEmail">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="editPhone">Phone</label>
                        <input type="text" class="form-control" id="editPhone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="editCountry">Country</label>
                        <input type="text" class="form-control" id="editCountry" name="country" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateUserBtn">Update User</button>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(function () {
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('user.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'country', name: 'country' },
                {
                    data: null,
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function (data) {
                        var showBtn = '<a href="{{ route("user.show", [":id"]) }}" class="btn btn-info btn-sm">Show</a>'
                            .replace(':id', data.id);
                        var editBtn = '<a href="{{ route("users.edit", [":id"]) }}" class="btn btn-primary btn-sm edit-btn" data-toggle="modal" data-target="#editUserModal" >Edit</a>'.replace(':id', data.id);
                        var deleteBtn = '<a href="{{ route("user.delete", [":id"]) }}" class="btn btn-danger btn-sm delete-btn" >Delete</a>'.replace(':id', data.id);
                        return showBtn + ' ' + editBtn + ' ' + deleteBtn;
                    }
                },
            ]
        });

        // Add a new user
        $('#addUserBtn').on('click', function () {
            var formData = $('#addUserForm').serialize();
            $.ajax({
                url: "{{ route('adduser') }}",
                type: "POST",
                data: formData,
                success: function (data) {
                    $('#addUserModal').modal('hide');
                    table.draw(false);
                },
                error: function (xhr) {
                    // Handle error messages if needed
                }
            });
        });

        // Edit a user
        $('.data-table').on('click', '.edit-btn', function () {
            var formData = $('#editUserForm').serialize();
            var userId = $(this).data('id');
            $.ajax({
                url: "/user/" + userId + "/edit",
                type: "GET",
                success: function (data) {
                    $('#editUserId').val(data.id);
                    $('#editName').val(data.name);
                    $('#editEmail').val(data.email);
                    $('#editPhone').val(data.phone);
                    $('#editCountry').val(data.country);
                    $('#editUserModal').modal('show');
                },
                error: function (xhr) {
                    // Handle error messages if needed
                }
            });
        });

        // Update a user
        $('#updateUserBtn').on('click', function () {
            var userId = $('#editUserId').val();
            var formData = $('#editUserForm').serialize();
            $.ajax({
                url: "/users/" + userId,
                type: "PUT",
                data: formData,
                success: function (data) {
                    $('#editUserModal').modal('hide');
                    table.draw(false);
                },
                error: function (xhr) {
                    // Handle error messages if needed
                }
            });
        });

        // Delete a user
        $('.data-table').on('click', '.delete-btn', function () {
            var userId = $(this).data('id');
            if (confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    url: "/userdel/" + userId,
                    type: 'DELETE',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    success: function (data) {
                        table.draw(false);
                    },
                    error: function (xhr) {
                        // Handle error messages if needed
                    }
                });
            }
        });

    });
</script>
@endsection

