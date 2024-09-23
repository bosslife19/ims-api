<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "name",
        "school_id",
        "website",
        "email",
        "phone_number",
        "level",
        "logo",
        "address",
        "city",
        "lga",
        "postal_code"
    ];

    public function items($school_id)
    {
        return NewItem::where(['school_id' => $school_id])->paginate(50);
    }
}
