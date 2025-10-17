<?php

namespace App\Controllers;

use App\Models\User;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;

class UsersController extends Controller
{
    public function getUsers(): void
    {
        $users = User::where(['role' => 'user']);

        $this->renderJson("users/index", compact('users'));
    }

    public function getAdmins(): void
    {
        $users = User::where(['role' => 'admin']);

        $this->renderJson("users/index", compact('users'));
    }

    public function getAllUsers(): void
    {
        $users = User::all();

        $this->renderJson("users/index", compact('users'));
    }

    public function login(Request $request): void
    {
        $email = $request->getParam('email');
        $password = $request->getParam('password');

        if (!$email || !$password) {
            $this->renderJson(['error' => 'Email e senha são obrigatórios']);
        }

        $user = User::findByEmail($email);
        if ($user && $user->email && $user->authenticate($password)) {
            $token = Auth::createToken([
                'id' => $user->id,
                'email' => $user->email,
                'role' => $user->role
            ]);

            $this->renderJson(['token' => $token, 'role' => $user->role]);
        }


        $this->renderJson(['error' => 'Credenciais inválidas']);
    }
}
