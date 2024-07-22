<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relief extends Model
{
    use HasFactory;
    protected $fillable = ['home', 'housefurniture', 'food', 'clothes', 'money', 'psychologicalaid'];

    public function beneficiaries()
    {
        return $this->belongsToMany(Beneficiary::class, 'beneficiaries__reliefs')
                    ->withPivot('charities_id', 'active', 'status')
                    ->withTimestamps();
    }
}
