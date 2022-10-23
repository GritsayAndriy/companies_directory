<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RecoverPasswordController extends Controller
{
    public function sendRecoverPassword(Request $request)
    {
        $fields = $this->validate($request, [
            'email' => 'required|email|exists:users',
        ]);

        $fields['token'] = Str::random(40);

        $passwordReset = PasswordReset::create($fields);

        Mail::to($fields['email'])->send(new PasswordResetMail($passwordReset));

        return response()->json('Sent link');
    }

    public function recoverPassword(string $token, Request $request)
    {
        $fields = $this->validate($request, [
            'password' => 'required|string|confirmed',
        ]);

        $passwordReset = PasswordReset::firstWhere('token', $token);
        $fields['password'] = Hash::make($fields['password']);
        User::where('email', $passwordReset->email)
            ->update($fields);

        return response()->json('Recovered');
    }
}
