<?php
/**
 * A HTTP2 Server Push Middleware for Laravel 5
 *
 * @author Jacob Bennett
 * @copyright Copyright (c) 2016 Jacob Bennett
 * @license MIT License
 *
 * @link https://github.com/JacobBennett/laravel-HTTP2ServerPush
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\DomCrawler\Crawler;

class ServerPushMiddleware
{
    /**
     * The DomCrawler instance.
     *
     * @var \Symfony\Component\DomCrawler\Crawler
     */
    protected $crawler;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response->isRedirection() || !$response instanceof Response || $request->isJson()) {
            return $response;
        }

        $this->generateAndAttachLinkHeaders($response);

        return $response;
    }

    /**
     * @param \Illuminate\Http\Response $response
     *
     * @return $this
     */
    protected function generateAndAttachLinkHeaders(Response $response)
    {
        $headers = $this->fetchLinkableNodes($response)
            ->flatten(1)
            ->filter(function ($url) {
                return strpos($url, '/') === 0;
            })
            ->map(function ($url) {
                return $this->buildLinkHeaderString($url);
            })
            ->implode(',');

        if (!empty(trim($headers))) {
            $this->addLinkHeader($response, $headers);
        }

        return $this;
    }

    /**
     * Get the DomCrawler instance.
     *
     * @param \Illuminate\Http\Response $response
     *
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function getCrawler(Response $response)
    {
        if ($this->crawler) {
            return $this->crawler;
        }

        return $this->crawler = new Crawler($response->getContent());
    }

    /**
     * Get all nodes we are interested in pushing.
     *
     * @param \Illuminate\Http\Response $response
     *
     * @return \Illuminate\Support\Collection
     */
    protected function fetchLinkableNodes($response)
    {
        $crawler = $this->getCrawler($response);

        return collect($crawler->filter('link, script[src], img[src]')->extract(['src', 'href']));
    }

    /**
     * Build out header string based on asset extension.
     *
     * @param string $url
     *
     * @return string
     */
    private function buildLinkHeaderString($url)
    {
        $linkTypeMap = [
            '.CSS'  => 'style',
            '.JS'   => 'script',
        ];

        $type = collect($linkTypeMap)->first(function ($type, $extension) use ($url) {
            return str_contains(strtoupper($url), $extension);
        });

        return is_null($type) ? null : "<{$url}>; rel=preload; as={$type}";
    }

    /**
     * Add Link Header
     *
     * @param \Illuminate\Http\Response $response
     *
     * @param $link
     */
    private function addLinkHeader(Response $response, $link)
    {
        if ($response->headers->get('Link')) {
            $link = $response->headers->get('Link') . ',' . $link;
        }

        $response->header('Link', $link);
    }
}