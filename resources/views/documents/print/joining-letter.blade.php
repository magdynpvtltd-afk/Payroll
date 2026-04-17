<x-layouts.document-print-single-page
    pageTitle="Appointment letter"
    :backUrl="route('documents.joining-letters.index')"
>
    @include('documents.partials.letterhead', ['company' => $company , 'title' => 'Appointment Letter'])


    <div class="doc-body">
        <div class="doc-confirmation-letter">
            
        <p class="doc-meta">
            {{ $letter->issued_date?->format('F j, Y') ?? now()->format('F j, Y') }}<br>
        </p>

        <p>Dear <strong>{{ $employee?->full_name ?? 'Colleague' }}</strong>,</p>

        <p>Based on your performance, the management is pleased to inform you that you have been confirmed on the rolls of Magneto Dynamics w.e.f <strong>{{ $letter?->joining_date?->format('d-m-y') }}</strong>. The salary remains the same as given in the offer letter at the time of joining.</p>
        <p><br><br>For {{$company['name']}} 
            </p><br><br>
        <img src="{{asset('images/director_sign.png')}}" alt="" witdh=10 height=50>
        <p>Suresh Kumar</p>
        </div>
    </div>
    @include('documents.partials.letterfooter')
</x-layouts.document-print-single-page>
