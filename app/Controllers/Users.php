<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
	public function index()
	{
		$data = [];
		helper(['form']);

		if ($this->request->getMethod() == 'post') {
			// let's do the validation here
			$rules = [
				'email' => 'required|min_length[6]|max_length[30]|valid_email',
				'password' => 'required|min_length[8]|max_length[30]|validateUser[email, password]',
			];

			$errors = [
				'password' => [
					'validateUser' => 'Email or Password don\'t match'
				]
			];

			if (!$this->validate($rules, $errors)) {
				$data['validation'] = $this->validator;
			} else {
				// store the user in our DB
				$model = new UserModel();


				$user = $model->where('email', $this->request->getVar('email'))->first();

				$this->setUserSession($user);
				// $session->setFlashdata('success', 'Successful registration');
				return redirect()->to('dashboard');
			}
		}

		echo view('templates/header', $data);
		echo view('templates/footer');
		echo view('login');
	}

private function setUserSession($user){
	$data = [
		'id' => $user['id'],
		'firstname' => $user['firstname'],
		'lastname' => $user['lastname'],
		'email' => $user['email'],
		'isLoggedIn' => true,
	];

	session()->set($data);
	return true;
}

	public function register()
	{
		$data = [];
		helper(['form']);

		if ($this->request->getMethod() == 'post') {
			// let's do the validation here
			$rules = [
				'firstname' => 'required|min_length[3]|max_length[20]',
				'lastname' => 'required|min_length[3]|max_length[20]',
				'email' => 'required|min_length[6]|max_length[30]|valid_email|is_unique[users.email]',
				'password' => 'required|min_length[8]|max_length[30]',
				'password_confirm' => 'matches[password]',
			];

			if (!$this->validate($rules)) {
				$data['validation'] = $this->validator;
			} else {
				// store the user in our DB
				$model = new UserModel();

				$newData = [
					'firstname' => $this->request->getVar('firstname'),
					'lastname' => $this->request->getVar('lastname'),
					'email' => $this->request->getVar('email'),
					'password' => $this->request->getVar('password'),
				];
				$model->save($newData);
				$session = session();
				$session->setFlashdata('success', 'Successful registration');
				return redirect()->to('/');
			}
		}


		echo view('templates/header', $data);
		echo view('templates/footer');
		echo view('register');
	}
}
