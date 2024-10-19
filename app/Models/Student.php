<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'enrollment_no',
        'ip',
        'name',
        'course_id',
        'sem',
        'div',
    ];

    public function course()
    {
        return $this->belongsTo(Cource::class, 'course_id');
    }
}
