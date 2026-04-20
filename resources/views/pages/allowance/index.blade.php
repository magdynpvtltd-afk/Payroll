<x-layouts.app>
    @push('styles')
       <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    @endpush()

    @if (session('success'))
        <div class="app-flash app-flash--success" role="status">{{ session('success') }}</div>
    @endif
    <div class="table-container">
        <div class="table-header">
            <section>Showing {{$allowance->firstItem()}} to {{ $allowance->lastItem() }} of {{ $allowance->count() }}
                entries</section>
            <section>Allowance List</section>
            <section>
                {{ $allowance->links() }}
            </section>
        </div>
        <table class="data-table table table-striped">
            <thead>
                <th>S.No</th>
                <th>Type</th>
                <th>Expression</th>
            </thead>

            <tbody>
                @foreach ($allowance as $row)
                    <tr class="edit_allowance_rows">
                        <td>{{ $row?->id }}</td>
                        <td class="type" data-id={{ $row?->id }}>{{ $row?->type }}</td>
                        <td class="value" data-id={{ $row?->id }}>{{ $row?->value }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @push('external_scripts')
        <script>
            let allowance_rows = document.querySelectorAll('.edit_allowance_rows td:nth-child(3)');
            Array.from(allowance_rows).forEach(function (row) {
                row.addEventListener('dblclick', function (e) {
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