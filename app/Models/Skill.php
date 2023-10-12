<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;
    protected $fillable = [
        'skill'
    ];
    public function applicants(){
        return $this->belongsToMany(Applicant::class, 'applicant_skills');
    }
    
}
