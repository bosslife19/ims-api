<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discrepancy extends Model
{
    use HasFactory;

    protected $fillable = [
        "report_id",
        "reporter",
        "item_name",
        "supplier",
        "expected_quantity",
        "actual_quantity",
        "discrepancy_type",
        "description",
        "date"
    ];
}
