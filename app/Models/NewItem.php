<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewItem extends Model
{
    use HasFactory;

    protected $guarded =[];
    public $timestamps = false;

    public function school(){
        return $this->belongsTo(AllSchools::class, 'all_school_ids');
    }
}
