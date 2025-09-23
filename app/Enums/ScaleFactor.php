<?php

namespace App\Enums;

use App\Enums\Rating;

enum ScaleFactor: string
{
    case Prec = 'PREC';
    case Flex = 'FLEX';
    case Resl = 'RESL';
    case Team = 'TEAM';
    case Pmat = 'PMAT';

    public function label(): string
    {
        return match ($this) {
            self::Prec => 'Pengalaman (Precedentedness)',
            self::Flex => 'Fleksibilitas Pengembangan (Flexibility)',
            self::Resl => 'Resolusi Arsitektur/Risiko (Resolution)',
            self::Team => 'Kohesi Tim (Team Cohesion)',
            self::Pmat => 'Kematangan Proses (Process Maturity)',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Prec => 'Seberapa familiar tim Anda dengan jenis proyek ini?',
            self::Flex => 'Seberapa fleksibel proses pengembangan yang akan diterapkan?',
            self::Resl => 'Sejauh mana arsitektur dan risiko proyek sudah dipahami di awal?',
            self::Team => 'Bagaimana kerjasama tim Anda?',
            self::Pmat => 'Seberapa matang proses pengembangan di organisasi/tim Anda?',
        };
    }

    public function options(): array
    {
        return [Rating::VeryLow, Rating::Low, Rating::Nominal, Rating::High, Rating::VeryHigh];
    }
}