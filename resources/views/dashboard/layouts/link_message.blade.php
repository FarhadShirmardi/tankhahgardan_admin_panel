@php
    if (session('link'))
        $link = session('link');
@endphp
@if($link ?? null)
    <div class="alert alert-info">
        <a target="_blank" href="{{ $link }}">
            <button>دانلود فایل</button>
        </a>
    </div>
@endif
