<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Http\Responses\Response;
use App\Http\Requests\User\IndexRequest;
use App\Http\Requests\User\DestroyRequest;
use App\Http\Requests\User\ShowRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;

class UserController extends Controller
{
    /**
     * @var PostRepository
     */
    private $repository;

    /**
     * UserController constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexRequest $request
     * @return Response
     */
    public function index(IndexRequest $request)
    {
        $limit = $request->input('limit', 10);

        $users = $this->repository->getUsersPaginate($limit);

        return Response::success(compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @return Response
     */
    public function store(StoreRequest $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if ($this->repository->saveNewUser($username, $password)) {
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
        $user = $this->repository->getUserById($id);

        return Response::success(compact('user'));
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
        $username = $request->input('username');
        $password = $request->input('password');

        if ($this->repository->updateUserById($id, $username, $password)) {
            return Response::success();
        } else {
            Response::error(Response::FAIL_MESSAGE);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @param DestroyRequest $request
     * @return Response
     */
    public function destroy($id, DestroyRequest $request)
    {
        if ($this->repository->destroyUserById($id)) {
            return Response::success();
        }

        Response::error();
    }
}
