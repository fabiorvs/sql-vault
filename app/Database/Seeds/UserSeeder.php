<?php
namespace App\Database\Seeds;

use App\Models\UserModel;
use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();

        $adminEmail = 'admin@admin.com';
        $adminPassword = '123456';

        $exists = $userModel->where('email', $adminEmail)->first();

        if ($exists) {
            return;
        }

        $userModel->insert([
            'name'     => 'Administrador',
            'email'    => $adminEmail,
            'password' => password_hash($adminPassword, PASSWORD_DEFAULT),
            'is_admin' => 1,
            'status'   => 'active',
        ]);
    }
}
