<?php

namespace App\Exceptions;

use App\Http\Kernel;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($this->isHttpException($exception))
        {
            switch ($exception->getStatusCode())
            {
                case '404':
                    \Route::any(request()->path(), function () use ($exception, $request)
                    {
                        return parent::render($request, $exception);
                    })->middleware('web');
                    return app()->make(Kernel::class)->handle($request);
                    break;
                case '403':
                    if ($request->session()->has("loggedout"))
                    {
                        return redirect(route("root"));
                    }
                default:
                    return $this->renderHttpException($exception);
                    break;
            }
        }

        return parent::render($request, $exception);
    }
}
