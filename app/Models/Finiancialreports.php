<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finiancialreports extends Model
{
    use HasFactory;
    protected $fillable = [
  'charities_id',
  'donation_id',
  'date',
  'description',
];
}
