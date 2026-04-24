<x-layouts.app title="Offer letters">
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/components/table_dropdown.css') }}">
        <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    @endpush()
    <section class="table-panel" style="padding:0; margin:0;">
        <div class="table-responsive" style="width:100%; margin:0; padding:0;">
            <x-data_table :headers="[
        'Employee Code',
        'Name',
        'Department',
        'Designation',
        'CTC',
        'Variable',
        'Action'
    ]" id="Offer_letters" pagetitle="Offer Letters" ajax="documents.offer-letters.getdata" />
        </div>
    </section>
</x-layouts.app>