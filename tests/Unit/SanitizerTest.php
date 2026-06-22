<?php

namespace Tests\Unit;

use App\Support\Sanitizer;
use PHPUnit\Framework\TestCase;

class SanitizerTest extends TestCase
{
    public function test_removes_script_tags(): void
    {
        $out = Sanitizer::clean('<p>ok</p><script>alert(1)</script>');

        $this->assertStringContainsString('ok', $out);
        $this->assertStringNotContainsString('<script', $out);
    }

    public function test_removes_inline_event_handlers(): void
    {
        $out = Sanitizer::clean('<img src="https://x.test/a.png" onerror="alert(1)">');

        $this->assertStringNotContainsString('onerror', $out);
    }

    public function test_removes_javascript_protocol(): void
    {
        $out = Sanitizer::clean('<a href="javascript:alert(1)">x</a>');

        $this->assertStringNotContainsString('javascript:', $out);
    }

    public function test_keeps_safe_formatting(): void
    {
        $out = Sanitizer::clean('<p><strong>a</strong> <em>b</em></p>');

        $this->assertStringContainsString('<strong>', $out);
        $this->assertStringContainsString('<em>', $out);
    }

    public function test_keeps_youtube_iframe(): void
    {
        $out = Sanitizer::clean('<iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"></iframe>');

        $this->assertStringContainsString('youtube.com/embed/dQw4w9WgXcQ', $out);
    }

    public function test_strips_non_youtube_iframe(): void
    {
        $out = Sanitizer::clean('<iframe src="https://evil.test/frame"></iframe>');

        $this->assertStringNotContainsString('evil.test', $out);
    }
}
