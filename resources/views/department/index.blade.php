<x-layouts.app>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/pages/departments.css') }}">
        <link rel="stylesheet" href="{{ asset('css/components/alert.css') }}">
    @endpush

    @if (session('success'))
        <x-tools.alert type="Success">
            {{ session('success') }}
        </x-tools.alert>
    @endif

    <x-bootstrap.modal id="add_department_modal" modeltitle="Add Department" submitbutton="Save Department"
        submitbuttonid="saveDepartmentButton">
        <form id="add_department_form" action="{{ route('department.departments.add') }}" method="POST">
            @csrf
            <table class="settings-form-table">
                <tr>
                    <td><label for="department_name">Department Name</label></td>
                    <td><input type="text" id="department_name" name="name" placeholder="Enter department name"
                            required></td>
                </tr>
            </table>
        </form>
    </x-bootstrap.modal>

    <x-bootstrap.modal id="edit_department_modal" modeltitle="Edit Department" submitbutton="Update Department"
        submitbuttonid="updateDepartmentButton">
        <form id="edit_department_form" action="{{ route('department.departments.edit') }}" method="POST">
            @csrf
            <input type="hidden" name="edit_id" id="edit_department_id">
            <table class="settings-form-table">
                <tr>
                    <td><label for="edit_department_name">Department Name</label></td>
                    <td><input type="text" id="edit_department_name" name="name" placeholder="Enter department name"
                            required></td>
                </tr>
            </table>
        </form>
    </x-bootstrap.modal>

    <x-bootstrap.modal id="add_designation_modal" modeltitle="Add Designation" submitbutton="Save Designation"
        submitbuttonid="saveDesignationButton">
        <form id="add_designation_form" action="{{ route('department.designations.add') }}" method="POST">
            @csrf
            <table class="settings-form-table">
                <tr>
                    <td><label for="designation_title">Title</label></td>
                    <td><input type="text" id="designation_title" name="title" placeholder="Enter designation title"
                            required></td>
                </tr>
                <tr>
                    <td><label for="designation_title">Department</label></td>
                    <td>
                        <select name="department" id="department">
                            <option value="-1"> Select Department</option>
                            @foreach ($dept as $row)

                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="designation_managerial">Managerial</label></td>
                    <td>
                        <select id="designation_managerial" name="is_managerial">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </td>
                </tr>
            </table>
        </form>
    </x-bootstrap.modal>

    <x-bootstrap.modal id="edit_designation_modal" modeltitle="Edit Designation" submitbutton="Update Designation"
        submitbuttonid="updateDesignationButton">
        <form id="edit_designation_form" action="{{ route('department.designations.edit') }}" method="POST">
            @csrf
            <input type="hidden" name="edit_id" id="edit_designation_id">
            <table class="settings-form-table">
                <tr>
                    <td><label for="edit_designation_title">Title</label></td>
                    <td><input type="text" id="edit_designation_title" name="title"
                            placeholder="Enter designation title" required></td>
                </tr>
                <tr>
                    <td><label for="designation_title">Department</label></td>
                    <td>
                        <select name="department" id="edit_department_designation">
                            <option value="-1"> Select Department</option>
                            @foreach ($dept as $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="edit_designation_managerial">Managerial</label></td>
                    <td>
                        <select id="edit_designation_managerial" name="is_managerial">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </td>
                </tr>
            </table>
        </form>
    </x-bootstrap.modal>

    <div class="settings-grid">
        <section class="settings-card">
            <div class="table-responsive">
                <x-data_table :headers="['ID', 'Department Name', 'Actions']" id="departments_table"
                    pagetitle="Departments" ajax="department.departments.data" />
            </div>
        </section>

        <section class="settings-card">
            <div class="table-responsive">
                <x-data_table :headers="['ID', 'Title', 'Actions']" id="designations_table" pagetitle="Designations"
                    ajax="department.designations.data" />
            </div>
        </section>
    </div>

    @push('external_scripts')
        <script>
            function editDepartment(id, name) {
                $('#edit_department_id').val(id);
                $('#edit_department_name').val(name);
                $('#edit_department_modal').modal('show');
            }

            function editDesignation(designation) {
                $('#edit_designation_id').val(designation.id);
                $('#edit_designation_title').val(designation.title ?? '');
                $('#edit_department_designation').val(designation.department);
                $('#edit_designation_managerial').val(designation.is_managerial ? '1' : '0');
                $('#edit_designation_modal').modal('show');
            }

            document.getElementById('saveDepartmentButton').addEventListener('click', function () {
                document.getElementById('add_department_form').submit();
            });

            document.getElementById('updateDepartmentButton').addEventListener('click', function () {
                document.getElementById('edit_department_form').submit();
            });

            document.getElementById('saveDesignationButton').addEventListener('click', function () {
                document.getElementById('add_designation_form').submit();
            });

            document.getElementById('updateDesignationButton').addEventListener('click', function () {
                document.getElementById('edit_designation_form').submit();
            });

            // title align center
            let title = document.getElementsByClassName('dt_title');
            Array.from(title).forEach(item => {
                item.style.textAlign = 'center';
            })

            // append buttons 
            let isTrigger = 0;
            $(document).on('table-ready', function (e, table) {
                isTrigger++;
                if (isTrigger != 2) { return; }

                let add_dept = $('<button>', {
                    text: 'Add Department',
                    click: function () {
                        $('#add_department_modal').modal('show');
                    }
                }).css({
                    width: '150px',
                    border: '1px solid grey',
                    position: 'relative',
                    marginTop: '5%',
                    marginLeft: '10%',
                    fontSize: '12px'
                });

                let add_design = $('<button>', {
                    text: 'Add Designation',
                    click: function () {
                        $('#add_designation_modal').modal('show');
                    }
                }).css({
                    width: '150px',
                    border: '1px solid grey',
                    position: 'relative',
                    marginTop: '5%',
                    marginLeft: '10%',
                    fontSize: '12px'
                });

                $('#departments_table_wrapper .dt-buttons').append(add_dept);
                $('#designations_table_wrapper .dt-buttons').append(add_design);
            });


        </script>
    @endpush
</x-layouts.app>