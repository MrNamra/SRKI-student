<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentInfo extends Model
{
    use HasFactory;
    protected $table = 'assignment_info';

    protected $fillable = [
        'en_no',
        'assingment_id',
        'file_path',
    ];

    public function students(){
        return $this->hasMany(Student::class, 'enrollment_no', 'en_no');
    }
}
