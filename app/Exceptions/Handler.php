<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException('Resource Not Found', $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
        ? response()->json(['message' => $exception->getMessage()], 401)
        : response()->json(['message' => $exception->getMessage()], 401);
    }

    // /**
    //  * Prepare a JSON response for the given exception.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \Exception $e
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // protected function prepareJsonResponse($request, Exception $e)
    // {
    //     $status = $this->isHttpException($e) ? $e->getStatusCode() : 500;

    //     $headers = $this->isHttpException($e) ? $e->getHeaders() : [];

    //     var_dump($e);die;

    //     return new JsonResponse(
    //         $this->convertExceptionToArray($e, $status), $status, $headers,
    //         JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
    //     );
    // }

    // *
    //  * Convert the given exception to an array.
    //  *
    //  * @param  \Exception  $e
    //  * @return array

    // protected function convertExceptionToArray(Exception $e, $status = 500)
    // {
    //     return config('app.debug') ? [
    //         'message'   => $e->getMessage(),
    //         'exception' => get_class($e),
    //         'file'      => $e->getFile(),
    //         'line'      => $e->getLine(),
    //         'trace'     => collect($e->getTrace())->map(function ($trace) {
    //             return Arr::except($trace, ['args']);
    //         })->all(),
    //     ] : [
    //         'message'   => $this->isHttpException($e) ? $e->getMessage() : 'Server Error',
    //         'code'      => $status,
    //         'exception' => (new \ReflectionClass($e))->getShortName(),
    //     ];
    // }
}
