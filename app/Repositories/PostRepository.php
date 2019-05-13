<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\Term;
use Bosnadev\Repositories\Eloquent\Repository;

class PostRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Post::class;
    }

    /**
     * Save new post
     *
     * @return bool
     */
    public function saveNewPost($title, $content, $excerpt, $slug, $status, $tags, $cats)
    {
        app('db')->beginTransaction();
        try {
            $post = factory(Post::class)->create([
                'author' => auth('api')->user()->id,
                'title' => $title,
                'content' => $content,
                'excerpt' => $excerpt,
                'slug' => $slug,
                'status' => $status,
            ]);

            // Tags
            foreach ($tags as $val) {
                $post->tags()->attach([
                    $val => ['post_id' => $post->id],
                ]);
            }

            // Cats
            foreach ($cats as $val) {
                $post->cats()->attach([
                    $val => ['post_id' => $post->id],
                ]);
            }

            app('db')->commit();

            return $post;
        } catch (\Exception $e) {
            app('db')->rollback();
            return false;
        }
    }

    /**
     * Update post by id
     *
     * @return bool
     */
    public function updatePostById($id, $title, $content, $excerpt, $slug, $status, $tags, $cats)
    {
        $post = Post::findOrFail($id);

        app('db')->beginTransaction();
        try {
            $post->update([
                'title' => $title,
                'content' => $content,
                'excerpt' => $excerpt,
                'slug' => $slug,
                'status' => $status,
                'updated_at' => time(),
            ]);

            // Terms
            $post->terms()->detach();

            // Tags
            foreach ($tags as $val) {
                $post->tags()->attach([
                    $val => ['post_id' => $post->id],
                ]);
            }

            // Cats
            foreach ($cats as $val) {
                $post->cats()->attach([
                    $val => ['post_id' => $post->id],
                ]);
            }

            app('db')->commit();

            return true;
        } catch (\Exception $e) {
            app('db')->rollback();
            return false;
        }
    }

    /**
     * Destroy post by id
     *
     * @param integer $id
     * @return bool
     */
    public function destroyPostById($id)
    {
        $post = Post::findOrFail($id);

        app('db')->beginTransaction();
        try {
            $post->delete();
            $post->terms()->detach();

            app('db')->commit();

            return true;
        } catch (\Exception $e) {
            app('db')->rollback();
            return false;
        }
    }

    /**
     * Get post by id
     *
     * @param integer $id
     * @return \Model
     */
    public function getPostById($id)
    {
        $post = Post::with(['tags', 'cats'])->findOrFail($id);

        return $post;
    }

    /**
     * Get posts paginate
     * @param integer $limit
     * @return mixed
     */
    public function getPostsPaginate($limit = 10)
    {
        return Post::with(['tags', 'cats'])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate($limit);
    }

    /**
     * Get site posts paginate
     *
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public static function getSitePostsPaginate($limit = 10)
    {
        return Post::query()
            ->where('type', Post::TYPE_POST)
            ->where('status', Post::STATUS_PUBLISH)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->simplePaginate($limit);
    }

    /**
     * Get site all posts
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getSiteAllPosts()
    {
        return Post::query()
            ->where('type', Post::TYPE_POST)
            ->where('status', Post::STATUS_PUBLISH)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();
    }

    /**
     * Get site all posts by search
     *
     * @param $s
     * @param null $column
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public static function getSitePostsPaginateBySearch($s, $column = null, $limit = 10)
    {
        $s = "%{$s}%";

        $builder = Post::query()
            ->where('type', Post::TYPE_POST)
            ->where('status', Post::STATUS_PUBLISH)
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        if ($column === null) {
            $builder->where(function($query) use ($s) {
                $query->orWhere('title', 'like', $s)
                    ->orWhere('content', 'like', $s)
                    ->orWhere('slug', 'like', $s);
            });
        } elseif(is_string($column)) {
            $builder->where($column, 'like', $s);
        }

        return $builder->simplePaginate($limit);
    }

    /**
     * Get site post by slug
     *
     * @param $slug
     * @return Post|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public static function getSitePostBySlug($slug)
    {
        return Post::with(['tags', 'cats'])
            ->where('type', Post::TYPE_POST)
            ->where('status', Post::STATUS_PUBLISH)
            ->where('slug', $slug)
            ->first();
    }

    /**
     * Get site posts by term'slug and term'taxonomy
     *
     * @param $term
     * @param $limit
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public static function getSitePostsPaginateByTerm(Term $term, $limit = 10)
    {
        return Post::query()
            ->where('type', Post::TYPE_POST)
            ->where('status', Post::STATUS_PUBLISH)
            ->whereHas('terms', function ($query) use ($term) {
                $query->where('taxonomy', $term->taxonomy)
                    ->where('slug', $term->slug)
                    ->where('status', 1);
            })
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->simplePaginate($limit);
    }
}