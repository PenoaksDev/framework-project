<?php namespace HolyWorlds\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Milky\Database\Eloquent\ModelNotFoundException;
use Milky\Exceptions\Handler as ExceptionHandler;
use Milky\Http\Request;
use Milky\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		AuthorizationException::class,
		HttpException::class,
		ModelNotFoundException::class,
		ValidationException::class,
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception $e
	 * @return void
	 */
	public function report( Exception $e )
	{
		parent::report( $e );
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  Request $request
	 * @param  \Exception $e
	 * @return Response
	 */
	public function render( $request, Exception $e )
	{
		return parent::render( $request, $e );
	}
}
