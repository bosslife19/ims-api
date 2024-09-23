<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "barcode_id",
        "item_code",
        "item_name",
        "class",
        "subject_category",
        "distribution",
        "quantity",
        "category",
        "image",
        "start_location",
        "current_location"
    ];

}
