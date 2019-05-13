<?php

namespace App\Http\Middleware;

use Closure;

class SiteCacheMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $url = $request->fullUrl();

        // Check url length
        if (mb_strlen($url) > 256) {
            abort(404);
        }

        // Set key
        $urlArr = explode('?', $url);
        if (isset($urlArr['1'])) {
            if ($request->routeIs(['home', 'term.cat.show', 'term.tag.show'])) {
                $pageQuery = $request->query('page');
                if ($pageQuery !== null && $pageQuery > 0) {
                    $pageQuery = (int)$pageQuery;
                    $pageQuery = $pageQuery > 100 ? 100 : $pageQuery;

                    $key = "{$urlArr['0']}?page={$pageQuery}";
                } else {
                    $key = $urlArr['0'];
                }
            } else {
                $key = $urlArr['0'];
            }
        } else {
            $key = $url;
        }

        $key = rtrim($key, '/');

        $cachedPage = app('cache')->get($key);

        // Return cached page
        if ($cachedPage !== null) {
            // Content-Type: text/xml
            if ($request->routeIs(['site.sitemap', 'site.bingsiteauth'])) {
                return response($cachedPage)->header('Content-Type', 'text/xml');
            } else {
                return response($cachedPage);
            }
        }

        $response = $next($request);

        if ($response->status() == 200) {
            // Cache page
            $pageContent = $response->getContent();
            app('cache')->forever($key, $pageContent);
        }

        return $response;
    }
}
