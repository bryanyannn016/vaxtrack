<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccineStock extends Model
{
    use HasFactory;

    // Ensure the table name matches your actual table in the database
    protected $table = 'vaccine_stocks';

    protected $primaryKey = 'stock_id';

    public $incrementing = true; // Set to false if 'stock_id' is not auto-incrementing

    // Specify the columns that are mass assignable
    protected $fillable = ['vaccine_name', 'current_quantity', 'last_updated_by'];
}
