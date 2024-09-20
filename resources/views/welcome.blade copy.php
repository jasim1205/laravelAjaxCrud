<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.2/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-4 shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Employee Registation</h4>
                        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="bi bi-database-add"></i> ADD
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="display">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Phone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="employee-form" method="post">
                            <div class="row">
                                <div class="col-lg">
                                    <label>Name</label>
                                    <input type="text" name="name" id="name" class="form-control">
                                </div>
                                <div class="col-lg">
                                    <label>Email</label>
                                    <input type="email" name="email" id="email" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg">
                                    <label>Address</label>
                                    <input type="text" name="address" id="address" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg">
                                    <label>Phone</label>
                                    <input type="text" name="phone" id="phone" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button class="btn btn-primary" form="employee-form">Save</button>
                            </div>
                        </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Employee</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="edit-form" method="post">
                                <input type="hidden" id="edit-id" name="id">
                                <input type="hidden" name="_method" value="PATCh"> <!-- Method spoofing for PUT request -->
                                <div class="row">
                                    <div class="col-lg">
                                        <label>Name</label>
                                        <input type="text" id="edit-name" name="name" class="form-control">
                                    </div>
                                    <div class="col-lg">
                                        <label>Email</label>
                                        <input type="email" id="edit-email" name="email" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg">
                                        <label>Address</label>
                                        <input type="text" id="edit-address" name="address" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg">
                                        <label>Phone</label>
                                        <input type="text" id="edit-phone" name="phone" class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Edit</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTable
            var table = $('#myTable').DataTable({
                "ajax": {
                    "url": "{{ route('user.index') }}", // Make sure this route returns the correct data
                    "type": "GET",
                    "dataType": "json",
                    "dataSrc": function (response) {
                        return response.status === 200 ? response.users : [];
                    }
                },
                "columns": [
                    { "data": "id" },
                    { "data": "name" },
                    { "data": "email" },
                    { "data": "address" },
                    { "data": "phone" },
                    {
                        "data": null,
                        "render": function (data) {
                            return `
                                <a href="#" class="btn btn-sm btn-success edit-btn" data-id="${data.id}" data-name="${data.name}" data-email="${data.email}" data-address="${data.address}" data-phone="${data.phone}">Edit</a>
                                <a href="#" class="btn btn-sm btn-danger delete-btn" data-id="${data.id}">Delete</a>
                            `;
                        }
                    }
                ]
            });

            // Handle Edit Button Click
            $('#myTable tbody').on('click', '.edit-btn', function () {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var email = $(this).data('email');
                var address = $(this).data('address');
                var phone = $(this).data('phone');

                // Fill the form fields in the modal with the data
                $('#edit-id').val(id);
                $('#edit-name').val(name);
                $('#edit-email').val(email);
                $('#edit-address').val(address);
                $('#edit-phone').val(phone);

                // Show the modal
                $('#editModal').modal('show');
            });



            $('#employee-form').submit(function (e) {
                e.preventDefault(); // Prevent the default form submission
                const employeedata = new FormData(this);

                $.ajax({
                    url: '{{ route("user.store") }}', // Ensure the route is correct
                    method: 'post',
                    data: employeedata,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF Token for security
                    },
                    success: function(response) {
                        if (response.status == 200) {
                            alert("Saved successfully");
                            $('#employee-form')[0].reset(); // Reset form after successful submission
                            $('#exampleModal').modal('hide'); // Hide the modal
                            $('#myTable').DataTable().ajax.reload(); // Reload the DataTable
                        } else {
                            alert("An error occurred");
                        }
                    },
                    error: function(xhr) {
                        // Handle error response
                        alert("Error: " + xhr.responseText);
                    }
                });
            });
        });


        $('#edit-form').submit(function (e) {
                e.preventDefault();
                const employeedata = new FormData(this);
                const userId = $('#edit-id').val(); // Get the actual value of the user ID

                $.ajax({
                    url: '{{ route('user.update', '') }}/' + userId, // Append the user ID to the update URL
                    method: 'POST',
                    data: employeedata,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 200) {
                            alert(response.message);
                            $('#edit-form')[0].reset();
                            $('#editModal').modal('hide');
                            $('#myTable').DataTable().ajax.reload();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });

        
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');

                if (confirm('Are you sure you want to delete this employee?')) {
                    $.ajax({
                        url: '{{ route('user.destroy', ':id') }}'.replace(':id', id),
                        type: 'POST', // Use POST because we're sending a DELETE method spoofed request
                        data: {
                            _method: 'DELETE', // Spoof DELETE method
                            _token: $('meta[name="csrf-token"]').attr('content') // CSRF token for security
                        },
                        success: function(response) {
                            console.log(response); // Debugging: log the response
                            if (response.status === 200) {
                                //alert(response.message); // Show success message
                                $('#myTable').DataTable().ajax.reload(); // Reload the table data
                            } else {
                                alert(response.message); // Show error message
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr); // Debugging: log the error
                            alert('Error: ' + error); // Show generic error message
                        }
                    });
                }
            });

    </script>

</body>
</html>