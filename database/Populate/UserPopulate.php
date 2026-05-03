<?php

namespace Database\Populate;

use App\Models\User;

class UserPopulate
{

    public static function populate(): void
    {
        try {
            $managerUser = [
                'name' => 'Manager',
                'email' => 'test@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => 'manager',
                'status' => 'active',
            ];

            $user = new User($managerUser);
            $user->save();

            $employeeUser = [
                'name' => 'Employee',
                'email' => 'employee@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => 'employee',
                'status' => 'active',
            ];

            $user = new User($employeeUser);
            $user->save();

            $employeeUserInactive = [
                'name' => 'Employee_inactive',
                'email' => 'employee_inactive@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => 'employee',
                'status' => 'inactive',
            ];

            $user = new User($employeeUserInactive);
            $user->save();

            echo "Users populated successfully.\n";
        } catch (\Throwable $e) {
            echo "Erro ao populate usuários: " . $e->getMessage() . PHP_EOL;
        }
    }
}
