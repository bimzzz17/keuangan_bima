<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['Gaji', 'income'],
            ['Uang Saku', 'income'],
            ['Bonus / Lainnya', 'income'],
            ['Makan & Minum', 'expense'],
            ['Transportasi', 'expense'],
            ['Belanja', 'expense'],
            ['Tagihan & Pulsa', 'expense'],
            ['Pendidikan', 'expense'],
            ['Hiburan', 'expense'],
            ['Tabungan', 'saving'],
            ['Dana Darurat', 'saving'],
        ];

        foreach ($data as [$name, $type]) {
            Category::updateOrCreate(['name' => $name], ['type' => $type]);
        }
    }
}
