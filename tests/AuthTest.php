<?php

declare(strict_types=1);

namespace Tests;

use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthTest extends TestCase
{
    private array $userData = [
        'first_name' => 'Test',
        'last_name' => 'Test',
        'phone' => '93491872398',
        'email' => 'test@test.ua',
        'password' => '123456789',
    ];

    public function testRegistration()
    {
        $responseData = $this->userData;
        unset($responseData['password']);

        $this->userData['password_confirmation'] = $this->userData['password'];

        $response = $this->call('POST', 'api/user/register', $this->userData);

        $response->assertJsonFragment($responseData)
            ->assertJsonMissing(['password']);
    }

    public function testSignIn()
    {
        $user = User::factory()->create();

        $this->call('POST', 'api/user/sign-in', [
            'email' => $user->email,
            'password' => '123456789'
        ])->assertJsonStructure(['token']);
    }

    public function testSendRecoverPassword()
    {
        $user = User::factory()->create();

        $this->call('POST', 'api/user/recover-password', [
            'email' => $user->email,
        ])->assertJsonFragment(['Sent link'])
            ->assertStatus(200);

        $this->seeInDatabase('password_resets', ['email' => $user->email]);
    }

    public function testRecoverPassword()
    {
        $user = User::factory()->create();
        $newPassword = '1234567890';
        $passwordReset = PasswordReset::create([
            'email' => $user->email,
            'token' => Str::random(40)
        ]);

        $this->call('PATCH', "api/user/recover-password/$passwordReset->token", [
            'password' => $newPassword,
            'password_confirmation' => $newPassword
        ])->assertJsonFragment(['Recovered'])
            ->assertStatus(200);

        $user = User::find($user->id);
        $this->assertIsBool(Hash::check($newPassword, $user->password));
    }
}
