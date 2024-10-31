<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'en_no',
        'exam_id',
        'file_path',
        'marks',
        'ip',
    ];
}
