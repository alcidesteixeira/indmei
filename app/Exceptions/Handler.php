<?php

namespace App\Exceptions;

use App\Mail\sendSimpleEmail;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Mail;

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
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $message = $exception->getMessage();
        $file = $exception->getFile();
        $line = $exception->getLine();
        $url = url()->current();

        $body =
            'Message: ' . $message . '<br>' .
            'File: ' . $file . '<br>' .
            'Line: ' . $line . '<br>' .
            'URL: ' . $url . '<br>';


        Mail::to('alcides.mn.teixeira@gmail.com')->send(new sendSimpleEmail('Erro Plataforma INDMEI', $body));

        return response()->view('errors.custom', compact('message'));
//        return parent::render($request, $exception);
    }
}
