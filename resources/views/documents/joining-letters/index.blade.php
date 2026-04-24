<x-layouts.app title="Appointment letters">
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/components/table_dropdown.css') }}">
        <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    @endpush()

    <section class="table-panel">
        <div class="table-responsive custom-table-wrap">
            <x-data_table :headers="[
        'code',
        'Name',
        'Email',
        'Department',
        'Issued Date',
        'Joining Date',
        'Option'
    ]" id="joining-letters" pagetitle="Confirmation Letters" ajax="documents.joining-letters.getdata" />
        </div>
    </section>

</x-layouts.app>