<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'students';
    protected $keyType = 'string';
    protected $primaryKey = 'enrollment_no';
    public $incrementing = false;

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
