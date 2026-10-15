<?php

namespace Azuriom\Plugin\LiveMap\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

/**
 * Checks from the server side whether a web map answers and may be embedded in an iframe.
 */
class EmbedTester
{
    /**
     * @return array{status: string, http_status: int|null, messages: string[], headers: array<string, string|null>}
     */
    public function test(string $url, ?string $siteUrl = null): array
    {
        $siteUrl ??= url('/');
        $messages = [];

        if (! in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https'], true)) {
            return $this->result('error', null, [trans('live-map::admin.test.invalid_url')]);
        }

        try {
            $response = Http::timeout(8)
                ->connectTimeout(5)
                ->withUserAgent('Azuriom Plugiit Live Map (+'.$siteUrl.')')
                ->withOptions(['allow_redirects' => ['max' => 5]])
                ->get($url);
        } catch (Throwable $e) {
            return $this->result('error', null, [trans('live-map::admin.test.unreachable', [
                'error' => Str::limit($e->getMessage(), 150),
            ])]);
        }

        $status = 'success';
        $frameOptions = $response->header('X-Frame-Options') ?: null;
        $csp = $response->header('Content-Security-Policy') ?: null;

        if (! $response->successful()) {
            $status = 'error';
            $messages[] = trans('live-map::admin.test.http_error', ['status' => $response->status()]);
        } else {
            $messages[] = trans('live-map::admin.test.reachable', ['status' => $response->status()]);
        }

        $blockReasons = $this->frameBlockReasons($frameOptions, $csp, $url, $siteUrl);

        if ($blockReasons !== []) {
            $status = 'error';
            array_push($messages, ...$blockReasons);
            $messages[] = trans('live-map::admin.test.how_to_fix', [
                'site' => $this->origin($siteUrl),
            ]);
        } elseif ($response->successful()) {
            $messages[] = trans('live-map::admin.test.embeddable');
        }

        if (Str::startsWith($siteUrl, 'https://') && Str::startsWith($url, 'http://')) {
            $status = $status === 'error' ? 'error' : 'warning';
            $messages[] = trans('live-map::admin.test.mixed_content');
        }

        return $this->result($status, $response->status(), $messages, [
            'X-Frame-Options' => $frameOptions,
            'Content-Security-Policy' => $csp,
        ]);
    }

    /**
     * Get the reasons why the given headers prevent embedding on the site.
     *
     * @return string[]
     */
    public function frameBlockReasons(?string $frameOptions, ?string $csp, string $mapUrl, string $siteUrl): array
    {
        $reasons = [];
        $sameOrigin = $this->origin($mapUrl) === $this->origin($siteUrl);

        if ($csp !== null && preg_match('/frame-ancestors\s+([^;]*)/i', $csp, $matches)) {
            if (! $this->ancestorsAllow(trim($matches[1]), $siteUrl, $sameOrigin)) {
                $reasons[] = trans('live-map::admin.test.csp_blocked', ['value' => trim($matches[1])]);
            }

            // When frame-ancestors is present, browsers ignore X-Frame-Options.
            return $reasons;
        }

        if ($frameOptions !== null) {
            $value = strtoupper(trim($frameOptions));

            if ($value === 'DENY' || ($value === 'SAMEORIGIN' && ! $sameOrigin) || str_starts_with($value, 'ALLOW-FROM')) {
                $reasons[] = trans('live-map::admin.test.xfo_blocked', ['value' => $frameOptions]);
            }
        }

        return $reasons;
    }

    protected function ancestorsAllow(string $sources, string $siteUrl, bool $sameOrigin): bool
    {
        $siteHost = (string) parse_url($siteUrl, PHP_URL_HOST);
        $siteScheme = (string) parse_url($siteUrl, PHP_URL_SCHEME);

        foreach (preg_split('/\s+/', $sources) as $source) {
            $source = strtolower(trim($source));

            if ($source === '' || $source === "'none'") {
                continue;
            }

            if ($source === '*' || ($source === "'self'" && $sameOrigin) || $source === $siteScheme.':') {
                return true;
            }

            $host = parse_url(str_contains($source, '://') ? $source : 'scheme://'.$source, PHP_URL_HOST);

            if ($host === null || $host === false) {
                continue;
            }

            if ($host === $siteHost || (str_starts_with($host, '*.') && str_ends_with($siteHost, substr($host, 1)))) {
                return true;
            }
        }

        return false;
    }

    protected function origin(string $url): string
    {
        $parts = parse_url($url);
        $port = isset($parts['port']) ? ':'.$parts['port'] : '';

        return strtolower(($parts['scheme'] ?? 'http').'://'.($parts['host'] ?? '').$port);
    }

    protected function result(string $status, ?int $httpStatus, array $messages, array $headers = []): array
    {
        return [
            'status' => $status,
            'http_status' => $httpStatus,
            'messages' => $messages,
            'headers' => $headers,
        ];
    }
}
