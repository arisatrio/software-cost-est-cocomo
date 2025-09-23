<?php

namespace App\Enums;

enum ProjectCategory: string
{
    case ORGANIC = 'organic';
    case SEMI_DETACHED = 'semi_detached';
    case EMBEDDED = 'embedded';

    /**
     * Get human readable label
     */
    public function label(): string
    {
        return match($this) {
            self::ORGANIC => 'Organik',
            self::SEMI_DETACHED => 'Semi-Detached',
            self::EMBEDDED => 'Embedded',
        };
    }

    /**
     * Get detailed description
     */
    public function description(): string
    {
        return match($this) {
            self::ORGANIC => 'Proyek kecil dengan tim berpengalaman nominal yang bekerja di lingkungan familiar',
            self::SEMI_DETACHED => 'Proyek menengah dengan tim berpengalaman campuran dan beberapa batasan',
            self::EMBEDDED => 'Proyek besar dan kompleks dengan batasan ketat dan persyaratan real-time',
        };
    }

    /**
     * Get project characteristics
     */
    public function characteristics(): array
    {
        return match($this) {
            self::ORGANIC => [
                'Ukuran tim: 2-8 orang',
                'Persyaratan: Dipahami dengan baik dan stabil',
                'Pengalaman: Tim berpengalaman nominal',
                'Lingkungan: Familiar dan stabil',
                'Contoh: Sistem bisnis, pengolahan data'
            ],
            self::SEMI_DETACHED => [
                'Ukuran tim: 8-30 orang',
                'Persyaratan: Campuran familiar dan tidak familiar',
                'Pengalaman: Tim berpengalaman campuran',
                'Lingkungan: Beberapa batasan baru',
                'Contoh: Compiler, sistem database'
            ],
            self::EMBEDDED => [
                'Ukuran tim: 15-300+ orang',
                'Persyaratan: Kompleks, berubah, belum pernah ada',
                'Pengalaman: Campuran, bekerja di area baru',
                'Lingkungan: Batasan ketat, real-time',
                'Contoh: Kontrol penerbangan, perangkat medis'
            ],
        };
    }

    /**
     * Get COCOMO II coefficients for effort and schedule calculation
     */
    public function coefficients(): array
    {
        return match($this) {
            self::ORGANIC => [
                'A' => 2.94,  // Effort coefficient
                'B' => 0.91,  // Effort exponent (will be adjusted by scale factors)
                'C' => 3.67,  // Schedule coefficient  
                'D' => 0.28   // Schedule exponent
            ],
            self::SEMI_DETACHED => [
                'A' => 3.67,
                'B' => 1.04,
                'C' => 3.21,
                'D' => 0.36
            ],
            self::EMBEDDED => [
                'A' => 4.24,
                'B' => 1.10,
                'C' => 2.80,
                'D' => 0.45
            ]
        };
    }

    /**
     * Get all project categories as array for dropdowns
     */
    public static function options(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
            'description' => $case->description(),
            'characteristics' => $case->characteristics()
        ], self::cases());
    }

    /**
     * Get CSS color class for UI display
     */
    public function colorClass(): string
    {
        return match($this) {
            self::ORGANIC => 'bg-green-100 text-green-800',
            self::SEMI_DETACHED => 'bg-blue-100 text-blue-800',
            self::EMBEDDED => 'bg-red-100 text-red-800',
        };
    }
}