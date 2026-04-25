<x-layouts.app title="Employees">
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/components/table_dropdown.css') }}">
        <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    @endpush()


    <!-- modal -->
    <div class="modal fade" id="joining_letter_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-5" id="exampleModalLabel">Generate Confirmation Letter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('documents.joining-letters.store') }}" method="POST"
                        id="joining_letter_form">
                        @csrf
                        <table>
                            <tr>
                                <td>Confirm Date</td>
                                <td>:</td>
                                <td><input type="date" name="confirmation_date" id="confirmation_date"></td>
                            </tr>
                        </table>
                        <input type="hidden" name="employee_id" id="fom_employee_id">
                       
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-primary" id="joining_letter_submit">Generate</button>
                </div>
            </div>
        </div>
    </div>

    <section class="table-panel" style="padding:0; margin:0;">
        <div class="table-responsive" style="width:100%; margin:0; padding:0;">
            <x-data_table :headers="[
        'code',
        'Name',
        'Email',
        'Department',
        'Designation',
        'CTC',
        'Variable',
        'Status',
        'Action'
    ]" id="employee_table" pagetitle="Employees List" ajax="employees.getdata" />
        </div>
    </section>
    @push('external_scripts')
        <script>
            $(document).on('table-ready', function (e, table) {
                let add_button = $('<button>', {
                    text: 'Add Employee',
                    click: function () {
                        window.open('{{ route("employees.create") }}', '_blank')
                    }
                }).css({
                    width: '100px',
                    border: '1px solid grey',
                    position: 'relative',
                    marginTop: '5%',
                    marginLeft: '10%'
                });
                $('.dt-buttons').append(add_button);
            });

            function newJoiningLetter(id) {
                document.getElementById('fom_employee_id').value = id;
                $('#joining_letter_modal').modal('show')
            }

            document.getElementById('joining_letter_submit').addEventListener('click', function () {
                document.getElementById('joining_letter_form').submit();
            });
        </script>
    @endpush
</x-layouts.app>