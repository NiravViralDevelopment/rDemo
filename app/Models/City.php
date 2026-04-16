<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'city_management';

    protected $fillable = [
        'id',
        'countries_id',
        'state_id',
        'city_name'        
    ];

    public function countries()
    {
        return $this->belongsTo(Countries::class);
    }
    
    public function state()
    {
        return $this->belongsTo(Cities::class);
    }
}
