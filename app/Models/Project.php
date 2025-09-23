<?php
namespace App\Models;

use App\Enums\ProjectCategory;
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
        'project_category',
        'function_points',
        'total_sloc',
        'calculated_effort',
        'calculated_schedule',
        'calculated_personnel',
        // Actual data fields
        'actual_effort',
        'actual_schedule', 
        'actual_personnel',
        'actual_sloc',
        'status',
        'actual_start_date',
        'actual_end_date',
        'actual_notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'kloc' => 'decimal:2',
        'function_points' => 'decimal:2',
        'calculated_effort' => 'decimal:2',
        'calculated_schedule' => 'decimal:2',
        'calculated_personnel' => 'decimal:2',
        'actual_effort' => 'decimal:2',
        'actual_schedule' => 'decimal:2',
        'effort_accuracy' => 'decimal:2',
        'schedule_accuracy' => 'decimal:2',
        'personnel_accuracy' => 'decimal:2',
        'project_category' => ProjectCategory::class,
        'sloc_accuracy' => 'decimal:2',
        'overall_accuracy' => 'decimal:2',
        'actual_start_date' => 'date',
        'actual_end_date' => 'date',
    ];

    /**
     * Get the factors for the project.
     */
    public function factors(): HasMany
    {
        return $this->hasMany(ProjectFactor::class);
    }

    /**
     * Calculate accuracy metrics when actual data is available
     */
    public function calculateAccuracy()
    {
        if (!$this->actual_effort || !$this->actual_schedule || !$this->actual_personnel) {
            return;
        }

        // Calculate MRE (Magnitude of Relative Error) for each metric
        $this->effort_accuracy = $this->calculateMRE($this->calculated_effort, $this->actual_effort);
        $this->schedule_accuracy = $this->calculateMRE($this->calculated_schedule, $this->actual_schedule);
        $this->personnel_accuracy = $this->calculateMRE($this->calculated_personnel, $this->actual_personnel);
        
        if ($this->actual_sloc && $this->total_sloc) {
            $this->sloc_accuracy = $this->calculateMRE($this->total_sloc, $this->actual_sloc);
        }

        // Overall MMRE (Mean MRE of all metrics)
        $mreValues = array_filter([
            $this->effort_accuracy,
            $this->schedule_accuracy, 
            $this->personnel_accuracy,
            $this->sloc_accuracy
        ]);
        
        $this->overall_accuracy = count($mreValues) > 0 ? array_sum($mreValues) / count($mreValues) : null;
        
        $this->save();
    }

    /**
     * Calculate MRE (Magnitude of Relative Error) between estimated and actual values
     * MRE = |Actual - Estimated| / Actual
     * Lower values indicate better accuracy (0 = perfect, 0.25 = good, 0.5+ = poor)
     */
    private function calculateMRE($estimated, $actual)
    {
        if ($actual == 0) return null;
        
        return abs($actual - $estimated) / $actual;
    }

    /**
     * Scope for completed projects with actual data
     */
    public function scopeWithActualData($query)
    {
        return $query->where('status', 'completed')
                     ->whereNotNull('actual_effort')
                     ->whereNotNull('actual_schedule');
    }

    /**
     * Get formatted MRE for display
     */
    public function getFormattedAccuracyAttribute()
    {
        if (!$this->overall_accuracy) {
            return 'N/A';
        }
        
        return number_format($this->overall_accuracy, 3);
    }

    /**
     * Get MRE interpretation for overall accuracy
     */
    public function getMreInterpretationAttribute()
    {
        if (!$this->overall_accuracy) {
            return 'N/A';
        }
        
        return match(true) {
            $this->overall_accuracy <= 0.25 => 'Excellent',
            $this->overall_accuracy <= 0.50 => 'Good', 
            $this->overall_accuracy <= 0.75 => 'Fair',
            default => 'Poor'
        };
    }

    /**
     * Get MRE color class for display
     */
    public function getMreColorClassAttribute()
    {
        if (!$this->overall_accuracy) {
            return 'bg-gray-100 text-gray-800';
        }
        
        return match(true) {
            $this->overall_accuracy <= 0.25 => 'bg-green-100 text-green-800',
            $this->overall_accuracy <= 0.50 => 'bg-yellow-100 text-yellow-800',
            $this->overall_accuracy <= 0.75 => 'bg-orange-100 text-orange-800', 
            default => 'bg-red-100 text-red-800'
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'planning' => 'bg-gray-100 text-gray-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get project category instance
     */
    public function getProjectCategoryAttribute()
    {
        return $this->project_category ?? ProjectCategory::ORGANIC;
    }

    /**
     * Get project category coefficients
     */
    public function getCategoryCoefficientsAttribute()
    {
        $category = $this->project_category ?? ProjectCategory::ORGANIC;
        return $category->coefficients();
    }
}