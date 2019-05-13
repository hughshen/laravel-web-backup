<?php

namespace App\Http\Controllers;

use App\Http\Responses\Response;
use App\Http\Requests\Config\IndexRequest;
use App\Http\Requests\Config\UpdateRequest;
use App\Repositories\ConfigRepository as Config;

class ConfigController extends Controller
{
    private $keys = [
        'site_name',
        'site_keywords',
        'site_description',
        'site_copyright',
        'site_backend_2fa',
        'site_bing_auth',
        'site_google_verification',
        'site_google_analytics_code',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(IndexRequest $request)
    {
        $configs = Config::getByKeys($this->keys, false);

        return Response::success(compact('configs'));
    }

    /**
     * Update the listing resources in storage.
     *
     * @param UpdateRequest $request
     * @return Response
     */
    public function update(UpdateRequest $request)
    {
        $data = [];
        foreach ($this->keys as $key) {
            $data[$key] = $request->input($key, '');
        }

        if (Config::updateByData($data)) {
            app('cache')->flush();

            return Response::success();
        } else {
            Response::error(Response::FAIL_MESSAGE);
        }
    }
}
