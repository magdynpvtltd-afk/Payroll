<x-layouts.app title="Employees">
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/components/table_dropdown.css') }}">
        <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    @endpush()
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
            
        </script>
    @endpush
</x-layouts.app>