<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Life_hood extends Model
{
    use HasFactory;
    protected $fillable = [
        'learningaprofession',
        'gainmoreexperienceinspecificfield',
        'typeofworkthatyouwanttogain',
        'jobapportunity'
    ];

    public function beneficiaries()
    {
        return $this->belongsToMany(Beneficiary::class, 'beneficiaries__lifehoods')
                    ->withPivot('charities_id', 'status', 'active')
                    ->withTimestamps();
    }
}
