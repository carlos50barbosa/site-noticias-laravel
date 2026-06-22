<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Support\Html;
use Tests\TestCase;

class FormatTest extends TestCase
{
    public function test_strip_removes_tags_and_collapses_whitespace(): void
    {
        $this->assertSame('Olá mundo', Html::strip('<p>Olá   <strong>mundo</strong></p>'));
    }

    public function test_excerpt_uses_explicit_value(): void
    {
        $post = new Post(['excerpt' => 'Resumo curto', 'content' => '<p>conteúdo bem mais longo</p>']);

        $this->assertSame('Resumo curto', $post->excerptText());
    }

    public function test_excerpt_falls_back_to_stripped_content(): void
    {
        $post = new Post(['excerpt' => null, 'content' => '<p>'.str_repeat('a', 200).'</p>']);

        $out = $post->excerptText(160);

        $this->assertStringEndsWith('…', $out);
        $this->assertLessThanOrEqual(161, mb_strlen($out));
    }
}
