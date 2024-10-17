<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentInfo extends Model
{
    use HasFactory;
    protected $table = 'assignment_info';

    protected $fillebal = [
        'en_no',
        'assingment_id',
    ];
}
