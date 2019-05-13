<?php

namespace App\Http\Responses;

use Symfony\Component\HttpKernel\Exception\HttpException;

class Response
{
    // Success
    const SUCCESS_CODE = 200;
    const SUCCESS_MESSAGE = 'Successfully';

    // Fail
    const FAIL_MESSAGE = 'Failure';

    /**
     * Render
     *
     * @param string $message
     * @param integer $code
     * @param array $data
     * @return Response
     */
    public static function render($message, $code, $data = [])
    {
        $json = [
            'code' => $code,
            'message' => $message,
        ];

        if (!empty($data)) {
            $json['data'] = $data;
        }

        return response()->json($json, $code);
    }

    /**
     * Success
     *
     * @param array $data
     * @param string $message
     * @return Response
     */
    public static function success($data = [], $message = null)
    {
        $message = empty($message) ? self::SUCCESS_MESSAGE : $message;

        return self::render($message, self::SUCCESS_CODE, $data);
    }


    /**
     * Error
     *
     * @param string $message
     * @param int $code
     * @param \Exception|null $e
     */
    final public static function error($message = '', $code = 500, \Exception $e = null)
    {
        if (empty($message)) {
            $message = 'System error';
        }

        throw new HttpException($code, $message, $e);
    }
}
