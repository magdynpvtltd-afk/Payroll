@php
    $issuerName = $issuer?->user_name ?? $issuer?->email ?? 'Authorized Signatory';
@endphp

<x-layouts.document-print-single-page pageTitle="Experience letter"
    :backUrl="route('documents.experience-letters.index')">
    @include('documents.partials.letterhead', ['company' => $company, 'title' => 'Experience Letter'])

    <div class="doc-body">
        <div class="doc-confirmation-letter">
            <p class="doc-meta">
                {{ $letter->issued_date?->format('F j, Y') ?? now()->format('F j, Y') }}<br>
                Chennai - 96
            </p>

            <p class="line-center">To whom It May Concern</p>
            <p>This is to certify that Mr.R. Saravanan was working with us from 12-Sep-2025 to 01-Apr-2026. During his
                tenure with us we found him satisfactory in commitment and performance.</p>
            <br><br>
            <p>For {{$company['name']}}
            </p><br><br>
            <img src="{{asset('images/director_sign.png')}}" alt="" witdh="100" height=50>
            <p>Suresh Kumar</p>
        </div>

    </div>
    @include('documents.partials.letterfooter')
</x-layouts.document-print-single-page>