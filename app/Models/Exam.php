<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_id',
        'course_id',
        'div',
        'title',
        'examtype',
        'dec',
        'file_path',
        'StartTime',
        'EndTime',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'sub_id');
    }
    public function course()
    {
        return $this->belongsTo(cource::class, 'course_id');
    }
    public function examinfo(){
        return $this->hasMany(ExamInfo::class, 'exam_id');
    }
}
