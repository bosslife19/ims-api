<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllSchools extends Model
{
    use HasFactory;
   

    protected $casts = [
        'LGA' => 'string',
    ];

    public function newItems(){
        $this->hasMany(NewItem::class, 'all_school_ids');
    }
}
