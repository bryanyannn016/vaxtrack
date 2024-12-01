<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'sex',
        'birthday',
        'age',
        'email',
        'address',
        'medical_history',
        'created_by',
    ];

    /**
     * Define the relationship to the creator (user).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function vaccinationRecords()
{
    return $this->hasMany(VaccinationRecord::class);
}
}
