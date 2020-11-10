<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product_order extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=['purchase_id','quantity','product_id','cost'];
}
