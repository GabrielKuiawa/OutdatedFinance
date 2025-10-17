<?php

namespace Tests\Unit\Lib\Authentication;

use Lib\Authentication\Auth;
use App\Models\User;
use Lib\Authentication\JWT;
use Tests\TestCase;

class AuthTest extends TestCase
{
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = new User([
            'name' => 'Test User',
            'email' => 'teste@email',
            'password' => '12345',
            'password_confirmation' => '12345',
            'avatar' => 'https://example.com/profile.jpg',
            'role' =>  'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'deleted_at' => null
        ]);
        $this->user->save();
    }

    public function test_create_token_and_check(): void
    {
        $token = Auth::createToken([
            'id' => $this->user->id,
            'email' => $this->user->email,
            'role' => $this->user->role
        ]);

        $decoded = JWT::decode($token);

        $this->assertNotNull($decoded);
        $this->assertEquals($this->user->email, $decoded->user['email']);
        $this->assertTrue($decoded->user['role'] === 'admin');
    }
}
