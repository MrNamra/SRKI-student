<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabSchedule extends Model
{
    use HasFactory;
    protected $table = 'lab_schedules';

    protected $fillable = [
        'id',
        'sub_id',
        'div',
        'title',
        'dec',
        'file_path',
        'StartTime',
        'EndTime'
    ];

    public function subject() {
        return $this->belongsTo(Subject::class, 'sub_id');        
    }
    public function assignment() {
        return $this->belongsTo(AssignmentInfo::class, 'assingment_id');        
    }
}
