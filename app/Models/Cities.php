<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    use HasFactory;
    protected $table = 'cities';

    protected $fillable = [
        'id',
        'countries_id',
        'city_name',
        'created_at',
        'updated_at'
    ];
    
    public function countries()
    {
        return $this->belongsTo(Countries::class);
    }
    
}
