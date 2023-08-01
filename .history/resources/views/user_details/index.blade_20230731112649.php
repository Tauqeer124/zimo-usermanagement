@extends('layouts.app')

@section('content')

<!-- User Details List -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>User Details Record</h1>
           
            <div class="text-right mb-3">
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addUserDetailModal">Add New User Detail</a>
            </div>
            

            <table class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>image</th>
                        <th>city</th>
                        
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @
                    <tr>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add User Detail Modal -->
<div class="modal fade" id="addUserDetailModal" tabindex="-1" role="dialog" aria-labelledby="addUserDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserDetailModalLabel">Add New User Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add New User Detail Form -->
                <form id="addUserDetailForm" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="editCountry">Country</label>
                        <select class="form-control" id="user" name="user_id" required>
                            <option value="">Select Country</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" >{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" class="form-control" id="name" name="city" required>
                    </div>
                    
                   
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addUserDetailBtn">Save User Detail</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Detail Modal -->
<div class="modal fade" id="editUserDetailModal" tabindex="-1" role="dialog" aria-labelledby="editUserDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserDetailModalLabel">Edit User Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Edit User Detail Form -->
                <form id="editUserDetailForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editUserDetailId" name="id">
                    <div class="form-group">
                        <label for="editCountry">User</label>
                        <select class="form-control" id="edituser" name="user_id" required>
                            <option value="">Select Country</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $user->id == $user->name ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editcity">City</label>
                        <input type="email" class="form-control" id="editcity" name="city" required>
                    </div>
                    
                   
                    <div class="form-group">
                        <label for="editImage">Image</label>
                        <input type="file" class="form-control" id="editimage" name="image" accept="image/*">
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
@section('content')
<!-- Your existing content here -->

<!-- JavaScript Code -->
<script type="text/javascript">
    $(function () {
        // Your existing JavaScript code here...

        // Add a new user detail
        $('#addUserDetailBtn').on('click', function () {
            var formData = new FormData($('#addUserDetailForm')[0]);
            $(this).html('Sending..');
            $.ajax({
                url: "{{ route('adduserdetail') }}",
                type: "POST",
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                success: function (data) {
                    $('#addUserDetailModal').modal('hide');
                    $('#addUserDetailForm').trigger("reset");
                    table.draw(false);
                },
                error: function (xhr) {
                    // Handle error messages if needed
                }
            });
        });

        // Edit a user detail
        $('.data-table').on('click', '.edit-btn', function () {
            var formData = $('#editUserDetailForm').serialize();
            var userDetailId = $(this).data('id');
    
            $.ajax({
                url: "/userdetail/edit/" + userDetailId,
                type: "GET",
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    $('#editUserDetailId').val(data.id);
                    $('#edituser').val(data.user_id);
                    $('#editimage').val(data.image);
                    $('#editcity').val(data.city);
                    $('#editUserDetailModal').modal('show');
                },
                error: function (xhr) {
                    // Handle error messages if needed
                }
            });
        });

        // Update a user detail
        $('#updateUserDetailBtn').on('click', function () {
            var userDetailId = $('#editUserDetailId').val();
            var formData = new FormData($('#editUserDetailForm')[0]);
            formData.append('_method', 'PUT');
            $.ajax({
                url: "/userdetail/update/" + userDetailId,
                type: "POST",
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                success: function (data) {
                    $('#editUserDetailModal').modal('hide');
                    table.draw(false);
                },
                error: function (xhr) {
                    // Handle error messages if needed
                }
            });
        });

        // Delete a user detail
        $('.data-table').on('click', '.delete-btn', function () {
            var userDetailId = $(this).data('id');
            if (confirm('Are you sure you want to delete this user detail?')) {
                $.ajax({
                    url: "/userdetail/delete/" + userDetailId,
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

