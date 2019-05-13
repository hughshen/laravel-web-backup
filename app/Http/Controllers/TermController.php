<?php

namespace App\Http\Controllers;

use App\Repositories\TermRepository;
use App\Http\Responses\Response;
use App\Http\Requests\Term\IndexRequest;
use App\Http\Requests\Term\DestroyRequest;
use App\Http\Requests\Term\ShowRequest;
use App\Http\Requests\Term\StoreRequest;
use App\Http\Requests\Term\UpdateRequest;

class TermController extends Controller
{
    /**
     * @var TermRepository
     */
    private $repository;

    /**
     * TermController constructor.
     * @param TermRepository $repository
     */
    public function __construct(TermRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Responses\Response
     */
    public function index(IndexRequest $request)
    {
        $tax = $request->input('tax');
        $all = $request->input('all', 0);
        $limit = $request->input('limit', 10);

        if ((int)$all == 1) {
            $terms = $this->repository->getAllTerms($tax);
        } else {
            $terms = $this->repository->getTermsPaginate($tax, $limit);
        }

        return Response::success(compact('terms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @return Response
     */
    public function store(StoreRequest $request)
    {
        $name = $request->input('name');
        $description = $request->input('description', '');
        $slug = $request->input('slug');
        $taxonomy = $request->input('taxonomy');
        $status = $request->input('status', 1);

        if ($this->repository->saveNewTerm($name, $description, $slug, $taxonomy, $status)) {
            app('cache')->flush();

            return Response::success();
        } else {
            Response::error(Response::FAIL_MESSAGE);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @param ShowRequest $request
     * @return Response
     */
    public function show($id, ShowRequest $request)
    {
        $term = $this->repository->getTermById($id);

        return Response::success(compact('term'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @param UpdateRequest $request
     * @return Response
     */
    public function update($id, UpdateRequest $request)
    {
        $name = $request->input('name');
        $description = $request->input('description', '');
        $slug = $request->input('slug');
        $taxonomy = $request->input('taxonomy');
        $status = $request->input('status', 1);

        if ($this->repository->updateTermById($id, $name, $description, $slug, $taxonomy, $status)) {
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
        if ($this->repository->destroyTermById($id)) {
            app('cache')->flush();

            return Response::success();
        }

        Response::error();
    }
}
