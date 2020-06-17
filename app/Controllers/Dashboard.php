<?php namespace App\Controllers;

class Dashboard extends BaseController
{
	public function index()
	{
		$data = [];

		echo view('templates/header', $data);
		echo view('templates/footer');
		echo view('dashboard');
	}

	//--------------------------------------------------------------------

}
