<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Events\UserLogin;
use App\Events\UserLogout;
use App\Http\Responses\Response;
use App\Repositories\ConfigRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Get user model
     *
     * @return mixed
     */
    protected function getAuthUser()
    {
        return Auth::guard('api')->user();
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param Request $request
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $rules = [
            'username' => ['bail', 'required', 'string'],
            'password' => ['bail', 'required', 'string'],
        ];

        $params = $this->validate($request, $rules);

        $userInvalidMsg = 'Incorrect username or password.';

        $user = User::where('username', $request->input('username'))->first();

        // Empty user
        if (empty($user)) {
            return Response::success([], $userInvalidMsg);
        }

        // Wrong password
        if (!app('hash')->check($request->input('password'), $user->password)) {
            return Response::success([], $userInvalidMsg);
        }

        // Check 2FA
        if (ConfigRepository::yesOrNoByKey('site_backend_2fa', false)) {
            if (!app('gauth')->verifyCode($user->secret_2fa, $request->input('code_2fa'))) {
                return Response::success([], 'Incorrect 2FA code.');
            }
        }

        // Login start
        if (($token = Auth::guard('api')->attempt($params))) {
            $user = $this->getAuthUser();

            event(new UserLogin($user));

            return Response::success(compact('token', 'user'));
        } else {
            return Response::success([], $userInvalidMsg);
        }
    }

    /**
     * User logout
     *
     * @return Response
     */
    public function logout()
    {
        event(new UserLogout($this->getAuthUser()));

        Auth::guard('api')->logout();

        return Response::success();
    }

    /**
     * User profile
     *
     * @param Request $request
     * @return Response
     */
    public function profile(Request $request)
    {
        $user = $this->getAuthUser();

        return Response::success(compact('user'));
    }
}
