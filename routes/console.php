<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Publica notícias agendadas vencidas (acionado pelo cron do cPanel via schedule:run).
Schedule::command('posts:publish-scheduled')
    ->everyMinute()
    ->withoutOverlapping();
