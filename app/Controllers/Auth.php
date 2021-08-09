<?php

namespace App\Controllers;
use App\Models\Auth_m;
use App\Models\Divisi_m;
use CodeIgniter\HTTP\IncomingRequest;
/** 
* @property IncomingRequest $request
*/

class Auth extends BaseController
{
	public function __construct()
	{
		helper('form');
		$this->divisi = new Divisi_m();
        $this->auth = new Auth_m();
	}
	public function index()
	{
		$data = array(
			'title'		  => 'Login',
		);
		return view('auth/login', $data);
	}

	public function login()
	{
        if ($this->validate([
            'username' => [
                'label'  => 'Username',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'password' => [
                'label'  => 'Password',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ]
        ])) {
            //jika valid
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            $cek = $this->auth->login($username,$password);
            if ($cek) {
                //jika data cocok
                session()->set('log', true);
                session()->set('id_user', $cek['id_user']);
                session()->set('username', $cek['username']);
                session()->set('email', $cek['email']);
                session()->set('level', $cek['level']);
                session()->set('id_divisi', $cek['id_divisi']);
                session()->set('foto', $cek['foto']);
                session()->setFlashdata('pesan', 'Login berhasil, selamat datang ');
                return redirect()->to(base_url('home'));
            } else {
                session()->setFlashdata('pesan', 'Login Anda gagal !! Username atau password salah !!');
                return redirect()->to(base_url('auth'));
            }
        }
        else {
            //jika tidak valid
            session()->setFlashdata('errors',\Config\Services::validation()->getErrors());
            return redirect()->to(base_url('auth'));
        }
	}

    public function logout()
    {
        session()->remove('log');
        session()->remove('username');
        session()->remove('email');
        session()->remove('level');
        session()->remove('foto');

        session()->setFlashdata('pesan', 'Anda telah Logout dari sistem...');
        return redirect()->to(base_url('auth'));
    }
}
