<?php namespace App\Exceptions;

use Exception;
use Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		'Symfony\Component\HttpKernel\Exception\HttpException'
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{
		return parent::report($e);
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
		$accept = $request->header('Accept');
		$is_json = stristr($accept, 'javascript') || stristr($accept, 'json');
		if ($e->getMessage() === 'Not Authorized' && $is_json) {
			return response()->json(array('error' => $e->getMessage()), [], $e->getCode());
		    //return Response::json(array('error' => $e->getMessage()), $e->getCode());
		} else if (
			(
				$e instanceOf MethodNotAllowedHttpException ||
				$e instanceOf NotFoundHttpException
			)
			&& $is_json) {
			//return response()->json(array('error' => $e->getMessage()), [], $e->getCode());
		    return Response::json(array('error' => $e->getMessage()), $e->getCode());
		} else {
			return parent::render($request, $e);
		}
	}

}
