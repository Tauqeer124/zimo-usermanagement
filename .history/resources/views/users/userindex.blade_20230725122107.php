<div class="container">
    <h1>Laravel 8 AJAX CRUD Using Datatable - Techsolutionstuff</h1>
    <a class="btn btn-info" href="javascript:void(0)" id="createNewUser"> Add New Post</a>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>phone</th>
                <th>country</th>

                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
   
<div class="modal fade" id="ajaxModelexa" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="postForm" name="postForm" class="form-horizontal">
                   <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="title" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" required>
                        </div>
                    </div>
     
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-12">
                            <input type="email" id="email" name="email" required placeholder="Enter Email" class="form-control"></textarea>
                        </div>
                    </div>
      
                    <div class="col-sm-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-primary" id="savedata" value="create">Save User
                     </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    
</body>
    
<script type="text/javascript">
  $(function () {
     
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('user.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'title', name: 'name'},
            {data: 'description', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'country', name: 'country'},

            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
     
    $('#createNewUser').click(function () {
        $('#savedata').val("create-post");
        $('#id').val('');
        $('#postForm').trigger("reset");
        $('#modelHeading').html("Create New Post");
        $('#ajaxModelexa').modal('show');
    });
    
    $('body').on('click', '.editPost', function () {
      var id = $(this).data('id');
      $.get("{{ route('users.edit') }}" + id , function (data) {
          $('#modelHeading').html("Edit Post");
          $('#savedata').val("edit-user");
          $('#ajaxModelexa').modal('show');
          $('#id').val(data.id);
          $('#title').val(data.title);
          $('#country').val(data.t);
          $('#description').val(data.description);
      })
   });
    
    $('#savedata').click(function (e) {
        e.preventDefault();
        $(this).html('Sending..');
    
        $.ajax({
          data: $('#postForm').serialize(),
          url: "{{ route('adduser') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
     
              $('#postForm').trigger("reset");
              $('#ajaxModelexa').modal('hide');
              table.draw();
         
          },
          error: function (data) {
              console.log('Error:', data);
              $('#savedata').html('Save Changes');
          }
      });
    });
    
    $('body').on('click', '.deletePost', function () {
     
        var id = $(this).data("id");
        confirm("Are You sure want to delete this Post!");
      
        $.ajax({
            type: "DELETE",
            url: "{{ route('user.delete') }}" +id,
            success: function (data) {
                table.draw();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
     
  });
</script>