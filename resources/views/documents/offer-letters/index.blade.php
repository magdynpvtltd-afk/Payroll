<x-layouts.app title="Offer letters">
    <div class="doc-page-head">
        <p>Saved offer letters with preview and print.</p>
        <a class="doc-btn" href="{{ route('documents.offer-letters.create') }}">New offer letter</a>
    </div>

    <section class="app-panel">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Issued</th>
                        <th>Employee</th>
                        <th>Employee Code</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Offered salary</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="offer-letter-table">
                    @forelse ($letters as $row)
                        <tr>
                            <td>{{ $row->issued_date?->format('Y-m-d') }}</td>
                            <td>{{ $row->employee?->full_name }}</td>
                            <td>{{ $row->employee?->employee_code }}</td>
                            <td class="cell-muted">{{ $row->employee?->department?->name ?? '—' }}</td>
                            <td class="cell-muted">{{ $row->employee?->designation?->title ?? '—' }}</td>
                            <td class="cell-muted">
                                @if ($row->employee?->latestSalaryStructure->ctc !== null)
                                    {{ number_format((float) $row->employee?->latestSalaryStructure->ctc, 2) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <a class="offer-letter-icons" href="{{ route('documents.offer-letters.preview', $row) }}"><i class="fa-solid fa-file-arrow-up "></i></a>
                                <a class="offer-letter-icons" href="{{ route('documents.offer-letters.delete' , $row['id']) }}"><i class="fa-solid fa-trash"></i></a>
                                <!-- <a class="offer-letter-icons" href="#"><i class="fa fa-envelope"></i></a> -->
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"><p class="empty-state">No offer letters yet.</p></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">{{ $letters->links() }}</div>
    </section>
</x-layouts.app>
