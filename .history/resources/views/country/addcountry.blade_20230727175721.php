<!-- index.blade.php -->
@extends('layouts.app')

@section('content')




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
                    $('#addCountryForm').trigger("reset"); // Reset the form to clear the input fields
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
