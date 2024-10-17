<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabSchedule extends Model
{
    use HasFactory;
    protected $table = 'lab_schedules';

    protected $fillebal = [
        'sub_id',
        'div',
        'date',
        'StartTime',
        'EndTime'
    ];
}
