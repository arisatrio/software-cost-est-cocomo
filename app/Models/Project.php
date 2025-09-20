<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'kloc',
        'programming_language',
        'external_inputs',
        'external_outputs', 
        'external_inquiries',
        'internal_files',
        'external_files',
        'complexity_factor',
        'function_points',
        'total_sloc',
        'calculated_effort',
        'calculated_schedule',
        'calculated_personnel'
    ];

    /**
     * Get the factors for the project.
     */
    public function factors(): HasMany
    {
        return $this->hasMany(ProjectFactor::class);
    }
}