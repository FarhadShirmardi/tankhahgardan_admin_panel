@php
    if (session('message'))
        $message = session('message');
@endphp
<div class="alert alert-danger" style="display: {{ isset($message) ? 'block' : 'none' }}">
    @if($message ?? null)
        {!! $message !!}
        @if (session('status'))
            @php
                $status = session('status');
            @endphp
        @endif
    @endif
</div>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
