<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FecultyAssignment extends Model
{
    use HasFactory;
    protected $table = 'feculty_assignments';

    protected $fillebal = [
        'lab_id',
        'path',
        'title',
        'assignment_no',
    ];
}
