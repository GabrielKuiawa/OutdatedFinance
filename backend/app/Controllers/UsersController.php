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
        $users = User::all(['role' => 'admin']);
        $result = [];
        foreach ($users as $user) {
            if ($user->role != 'admin') {
                $result[] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'password' => $user->encrypted_password,
                    'avatar' => $user->avatar,
                    'created_at' => $user->created_at,
                    'deleted_at' => $user->deleted_at,
                ];
            }
        }
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function getAdmins(): void
    {
        $users = User::all(['role' => 'admin']);
        $result = [];
        foreach ($users as $user) {
            if ($user->role != 'user') {
                $result[] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'password' => $user->encrypted_password,
                    'avatar' => $user->avatar,
                    'created_at' => $user->created_at,
                    'deleted_at' => $user->deleted_at,
                ];
            }
        }
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function getAllUsers(): void
    {
        $users = User::all();

        $result = [];

        foreach ($users as $user) {
            $result[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'password' => $user->encrypted_password,
                'avatar' => $user->avatar,
                'created_at' => $user->created_at,
                'deleted_at' => $user->deleted_at,
            ];
        }

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function login(Request $request): void
    {
        $email = $request->getParam('email');
        $password = $request->getParam('password');

        if (!$email || !$password) {
            http_response_code(400);
            echo json_encode(['error' => 'Email e senha são obrigatórios' . $password . $email]);
            return;
        }

        $user = User::findByEmail($email);
        if ($user && $user->email && $user->authenticate($password)) {
            $token = Auth::createToken([
                'id' => $user->id,
                'email' => $user->email,
                'role' => $user->role
            ]);

            header('Content-Type: application/json');
            echo json_encode(['token' => $token, 'role' => $user->role]);
            return;
        }

        http_response_code(401);
        echo json_encode(['error' => 'Credenciais inválidas']);
    }
}
