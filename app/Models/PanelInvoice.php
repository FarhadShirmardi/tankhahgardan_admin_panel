<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanelInvoice extends Model
{
    protected $connection = 'mysql_panel';
    protected $table = 'invoices';

    protected $fillable = [
        'status',
    ];
}
