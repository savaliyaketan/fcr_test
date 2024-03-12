<!DOCTYPE html>
<html>

<head>
    <title>Laravel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
</head>

<body>

    <div class="container">
        <h2>Laravel Ajax CRUD</h2>
        <a class="btn btn-success" href="javascript:void(0)" id="createNewEmployee"> Create New Employee</a>
        <table class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>email</th>
                    <th>country</th>
                    <th>state</th>
                    <th>image</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="employeeForm" name="employeeForm" class="form-horizontal" enctype="multipart/form-data">

                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>

                        <input type="hidden" name="employee_id" id="employee_id">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" required id="name" name="name"
                                    placeholder="Enter Name" value="" maxlength="50" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" required id="email" name="email"
                                    placeholder="Enter Email" value="" maxlength="50" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Country</label>
                            <div class="col-sm-12">
                                <select id="country-dropdown" name="country_id" class="form-control">
                                    <option value="">-- Select Country --</option>
                                    @foreach ($countries as $data)
                                        <option value="{{ $data->id }}">
                                            {{ $data->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">State</label>
                            <div class="col-sm-12">
                                <select id="state-dropdown" name="state_id" class="form-control">
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Image</label>
                            <img id="preview-image" width="300px">
                            <div class="col-sm-12">
                                <input type="file" name="image" id="inputImage" class="form-control">
                                <span class="text-danger" id="image-input-error"></span>
                            </div>
                        </div>



                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

<script type="text/javascript">
    $(function() {

        $('#inputImage').change(function() {
            let reader = new FileReader();

            reader.onload = (e) => {
                $('#preview-image').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0]);

        });

        /*------------------------------------------
         --------------------------------------------
         Pass Header Token
         --------------------------------------------
         --------------------------------------------*/
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        /*------------------------------------------
        --------------------------------------------
        Render DataTable
        --------------------------------------------
        --------------------------------------------*/
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('employee-ajax-crud.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                },
                {
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'email',
                    name: 'email',
                },
                {
                    data: 'country_id',
                    name: 'country_id',
                },
                {
                    data: 'state_id',
                    name: 'state_id',
                },

                {
                    data: 'image',
                    name: 'image',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        /*------------------------------------------
        --------------------------------------------
        Click to Button
        --------------------------------------------
        --------------------------------------------*/
        $('#createNewEmployee').click(function() {
            $('#saveBtn').val("create-employee");
            $('#employee_id').val('');
            $('#employeeForm').trigger("reset");
            $('#preview-image').attr('src', "");
            $('#modelHeading').html("Create New Employee");
            $('#ajaxModel').modal('show');
        });

        /*------------------------------------------
        --------------------------------------------
        Click to Edit Button
        --------------------------------------------
        --------------------------------------------*/
        $('body').on('click', '.editEmployee', function() {
            var employee_id = $(this).data('id');
            $.get("{{ route('employee-ajax-crud.index') }}" + '/' + employee_id + '/edit', function(
                data) {
                $('#modelHeading').html("Edit Employee");
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#employee_id').val(data.id);
                $('#name').val(data.name);
                $('#email').val(data.email);
                $('#country-dropdown').val(data.country_id);

                if (data.image != '' && data.image != null) {
                    $('#preview-image').attr('src', "images/" + data.image);
                } else {
                    $('#preview-image').attr('src', "");
                }


                var idCountry = data.country_id;
                $("#state-dropdown").html('');
                $.ajax({
                    url: "{{ url('api/fetch-states') }}",
                    type: "POST",
                    data: {
                        country_id: idCountry,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('#state-dropdown').html(
                            '<option value="">-- Select State --</option>');
                        $.each(result.states, function(key, value) {
                            $("#state-dropdown").append('<option value="' +
                                value
                                .id + '">' + value.name + '</option>');
                        });
                        $('#state-dropdown').val(data.state_id);
                    }
                });
            })
        });

        /*------------------------------------------
        --------------------------------------------
        Create Employee Code
        --------------------------------------------
        --------------------------------------------*/
        $('#saveBtn').click(function(e) {
            e.preventDefault();

            let myform = document.getElementById("employeeForm");
            let formData = new FormData(myform);
            $('#image-input-error').text('');

            $(this).html('Sending..');

            $.ajax({
                data: formData,
                url: "{{ route('employee-ajax-crud.store') }}",
                type: "POST",
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {

                    $('#employeeForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();

                },
                error: function(err) {


                    $('#employeeForm').find(".print-error-msg").find("ul").html('');
                    $('#employeeForm').find(".print-error-msg").css('display', 'block');

                    let error = JSON.parse(err.responseText);



                    $.each(error.errors, function(key, value) {
                        $('#employeeForm').find(".print-error-msg").find("ul")
                            .append('<li>' + value + '</li>');
                    });
                    $('#saveBtn').html('Save Changes');
                }
            });
        });

        /*------------------------------------------
        --------------------------------------------
        Delete Employee Code
        --------------------------------------------
        --------------------------------------------*/
        $('body').on('click', '.deleteEmployee', function() {

            var employee_id = $(this).data("id");
            if (confirm("Are You sure want to delete !")) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('employee-ajax-crud.store') }}" + '/' + employee_id,
                    success: function(data) {
                        table.draw();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            }


        });

        $('#country-dropdown').on('change', function() {
            var idCountry = this.value;
            $("#state-dropdown").html('');
            $.ajax({
                url: "{{ url('api/fetch-states') }}",
                type: "POST",
                data: {
                    country_id: idCountry,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    $('#state-dropdown').html(
                        '<option value="">-- Select State --</option>');
                    $.each(result.states, function(key, value) {
                        $("#state-dropdown").append('<option value="' + value
                            .id + '">' + value.name + '</option>');
                    });
                }
            });
        });

    });
</script>

</html>
