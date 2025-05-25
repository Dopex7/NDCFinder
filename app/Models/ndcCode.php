<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ndcCode extends Model
{
    protected $fillable = [
        'ndc_code', 'brand_name', 'generic_name', 'labeler_name', 'product_type'
    ];
}
