@php
    if (session('success'))
        $success = session('success');
@endphp
<div class="alert alert-success"
     style="display: {{ isset($success) ? 'block' : 'none' }}; margin-left: 70px;">
    @if($success ?? null)
        {!! $success !!}
    @endif
</div>
