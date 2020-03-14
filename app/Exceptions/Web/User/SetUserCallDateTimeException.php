<?php


namespace App\Exceptions\Web\User;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class SetUserCallDateTimeException extends UnprocessableEntityHttpException
{
    protected $message;
    protected $route;

    public function __construct()
    {
        parent::__construct();
        $this->message = 'امکان ثبت تماس وجود ندارد.';
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function render()
    {
        return back()->withErrors($this->message)->withInput();
    }
}
