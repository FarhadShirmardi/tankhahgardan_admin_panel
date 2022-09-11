<div>
    <div class="input-group">
        <input
            type="text"
            class="form-control"
            placeholder="جست و جوی کاربر"
            wire:model="query"
            wire:keydown.escape="reset"
            wire:keydown.tab="reset"
            wire:keydown.arrow-up="decrementHighlight"
            wire:keydown.arrow-down="incrementHighlight"
            wire:keydown.enter="selectContact"
        />
    </div>
    <div wire:loading class="pr-0 list-item" style="position: absolute">
        <div class="list-item">در حال جست و جو</div>
    </div>
    @if(!empty($query))
        <div class="pr-0" style="position: absolute" wire:loading.remove>
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
