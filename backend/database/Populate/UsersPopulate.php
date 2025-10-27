<?php

namespace Database\Populate;

use App\Models\User;

class UsersPopulate
{
    public static function populate()
    {
        self::createUser(
            'John Doe',
            'teste@email',
            '12345',
            'admin',
            'https://example.com/profile1.jpg'
        );

        $numberOfUsers = 3;

        for ($i = 0; $i < $numberOfUsers; $i++) {
            self::createUser(
                'User ' . ($i + 1),
                'user' . ($i + 1) . '@email',
                '12345',
                'user',
                'https://example.com/profile' . ($i + 2) . '.jpg'
            );

            echo 'User ' . ($i + 1) . ' created successfully.' . PHP_EOL;
        }

        echo "Users populated successfully.\n";
    }

    public static function createUser(string $name, string $email, string $password, string $role, string $avatar): void
    {
        try {
            $user = new User([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $password,
                'avatar' => $avatar,
                'role' => $role,
                'created_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null
            ]);
            
            $user->save();
        } catch (\Exception $e) {
            echo "Error creating user $email: " . $e->getMessage() . PHP_EOL;
        }
    }
}
