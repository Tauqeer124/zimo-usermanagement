@extends('layouts.app')

@section('content')
    <!-- Users List -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Users record</h1>
                <div class="text-right mb-3">
                    <a href="{{route('user.create')}}" class="btn btn-primary">Add New User</a>
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
       
    </body>
       
    <script type="text/javascript">
      $(function () {
        
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('user.index') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'country', name: 'country'},
                {
                    data: null,
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function (data) {
                        var showBtn = '<a href="{{ route("user.show", [":id"]) }}" class="btn btn-primary btn-sm">Show</a>'
                            .replace(':id', data.id);
                        var editBtn = '<a href="{{ route("user.edit", [":id"]) }}" class="btn btn-info btn-sm">Edit</a>'
                            .replace(':id', data.id);
                        var deleteBtn = '<a href="{{ route("user.delete", [":id"]) }}" class="btn btn-info btn-sm">Edit</a>'
                            .replace(':id', data.id);
                        return showBtn + ' ' + deleteBtn ;
                    }
                },
                
            ]
        });
        
      });
    </script>
@endsection
