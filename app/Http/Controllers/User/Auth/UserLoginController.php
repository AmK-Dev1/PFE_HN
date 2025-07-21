<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserLoginController extends Controller
{
    public function index(): Renderable
    {
        return view('user.auth.login');
    }

    private function guard()
    {
        return auth('web');
    }

    private function getCredentials(Request $request): array
    {
        return $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('user.login');
    }

    public function login(Request $request)
    {
        $data = $this->getCredentials($request);

        if ($this->guard()->attempt($data,$request)){
            if($request->wantsJson()){
                return response()->json([
                    'success' => true,
                    'message' =>__('messages.login_success',['name' => auth()->user()->name]),
                ]);
            }

            // Redirect the manager to the company selection screen
            return redirect()->route('user.company.select');

        }

        //login fails
        session()->flash('error',trans('auth.failed'));
        return $request->wantsJson()
            ? response()->json([
                'success' => false,
                'message' =>__('auth.failed'),
            ],401)
            : redirect()->back();
    }
}
