<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrinterCommand extends Model
{
    protected $table = 'printer_commands';

    protected $fillable = [
        'data',
        'printer_type',
        'printer_name',
        'branch_id',
        'user_id',
    ];

    // Define relationships if needed
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
