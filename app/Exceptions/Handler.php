<?php

namespace VanguardLTE\Exceptions;

use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  Exception  $e
     * @return void
     */
    public function report(Throwable $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Exception $e
     * @return Response
     */
    public function render($request, Throwable $exception)
    {
		$userLevelCheck = $exception instanceof \jeremykenedy\LaravelRoles\App\Exceptions\RoleDeniedException ||
            $exception instanceof \jeremykenedy\LaravelRoles\App\Exceptions\RoleDeniedException ||
            $exception instanceof \jeremykenedy\LaravelRoles\App\Exceptions\PermissionDeniedException ||
            $exception instanceof \jeremykenedy\LaravelRoles\App\Exceptions\LevelDeniedException;

        if ($userLevelCheck) {
            $_obf_strlog = '';
            $_obf_strlog .= "\n";
            $_obf_strlog .= date("Y-m-d H:i:s") . ' ';
            $_obf_strlog .= $request->fullUrl() . '\n';
            $_obf_strinternallog= '';
            if( file_exists(storage_path('logs/') . 'urlInternal.log') ) 
            {
                $_obf_strinternallog = file_get_contents(storage_path('logs/') . 'urlInternal.log');
            }
            file_put_contents(storage_path('logs/') . $this->slotId . 'urlInternal.log', $_obf_strinternallog . $_obf_strlog);

            if ($request->expectsJson()) {
                return Response::json(array(
                    'error'    =>  403,
                    'message'   =>  'Unauthorized.'
                ), 403);
            }

            abort(403);
        }

        return parent::render($request, $exception);
        //return parent::render($request, $e);
    }
	
	protected function unauthenticated($request, AuthenticationException $exception)
	{
		$guard = array_get($exception->guards(),0);
		
		
		if ($request->is('backend*')){
			return $request->expectsJson()
							? response()->json(['message' => $exception->getMessage()], 401)
							: redirect()->guest(route('backend.auth.login'));
			
		} else {
			return $request->expectsJson()
							? response()->json(['message' => $exception->getMessage()], 401)
							: redirect()->guest(route('frontend.game.list'));
		}
		
	}

    private function getMessageFromStatusCode($code)
    {
        return array_get(Response::$statusTexts, $code);
    }

    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json($exception->errors(), $exception->status);
    }
}
