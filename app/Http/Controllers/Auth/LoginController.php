<?php

namespace Dbs\Http\Controllers\Auth;

use Dbs\User;
use Dbs\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use ThrottlesLogins;

    /**
     * Create a new login controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Show the normal login page.
     *
     * @return \Illuminate\View\View
     */
    public function getLogin()
    {
        return view('auth.login');
    }

    /**
     * Show the administrator login page.
     *
     * @return \Illuminate\View\View
     */
    public function getAdminLogin()
    {
        return view('auth.adminLogin');
    }

    /**
     * Handle a login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required',
            'password' => 'required',
        ]);

        if ($this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $user = User::where(
            $this->username(),
            $request->get($this->username())
        )->first();

        $loginType = $request->get('loginType');

        if (!$user || !$user->UserType || $user->UserType->value !== $loginType) {
            $this->incrementLoginAttempts($request);

            return $this->sendInvalidUserTypeResponse($request);
        }

        if (Auth::attempt(
            $this->credentials($request),
            $request->has('remember')
        )) {
            $this->clearLoginAttempts($request);

            return redirect()->intended($this->redirectPath());
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Log the current user out.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getLogout()
    {
        Auth::logout();

        if (\Request::get('page') === 'admin') {
            return redirect('/admin/auth/login');
        }

        return redirect(
            property_exists($this, 'redirectAfterLogout')
                ? $this->redirectAfterLogout
                : '/'
        );
    }

    /**
     * Get the authentication username field.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Get the login credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Get the successful-login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (property_exists($this, 'redirectPath')) {
            return $this->redirectPath;
        }

        return property_exists($this, 'redirectTo')
            ? $this->redirectTo
            : '/';
    }

    /**
     * Get the appropriate login page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function loginPath(Request $request)
    {
        return $request->get('loginType') === 'Admin'
            ? '/admin/auth/login'
            : '/auth/login';
    }

    /**
     * Respond when the account does not match the requested user type.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendInvalidUserTypeResponse(Request $request)
    {
        return redirect($this->loginPath($request))
            ->withInput(
                $request->only($this->username(), 'remember', 'loginType')
            )
            ->withErrors([
                $this->username() =>
                    'There is no ' . $request->get('loginType') .
                    ' with this email address.',
            ]);
    }

    /**
     * Respond to an unsuccessful login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect($this->loginPath($request))
            ->withInput(
                $request->only($this->username(), 'remember', 'loginType')
            )
            ->withErrors([
                $this->username() =>
                    'These credentials do not match our records.',
            ]);
    }
}
