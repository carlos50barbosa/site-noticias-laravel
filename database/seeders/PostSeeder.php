<?php

namespace Database\Seeders;

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::orderBy('id')->first();

        if (! $author) {
            return;
        }

        $posts = [
            [
                'title' => 'Mercado abre em alta após dados de inflação',
                'category' => 'economia',
                'excerpt' => 'Índices reagem positivamente a números abaixo do esperado.',
                'content' => '<p>Os principais índices da bolsa abriram em alta nesta sessão, impulsionados por dados de inflação abaixo do esperado.</p><p>Analistas avaliam que o cenário reforça a expectativa de manutenção dos juros.</p>',
                'tags' => ['Inflação', 'Bolsa', 'Juros'],
            ],
            [
                'title' => 'Time da casa vence clássico e assume liderança',
                'category' => 'esportes',
                'excerpt' => 'Vitória por 2 a 0 coloca a equipe no topo da tabela.',
                'content' => '<p>Em um clássico disputado, o time da casa venceu por 2 a 0 e assumiu a liderança do campeonato.</p><p>O técnico destacou a evolução defensiva da equipe nas últimas rodadas.</p>',
                'tags' => ['Futebol', 'Campeonato'],
            ],
            [
                'title' => 'Nova geração de chips promete mais eficiência',
                'category' => 'tecnologia',
                'excerpt' => 'Fabricantes apostam em arquitetura focada em consumo de energia.',
                'content' => '<p>A nova geração de chips anunciada esta semana promete ganhos expressivos de eficiência energética.</p><p>A expectativa é de que os primeiros dispositivos cheguem ao mercado no próximo ano.</p>',
                'tags' => ['Hardware', 'Inovação'],
            ],
        ];

        foreach ($posts as $data) {
            $category = Category::where('slug', $data['category'])->first();

            $post = Post::firstOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    'title' => $data['title'],
                    'excerpt' => $data['excerpt'],
                    'content' => $data['content'],
                    'status' => PostStatus::PUBLISHED,
                    'published_at' => now(),
                    'author_id' => $author->id,
                    'category_id' => $category?->id,
                ],
            );

            $tagIds = collect($data['tags'])->map(function (string $name) {
                return Tag::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name],
                )->id;
            });

            $post->tags()->sync($tagIds);
        }
    }
}
