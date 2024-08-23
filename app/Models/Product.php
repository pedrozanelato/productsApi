<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    
   use HasFactory;
    protected $fillable = ['name', 'description', 'price', 'status', 'stock_quantity'];

    public static $rules = [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'status' => 'required|in:em estoque,em reposição,em falta',
        'stock_quantity' => 'required|integer',
    ];
}
