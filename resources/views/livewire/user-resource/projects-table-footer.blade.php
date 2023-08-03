<tr class="filament-tables-row transition">
    @foreach($footer_columns as $column)
        <td class="filament-tables-cell bg-blue-200">
            <div class="filament-tables-text-column px-4 py-3">
                <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                <span class="">{{ $column }}</span>
                </div>
            </div>
        </td>
    @endforeach
    <td></td>
</tr>
