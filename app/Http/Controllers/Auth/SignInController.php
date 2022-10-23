<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SignInController extends Controller
{
    public function __invoke(Request $request)
    {
        $fields = $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::firstWhere('email', $fields['email']);
        if ($user === null) {
            return response()->json(['error' => 'Wrong email or password'], 403);
        }

        if (Hash::check($fields['password'], $user->password)) {
            $token = base64_encode(Str::random(40));
            $user->tokens()->create(['token' => $token]);
            return response()->json(['token' => $token]);
        }
        return response()->json(['error' => 'Wrong email or password'], 403);
    }
}
