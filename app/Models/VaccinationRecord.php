<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccinationRecord extends Model
{
    use HasFactory;

    // Specify the table name (optional, if the table name follows Laravel's naming convention)
    protected $table = 'vaccination_records';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'patient_id',
        'vaccine_name',
        'dose_number',
        'administration_date',
        'scheduled_date',
        'administered_by',
    ];



    // Define relationships (optional, if you want to use them)
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function administeredBy()
    {
        return $this->belongsTo(User::class, 'administered_by');
    }
}
