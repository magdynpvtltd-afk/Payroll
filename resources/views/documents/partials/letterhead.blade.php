@props(['title' => null])
<div class="doc-letterhead">
    <h1> {{ $title }}</h1>
    @if (! empty($company['logo_path']))
        <img src="{{ asset($company['logo_path']) }}" alt="" class="doc-letterhead__logo">
    @endif
</div>
