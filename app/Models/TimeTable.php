<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'day',
        'div',
        'course_id',
        'sem',
        'subject_id',
        'StartDate',
        'EndDate',
    ];

    public function course()
    {
        return $this->belongsTo(Cource::class, 'course_id');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
