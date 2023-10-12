<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        'description',
        'department',
        'experience',
        'first_name',
        'last_name',
        'phone',
        'email',
        'resume',
        'employer',
        'position',
        'applied',
        'interview',
        'interviewer',
        'score',
        'status',
        'notes',
    ];

    public function skills(){
        return $this->belongsToMany(Skill::class, 'applicant_skills');
    }
}
