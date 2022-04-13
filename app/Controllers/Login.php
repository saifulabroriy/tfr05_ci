<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LogUserModel;
use App\Models\UserModel;

class Login extends BaseController
{
    protected $logUser;

    public function __construct()
    {
        $this->logUser = new LogUserModel();
        $this->logUser->protect(false);
    }

    public function index()
    {
        return view('login/index');
    }

    public function login()
    {
        $session = session();
        $userModel = new UserModel();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $data = $userModel->where('username', $username)->first();

        if ($data) {
            $pass = $data['password'];
            $authenticatePassword = password_verify($password, $pass);
            if ($authenticatePassword) {
                $ses_data = [
                    'id' => $data['id'],
                    'nama' => $data['nama'],
                    'username' => $data['username'],
                    'role' => $data['role'],
                    'isLoggedIn' => TRUE
                ];

                $session->set($ses_data);

                $log = [
                    'iduser' => session()->get('id'),
                    'menu' => 'Log In',
                    'keterangan' => 'User telah log in',
                    'before' => '',
                    'after' => '',
                ];

                $this->logUser->insert($log);

                return redirect()->to('/admin/barang');
            } else {
                $session->setFlashdata('msg', 'Password is incorrect.');
                return redirect()->to('/');
            }
        } else {
            $session->setFlashdata('msg', 'Email does not exist.');
            return redirect()->to('/');
        }
    }

    public function logout()
    {
        $log = [
            'iduser' => session()->get('id'),
            'menu' => 'Log Out',
            'keterangan' => 'User telah log out',
            'before' => '',
            'after' => '',
        ];

        $this->logUser->insert($log);

        $session = session();
        $session->destroy();
        return redirect()->to('/');
    }
}
