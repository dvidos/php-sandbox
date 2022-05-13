<?php

class SiteController extends Controller
{
	const ABOUT_DV_ARTICLE_ID = 2;
	const COPYRIGHT_ARTICLE_ID = 7;
	
	function actionIndex()
	{
		$criteria = new Criteria('articles');
		$criteria->condition = 'published = 1 AND publish_at < NOW()';
		$criteria->orderBy = 'publish_at DESC';
		
		$pagination = new Pagination(new Article(), $criteria, 'index_article_entry', ['/'], 3, 'pagination');
		echo cms()->load_view('index', ['content' => $pagination->render()]);
	}
	
	function actionArticle($id)
	{
		$article = new Article();
		if (!$article->find($id))
			die('404 - Page not found');
		
		if (!$article->published && empty($_SESSION['logged_in']))
			die('404 - Page not found');

		echo cms()->load_view('article', ['article' => $article]);
	}
	
	function actionAbout()
	{
		$article = new Article();
		if (!$article->find(self::ABOUT_DV_ARTICLE_ID))
			die('404 - Page not found');
		
		// we allow this article to be shown
		echo cms()->load_view('article', ['article' => $article]);
	}
	
	function actionCopyright()
	{
		$article = new Article();
		if (!$article->find(self::COPYRIGHT_ARTICLE_ID))
			die('404 - Page not found');
		
		// we allow this article to be shown
		echo cms()->load_view('article', ['article' => $article]);
	}
	
	function actionSignIn()
	{
		// present login form
		$user = new User();
		$error = '';
		
		if (isset($_POST['email']))
		{
			$email = cms()->request->get_email('email');
			$password = cms()->request->get_string('password', 50);
			$redirect = cms()->request->get_url('redirect');
			
			$found = $user->find(['email' => $email]);
			if (!$found) {
				$error = 'Login invalid';
			} else if (!$user->verify($password)) {
				$error = 'Login invalid';
			} else {
				$_SESSION['logged_in'] = true;
				$_SESSION['user'] = $user;
				
				$user->last_login_at = date('Y-m-d H:i:s P');
				$user->save();
				
				if (empty($redirect))
					cms()->redirect('/');
				else
					cms()->redirect($redirect);
				return;
			}
		}
		
		echo cms()->load_view('signIn', ['user' => $user, 'error' => $error]);
	}
	
	function actionSignOut()
	{
		$_SESSION['logged_in'] = false;
		$_SESSION['user'] = null;
		cms()->redirect('/');
	}
}