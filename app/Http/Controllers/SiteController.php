<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepository;
use App\Repositories\TermRepository;
use App\Repositories\ConfigRepository;
use App\Http\Middleware\SiteCacheMiddleware;

class SiteController extends Controller
{
    /**
     * SiteController constructor.
     */
    public function __construct()
    {
        $this->middleware(SiteCacheMiddleware::class)->except([
            'backend', 'search'
        ]);

        // View shared data
        try {
            view()->share('config', ConfigRepository::getAllByCache());
        } catch (\Exception $e) {}
    }

    /**
     * Backend
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function backend()
    {
        return view('backend');
    }

    /**
     * Index, welcome
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function welcome()
    {
        $posts = PostRepository::getSitePostsPaginate();

        if (request()->all() === []) {
            $allowIndex = true;
        } else {
            $allowIndex = false;
        }

        return view('welcome', compact('posts', 'allowIndex'));
    }

    /**
     * Site posts by search
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search()
    {
        $s = request()->input('s', '');

        $s = trim($s);
        if ($s == '') {
            return redirect()->route('home');
        }

        if (mb_strlen($s) > 32) {
            return abort(404);
        }

        $fields = ['title', 'content', 'slug'];
        $search = explode(':', $s);
        if (isset($search['1']) && in_array($search['0'], $fields)) {
            $column = $search['0'];
            $search = $search['1'];
        } else {
            $column = null;
            $search = $search['0'];
        }

        $heading = 'Search: ' . $s;

        $posts = PostRepository::getSitePostsPaginateBySearch($search, $column);

        $posts->appends(['s' => $s]);

        return view('welcome', compact('heading', 'posts'));
    }

    /**
     * Sitemap
     */
    public function sitemap()
    {
        $posts = PostRepository::getSiteAllPosts();

        $output = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $output .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        foreach ($posts as $post) {
            $output .= "\t<url>\n\t\t<loc>" . route('post.show', ['slug' => $post->slug]) . "</loc>\n\t\t<lastmod>" . date('Y-m-d', strtotime($post->created_at)) . "</lastmod>\n\t</url>\n";
        }

        $output .= "</urlset>";

        return response($output)->header('Content-Type', 'text/xml');
    }

    /**
     * Bing Site Auth
     */
    public function bingSiteAuth()
    {
        $id = ConfigRepository::getByKey('site_bing_auth');

        $output = "<?xml version=\"1.0\"?>\n";
        $output .= "<users>\n";
        $output .= "\t<user>{$id}</user>\n";
        $output .= "</users>";

        return response($output)->header('Content-Type', 'text/xml');
    }

    /**
     * Google Verification
     */
    public function googleVerification()
    {
        $id = ConfigRepository::getByKey('site_google_verification');

        return response("google-site-verification: {$id}.html");
    }

    /**
     * Show post by slug
     *
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function postShow($slug)
    {
        $post = PostRepository::getSitePostBySlug($slug);

        if (empty($post)) {
            return abort(404);
        } else {
            return view('post.show', compact('post'));
        }
    }

    /**
     * Site terms list
     *
     * @param $tax
     * @param $heading
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function termList($tax, $heading)
    {
        $terms = TermRepository::getSiteAllTerms($tax);

        return view('term.list', compact('tax', 'heading', 'terms'));
    }

    /**
     * Site posts by term slug
     *
     * @param $slug
     * @param $tax
     * @param $headingPrefix
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    protected function termShow($slug, $tax, $headingPrefix)
    {
        $term = TermRepository::getSiteTermBySlug($slug, $tax);

        if (empty($term)) {
            return abort(404);
        } else {
            $heading = $headingPrefix . $term->name;

            $posts = PostRepository::getSitePostsPaginateByTerm($term);

            return view('welcome', compact('heading', 'posts'));
        }
    }

    /**
     * Site categories list
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function termCats()
    {
        return $this->termList('category', 'Categories');
    }

    /**
     * Site posts by term slug
     *
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function termCatShow($slug)
    {
        return $this->termShow($slug, 'category', 'Category: ');
    }

    /**
     * Site tags list
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function termTags()
    {
        return $this->termList('tag', 'Tags');
    }

    /**
     * Site posts by term slug
     *
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function termTagShow($slug)
    {
        return $this->termShow($slug, 'tag', 'Tag: ');
    }
}
