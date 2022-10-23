<h1>Password reset</h1>
<a href="{{route('users.recover-password', ['token' => $passwordReset->token])}}">
    {{route('users.recover-password', ['token' => $passwordReset->token])}}
</a>
