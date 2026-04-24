<x-layouts.app>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/components/table_dropdown.css') }}">
        <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    @endpush()
    <section class="table-panel" style="padding:0; margin:0;">
        <div class="table-responsive" style="width:100%; margin:0; padding:0;">
            <x-data_table :headers="[
        'id',
        'type',
        'value',
    ]" id="allowance_table" pagetitle="Allowance List"
                ajax="allowance.getData" />
        </div>
    </section>

    @push('external_scripts')
        <script>
            let allowance_rows = document.querySelectorAll('#allowance_table tbody tr  td:nth-child(2)');
            Array.from(allowance_rows).forEach(function (row) {
                row.addEventListener('dblclick', function (e) {
                    alert('working');
                    let existing_value = e.target.textContent;
                    let name = e.target.className;

                    // creating form when doublic click event occurs 
                    let form = document.createElement('form');
                    form.action = "{{ route('allowance.edit') }}";
                    form.method = "POST";

                    // creatin input box to append
                    let input1 = document.createElement('input');
                    input1.type = "text";
                    input1.name = 'new_value';
                    input1.value = existing_value;

                    // type 
                    let input2 = document.createElement('input');
                    input2.type = "hidden";
                    input2.name = 'name';
                    input2.value = name;

                    // id of the allowance 
                    let input3 = document.createElement('input');
                    input3.type = "hidden";
                    input3.name = 'id';
                    input3.value = e.target.dataset.id;

                    // csrf token 
                    let csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = "_token";
                    csrf.value = document.querySelector('meta[name="csrf-token"]').content;

                    // adding event listner for the input1 to make a ajax call 
                    input1.addEventListener('blur', function (t) {
                        let new_value = t.target.value;
                        if (new_value == existing_value) {
                            e.target.innerHTML = existing_value;
                        }
                        else {
                            form.submit();
                        }
                    })

                    form.append(input1, input2, csrf, input3);
                    e.target.innerHTML = '';
                    e.target.appendChild(form);
                    input1.focus();
                })
            });
        </script>
    @endpush
</x-layouts.app>