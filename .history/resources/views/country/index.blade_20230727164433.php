<!-- index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Country Data</h1>
            <div class="text-right mb-3">
                <button class="btn btn-primary" data-toggle="modal" data-target="#addCountryModal">Add New Country</button>
            </div>
            <table class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach ($countries as $country)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $country->name }}</td>
                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Country Modal -->
<div class="modal fade" id="addCountryModal" tabindex="-1" role="dialog" aria-labelledby="addCountryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCountryModalLabel">Add New Country</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add New Country Form -->
                <form id="addCountryForm">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addCountryBtn">Add Country</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Add a new country
        $('#addCountryBtn').on('click', function () {
            var formData = $('#addCountryForm').serialize();
            $.ajax({
                url: "{{ route('country.add') }}",
                type: "POST",
                data: formData,
                dataType: 'json',
                success: function (data) {
                    $('#addCountryModal').modal('hide');
                    $('#addCountryForm')[0].reset(); // Reset the form to clear the input fields
                    // Append the new country data to the table
                    var newRow = '<tr><td>' + data.id + '</td><td>' + data.name + '</td></tr>';
                    $('.data-table tbody').append(newRow);
                },
                error: function (xhr) {
                    // Handle error messages if needed
                }
            });
        });
    });
</script>
@endsection
