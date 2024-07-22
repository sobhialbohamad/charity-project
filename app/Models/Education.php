<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;
    protected $fillable = ['typeofeducation', 'clothes', 'booksandpens', 'courses', 'bags'];
    public function beneficiaries()
   {
       return $this->belongsToMany(Beneficiary::class, 'beneficiaries_educations')
                   ->withPivot('charities_id', 'active', 'status')
                   ->withTimestamps();
   }
}
