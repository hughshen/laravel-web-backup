<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Responses\Response;
use App\Http\Requests\Post\IndexRequest;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\ShowRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Http\Requests\Post\DestroyRequest;
use App\Http\Requests\Post\MarkdownRequest;
use App\Repositories\PostRepository;

class PostController extends Controller
{
    /**
     * @var PostRepository
     */
    private $repository;

    /**
     * PostController constructor.
     * @param PostRepository $repository
     */
    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(IndexRequest $request)
    {
        $limit = $request->input('limit', 10);

        $posts = $this->repository->getPostsPaginate($limit);

        return Response::success(compact('posts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return Response
     */
    public function store(StoreRequest $request)
    {
        $title = $request->input('title');
        $content = $request->input('content', '');
        $excerpt = $request->input('excerpt', '');
        $slug = $request->input('slug');
        $status = $request->input('status', Post::STATUS_PUBLISH);
        $tags = $request->input('tags', []);
        $cats = $request->input('cats', []);

        if ($this->repository->saveNewPost($title, $content, $excerpt, $slug, $status, $tags, $cats)) {
            app('cache')->flush();

            return Response::success();
        } else {
            Response::error(Response::FAIL_MESSAGE);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @param  ShowRequest  $request
     * @return Response
     */
    public function show($id, ShowRequest $request)
    {
        $post = $this->repository->getPostById($id);

        return Response::success(compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  UpdateRequest  $request
     * @return Response
     */
    public function update($id, UpdateRequest $request)
    {
        $title = $request->input('title');
        $content = $request->input('content', '');
        $excerpt = $request->input('excerpt', '');
        $slug = $request->input('slug');
        $status = $request->input('status', Post::STATUS_PUBLISH);
        $tags = $request->input('tags', []);
        $cats = $request->input('cats', []);

        if ($this->repository->updatePostById($id, $title, $content, $excerpt, $slug, $status, $tags, $cats)) {
            app('cache')->flush();

            return Response::success();
        } else {
            Response::error(Response::FAIL_MESSAGE);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  DestroyRequest  $request
     * @return Response
     */
    public function destroy($id, DestroyRequest $request)
    {
        if ($this->repository->destroyPostById($id)) {
            app('cache')->flush();

            return Response::success();
        }

        Response::error();
    }

    /**
     * Parse markdown content to html.
     *
     * @param MarkdownRequest $request
     * @return Response
     */
    public function markdown(MarkdownRequest $request)
    {
        $content = $request->input('content');

        $html = app('markdown')->process($content);

        return Response::success(compact('html'));
    }
}
