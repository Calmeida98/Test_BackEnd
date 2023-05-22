<?php

/**Generate by ASGENS
*@author Carlos Almeida  
*@date Sun May 21 16:54:10 GMT-04:00 2023  
*@time Sun May 21 16:54:10 GMT-04:00 2023  
*/
namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        $status = 500;
        $error = [];
        $result = ['success' => false, 'message' => $e->getMessage(), 'errors' => $error];
        if ($e instanceof ValidationException) {
            $error = $e->validator->errors();
            if (isset($error->messages()['login'])) {
                $message = $error->messages()['error'][0];
                $result = ['success' => false, 'message' => $message,];
                $status = $e->status;
            } else {
                $result = ['success' => false, 'message' => $e->getMessage()];
                $status = 406;
            }
        } elseif ($e instanceof HttpException) {
            $status = $e->getStatusCode();
        } elseif ($e instanceof AuthenticationException) {
            $status = 403;
            $result = ['success' => false, 'message' => $e->getMessage()];
        } elseif ($e instanceof ModelNotFoundException) {
            $status = 404;
        } elseif ($e instanceof \TypeError) {
            if (strpos($e->getMessage(), "Argument 3 passed to Lcobucci") !== false)
                $result = ['success' => false, 'message' => "You must type\n php artisan key:generate and php artisan jwt:secret"];
            else
                $result = ['success' => false, 'message' => $e->getMessage()];
        } else {
            $error = ['file' => $e->getFile(), 'line' => $e->getLine(), 'trace' => $e->getTrace()];
            $result = ['success' => false, 'message' => $e->getMessage(), 'error' => $error];

        }
        return response()->json($result, $status);
//        return parent::render($request, $e); // TODO: Change the autogenerated stub
    }
}



