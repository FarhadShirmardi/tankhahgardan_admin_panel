<div>
    <input
        type="text"
        class="form-control"
        placeholder="Search Contacts..."
        wire:model="query"
        wire:keydown.escape="reset"
        wire:keydown.tab="reset"
        wire:keydown.arrow-up="decrementHighlight"
        wire:keydown.arrow-down="incrementHighlight"
        wire:keydown.enter="selectContact"
    />
    <div wire:loading class="absolute z-10 w-full bg-white rounded-t-none shadow-lg list-group">
        <div class="list-item">Searching...</div>
    </div>
    @if(!empty($query))
        <div class="fixed top-0 bottom-0 left-0 right-0" wire:click="reset"></div>

        <div class="absolute z-10 w-full bg-white rounded-t-none shadow-lg list-group">
            @if(!empty($users))
                @foreach($users as $i => $user)
                    <a
                        href="{{ route('dashboard.report.userActivity', ['id' => $user->id]) }}"
                        class="list-group-item {{ $highlightIndex === $i ? 'highlight' : '' }}"
                    >{{ $user->full_name . ' - ' . $user->phone_number }}</a>
                @endforeach
            @else
                <div class="list-item">No results!</div>
            @endif
        </div>
    @endif
</div>
