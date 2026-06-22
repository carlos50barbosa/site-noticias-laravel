<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Política', 'slug' => 'politica', 'description' => 'Cobertura política nacional e internacional.'],
            ['name' => 'Economia', 'slug' => 'economia', 'description' => 'Mercado, finanças e negócios.'],
            ['name' => 'Esportes', 'slug' => 'esportes', 'description' => 'Notícias do mundo esportivo.'],
            ['name' => 'Tecnologia', 'slug' => 'tecnologia', 'description' => 'Inovação, gadgets e ciência.'],
            ['name' => 'Cultura', 'slug' => 'cultura', 'description' => 'Arte, cinema, música e entretenimento.'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name'], 'description' => $category['description']],
            );
        }
    }
}
