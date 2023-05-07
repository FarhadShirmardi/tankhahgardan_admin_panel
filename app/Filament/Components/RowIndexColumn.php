<?php

namespace App\Filament\Components;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Collection;
use stdClass;

class RowIndexColumn
{
    public static function make(string $columnName = null)
    {
        return TextColumn::make($columnName ?: __('names.table.row index'))
            ->getStateUsing(
                static function (stdClass $rowLoop, HasTable $livewire) : string {
                    $records = $livewire->getTableRecords();
                    if ($records instanceof Collection) {
                        return (string) $rowLoop->iteration;
                    } else {
                        return (string) (
                            $rowLoop->iteration + ($livewire->tableRecordsPerPage * ($records->currentPage() - 1))
                        );
                    }
                }
            );
    }
}