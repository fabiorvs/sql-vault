<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function attemptLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Preencha e-mail e senha corretamente.');
        }

        $email    = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');

        $userModel = new UserModel();
        $user      = $userModel->where('email', $email)->first();

        if (! $user) {
            return redirect()->back()->withInput()->with('error', 'Usuário ou senha inválidos.');
        }

        if (($user['status'] ?? 'inactive') !== 'active') {
            return redirect()->back()->withInput()->with('error', 'Usuário inativo.');
        }

        if (! password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Usuário ou senha inválidos.');
        }

        session()->set([
            'user_id'      => $user['id'],
            'user_name'    => $user['name'],
            'user_email'   => $user['email'],
            'is_admin'     => (int) $user['is_admin'],
            'is_logged_in' => true,
        ]);

        return redirect()->to('/dashboard')->with('success', 'Login realizado com sucesso.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Logout realizado com sucesso.');
    }
}
