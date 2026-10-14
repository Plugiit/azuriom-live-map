<?php

namespace Azuriom\Plugin\PlugiitLiveMap\Tests;

use Azuriom\Plugin\PlugiitLiveMap\Support\EmbedTester;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Run from the Azuriom root:
 *   vendor/bin/phpunit plugins/plugiit-live-map/tests
 */
class EmbedTesterTest extends TestCase
{
    protected const SITE = 'https://example.com';

    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }

    public function test_frame_headers_are_analysed(): void
    {
        $tester = new EmbedTester();
        $map = 'https://map.example.com/';

        $this->assertSame([], $tester->frameBlockReasons(null, null, $map, self::SITE));
        $this->assertCount(1, $tester->frameBlockReasons('DENY', null, $map, self::SITE));
        $this->assertCount(1, $tester->frameBlockReasons('SAMEORIGIN', null, $map, self::SITE));
        $this->assertSame([], $tester->frameBlockReasons('SAMEORIGIN', null, self::SITE.'/map/', self::SITE));

        // frame-ancestors takes precedence over X-Frame-Options.
        $this->assertSame([], $tester->frameBlockReasons('DENY', "frame-ancestors 'self' https://example.com", $map, self::SITE));
        $this->assertSame([], $tester->frameBlockReasons(null, 'frame-ancestors *.example.com', $map, 'https://www.example.com'));
        $this->assertSame([], $tester->frameBlockReasons(null, 'default-src *; frame-ancestors https:', $map, self::SITE));
        $this->assertCount(1, $tester->frameBlockReasons(null, "frame-ancestors 'none'", $map, self::SITE));
        $this->assertCount(1, $tester->frameBlockReasons(null, "frame-ancestors 'self'", $map, self::SITE));
    }

    public function test_an_embeddable_map_succeeds(): void
    {
        Http::fake(['map.example.com/*' => Http::response('<html></html>')]);

        $result = (new EmbedTester())->test('https://map.example.com/', self::SITE);

        $this->assertSame('success', $result['status']);
        $this->assertSame(200, $result['http_status']);
    }

    public function test_a_blocked_map_is_reported(): void
    {
        Http::fake(['map.example.com/*' => Http::response('<html></html>', 200, ['X-Frame-Options' => 'SAMEORIGIN'])]);

        $result = (new EmbedTester())->test('https://map.example.com/', self::SITE);

        $this->assertSame('error', $result['status']);
        $this->assertSame('SAMEORIGIN', $result['headers']['X-Frame-Options']);
    }

    public function test_mixed_content_is_a_warning(): void
    {
        Http::fake(['*' => Http::response('<html></html>')]);

        $result = (new EmbedTester())->test('http://play.example.com:8100/', self::SITE);

        $this->assertSame('warning', $result['status']);
    }

    public function test_an_unreachable_map_is_an_error(): void
    {
        Http::fake(['*' => Http::failedConnection()]);

        $result = (new EmbedTester())->test('https://map.example.com/', self::SITE);

        $this->assertSame('error', $result['status']);
        $this->assertNull($result['http_status']);
    }
}
