<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new SiswaSheet(),
            new GuruSheet(),
        ];
    }
}

class SiswaSheet implements WithTitle, WithHeadings
{
    public function title(): string
    {
        return 'Siswa';
    }

    public function headings(): array
    {
        return [
            'NISN',
            'Nama Lengkap',
            'Kelas', // Contoh: X RPL 1
        ];
    }
}

class GuruSheet implements WithTitle, WithHeadings
{
    public function title(): string
    {
        return 'Guru';
    }

    public function headings(): array
    {
        return [
            'NIP',
            'Nama Lengkap',
        ];
    }
}
