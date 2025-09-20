<?php

namespace App\Enums;

use App\Enums\Rating;

enum CostDriver: string
{
    // Product Attributes
    case Rely = 'RELY';
    case Data = 'DATA';
    case Cplx = 'CPLX';
    case Ruse = 'RUSE';
    case Docu = 'DOCU';

    // Platform Attributes
    case Time = 'TIME';
    case Stor = 'STOR';
    case Pvol = 'PVOL';

    // Personnel Attributes
    case Acap = 'ACAP';
    case Pcap = 'PCAP';
    case Aexp = 'AEXP';
    case Ltex = 'LTEX';
    case Pcon = 'PCON';

    // Project Attributes
    case Tool = 'TOOL';
    case Site = 'SITE';
    case Sced = 'SCED';

    public function label(): string
    {
        return match ($this) {
            self::Rely => 'Keandalan Perangkat Lunak',
            self::Data => 'Ukuran Basis Data',
            self::Cplx => 'Kompleksitas Produk',
            self::Ruse => 'Penggunaan Kembali',
            self::Docu => 'Kecocokan Dokumentasi',
            self::Time => 'Batasan Waktu Eksekusi',
            self::Stor => 'Batasan Penyimpanan Utama',
            self::Pvol => 'Volatilitas Platform',
            self::Acap => 'Kemampuan Analis',
            self::Pcap => 'Kemampuan Programmer',
            self::Aexp => 'Pengalaman Aplikasi',
            self::Ltex => 'Pengalaman Bahasa & Alat',
            self::Pcon => 'Kontinuitas Personel',
            self::Tool => 'Penggunaan Alat Bantu',
            self::Site => 'Pengembangan Multisitus',
            self::Sced => 'Jadwal Pengembangan',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Rely => 'Seberapa tinggi tingkat keandalan yang dibutuhkan dari perangkat lunak?',
            self::Data => 'Seberapa besar ukuran basis data yang akan digunakan?',
            self::Cplx => 'Seberapa kompleks produk yang akan dikembangkan?',
            self::Ruse => 'Seberapa banyak komponen yang akan dikembangkan untuk digunakan kembali di proyek lain?',
            self::Docu => 'Seberapa cocok dokumentasi yang dihasilkan dengan kebutuhan siklus hidup proyek?',
            self::Time => 'Seberapa ketat batasan waktu eksekusi perangkat lunak?',
            self::Stor => 'Seberapa ketat batasan memori utama?',
            self::Pvol => 'Seberapa sering platform pengembangan akan berubah?',
            self::Acap => 'Bagaimana kemampuan analis Anda?',
            self::Pcap => 'Bagaimana kemampuan programmer Anda?',
            self::Aexp => 'Seberapa berpengalaman tim Anda dalam jenis aplikasi ini?',
            self::Ltex => 'Seberapa berpengalaman tim Anda dengan bahasa dan alat yang akan digunakan?',
            self::Pcon => 'Seberapa stabil tim proyek?',
            self::Tool => 'Seberapa banyak alat perangkat lunak yang digunakan?',
            self::Site => 'Apakah tim proyek akan bekerja dari beberapa lokasi yang berbeda?',
            self::Sced => 'Seberapa ketat jadwal pengembangan yang dibutuhkan?',
        };
    }

    public function options(): array
    {
        return match ($this) {
            self::Rely => [Rating::VeryLow, Rating::Low, Rating::Nominal, Rating::High, Rating::VeryHigh],
            self::Data => [Rating::Low, Rating::Nominal, Rating::High, Rating::VeryHigh],
            self::Cplx => [Rating::VeryLow, Rating::Low, Rating::Nominal, Rating::High, Rating::VeryHigh, Rating::ExtraHigh],
            self::Ruse => [Rating::Low, Rating::Nominal, Rating::High, Rating::VeryHigh, Rating::ExtraHigh],
            self::Docu => [Rating::VeryLow, Rating::Low, Rating::Nominal, Rating::High, Rating::VeryHigh],
            self::Time => [Rating::Nominal, Rating::High, Rating::VeryHigh, Rating::ExtraHigh],
            self::Stor => [Rating::Nominal, Rating::High, Rating::VeryHigh, Rating::ExtraHigh],
            self::Pvol => [Rating::Low, Rating::Nominal, Rating::High, Rating::VeryHigh],
            self::Acap => [Rating::VeryLow, Rating::Low, Rating::Nominal, Rating::High, Rating::VeryHigh],
            self::Pcap => [Rating::VeryLow, Rating::Low, Rating::Nominal, Rating::High, Rating::VeryHigh],
            self::Aexp => [Rating::VeryLow, Rating::Low, Rating::Nominal, Rating::High, Rating::VeryHigh],
            self::Ltex => [Rating::VeryLow, Rating::Low, Rating::Nominal, Rating::High, Rating::VeryHigh],
            self::Pcon => [Rating::VeryLow, Rating::Low, Rating::Nominal, Rating::High, Rating::VeryHigh],
            self::Tool => [Rating::VeryLow, Rating::Low, Rating::Nominal, Rating::High, Rating::VeryHigh, Rating::ExtraHigh],
            self::Site => [Rating::VeryLow, Rating::Low, Rating::Nominal, Rating::High, Rating::VeryHigh],
            self::Sced => [Rating::VeryLow, Rating::Low, Rating::Nominal, Rating::High, Rating::VeryHigh],
        };
    }
}