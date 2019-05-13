<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Http\Responses\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Exception $exception
     * @return mixed|void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->expectsJson() || stripos($request->getPathInfo(), '/api/') === 0) {
            return $this->handleApiException($request, $exception);
        } else {
            return parent::render($request, $exception);
        }
    }

    /**
     * Handle api exception
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    private function handleApiException($request, Exception $exception)
    {
        $exception = $this->prepareException($exception);

        if ($exception instanceof UnauthorizedHttpException) {
            return Response::render($exception->getMessage(), 401);
        }

        if ($exception instanceof AuthenticationException) {
            return Response::render($exception->getMessage(), 401);
        }

        if ($exception instanceof ValidationException) {
            return Response::render(array_first(array_collapse($exception->errors())), 200);
        }

        return $this->apiResponse($exception);
    }

    /**
     * Api response
     *
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    private function apiResponse($exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 500;
        }

        switch ($statusCode) {
            case 401:
                $message = 'Unauthorized';
                break;
            case 403:
                $message = 'Forbidden';
                break;
            case 404:
                $message = 'Page Not Found';
                break;
            case 405:
                $message = 'Method Not Allowed';
                break;
            case 422:
                $message = $exception->original['message'];
                break;
            default:
                $message = $exception->getMessage();
                $message = empty($message) ? 'System error' : $message;
                break;
        }

        $traceData = [];
        if (config('app.debug')) {
            $traceData['exception'] = get_class($exception);
            $traceData['trace'] = $exception->getTrace();
        }

        return Response::render($message, $statusCode, $traceData);
    }
}
