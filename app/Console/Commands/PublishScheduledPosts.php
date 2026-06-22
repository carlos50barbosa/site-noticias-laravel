<?php

namespace App\Console\Commands;

use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Console\Command;

class PublishScheduledPosts extends Command
{
    protected $signature = 'posts:publish-scheduled';

    protected $description = 'Publica as notícias agendadas cujo horário já venceu.';

    public function handle(): int
    {
        $count = Post::where('status', PostStatus::SCHEDULED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->update(['status' => PostStatus::PUBLISHED]);

        $this->info("Notícias publicadas: {$count}");

        return self::SUCCESS;
    }
}
