<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $table = 'subjects';

    protected $fillable = [
        'subject_code',
        'cource_id',
        'name',
        'sem',
    ];

    public function course()
    {
        return $this->belongsTo(Cource::class, 'cource_id');
    }
}