<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ChangePassword extends Controller
{
    protected $user;

    public function __construct()
    {
        $id = intval(request()->id);
        $this->user = User::find($id);
    }

    public function show()
    {
        return view('auth.change-password');
    }

    public function update(Request $request)
    {
        $attributes = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:5', 'confirmed'],
        ]);

        if ($this->user && $this->user->email === $attributes['email']) {
            $this->user->update([
                'password' => Hash::make($attributes['password'])
            ]);

            Auth::login($this->user);

            return redirect('dashboard')->with('success', 'Password has been changed successfully!');
        } else {
            return back()->with('error', 'Your email does not match the email who requested the password change');
        }
    }
}
