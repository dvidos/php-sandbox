<?php

class UsersController extends Controller
{
	function actionIndex()
	{
		// show all entries
		$user = new User();
		$users = $user->findAll();
		echo cms()->load_view('index', ['users' => $users]);
	}
	
	function actionCreate()
	{
		$user = new User();
		if (cms()->request->method == 'post')
		{
			$user->email = cms()->request->get_string('email');
			$p1 = cms()->request->get_string('password1');
			$p2 = cms()->request->get_string('password2');
			if ($p1 == $p2 && strlen($p1) > 0)
				$user->password_hash = $user->encrypt($p1);
			$user->allow_admin = cms()->request->get_html('allow_admin');
			$user->updated_at = date('Y-m-d H:i:s');
			
			$user->save();
			
			// how about auto_increment value
			cms()->redirect('index');
			return;
		}
		
		echo cms()->load_view('create', ['user' => $user]);
	}
	
	function actionUpdate($id)
	{
		$user = new User();
		if (!$user->find($id))
		{
			die('404 - not found');
			return;
		}
		
		if (cms()->request->method == 'post')
		{
			$user->email = cms()->request->get_string('email');
			$p1 = cms()->request->get_string('password1');
			$p2 = cms()->request->get_string('password2');
			if ($p1 == $p2 && strlen($p1) > 0)
				$user->password_hash = $user->encrypt($p1);
			$user->allow_admin = cms()->request->get_html('allow_admin');
			$user->updated_at = date('Y-m-d H:i:s');
			
			$user->save();
		}
		
		echo cms()->load_view('update', ['user' => $user]);
	}
	
	function actionDelete($id)
	{
		// action to delete, i suppose through ajax?
	}
}

