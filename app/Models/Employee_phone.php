<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee_phone extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=['number','employee_id'];
}
