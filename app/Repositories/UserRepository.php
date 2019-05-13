<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Post;
use Bosnadev\Repositories\Eloquent\Repository;

class UserRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return User::class;
    }

    /**
     * Save new user
     *
     * @return bool
     */
    public function saveNewUser($username, $password)
    {
        $user = factory(User::class)->create([
            'username' => $username,
            'password' => bcrypt($password),
            'login_ip' => 0,
            'login_time' => 0,
        ]);

        return $user;
    }

    /**
     * Update user by id
     *
     * @return bool
     */
    public function updateUserById($id, $username, $password = null)
    {
        $user = User::findOrFail($id);

        $data = [
            'username' => $username,
            'updated_at' => time(),
        ];

        if (!empty($password)) {
            $data['password'] = bcrypt($password);
        }

        return $user->update($data);
    }

    /**
     * Destroy user by id
     *
     * @param integer $id
     * @return bool
     */
    public function destroyUserById($id)
    {
        $user = User::findOrFail($id);

        app('db')->beginTransaction();
        try {
            $user->delete();

            Post::where('author', $id)->update(['author' => 0]);

            app('db')->commit();

            return true;
        } catch (\Exception $e) {
            app('db')->rollBack();
            return false;
        }
    }

    /**
     * Get user by id
     *
     * @param integer $id
     * @return \Model
     */
    public function getUserById($id)
    {
        $user = User::findOrFail($id);

        return $user;
    }

    /**
     * Get users paginate
     * @param integer $limit
     * @return mixed
     */
    public function getUsersPaginate($limit = 10)
    {
        return User::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate($limit);
    }
}