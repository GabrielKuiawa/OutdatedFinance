<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $title = 'Home Page';
        $this->render('home/index', compact('title'));
    }
    public function getAllUsers(): void
    {
        // $users = User::all();

        $result = [];

        // foreach ($users as $user) {
        $result[] = [
            'id' => "ejr2k3jmik4j23",
            'name' => "John Doe",
            'email' => "teste@gmal",
            'is_manager' => true,
            'profile_picture_url' => "https://example.com/profile.jpg",
        ];
        // }

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    }
}
