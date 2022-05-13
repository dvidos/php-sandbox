<?php

class ArticlesController extends Controller
{
	function actionIndex()
	{
		// show all entries
		$article = new Article();
		$articles = $article->findAll();
		echo cms()->load_view('index', ['articles' => $articles]);
	}
	
	function actionCreate()
	{
		$article = new Article();
		$article->title = '';
		$article->content = '';
		$article->published = false;
		$article->created_at = date('Y-m-d H:i:s');
		$article->updated_at = '';
		$article->publish_at = '';
		
		if (cms()->request->method == 'post')
		{
			$article->title = cms()->request->get_string('title');
			$article->content = cms()->request->get_html('content');
			$article->published = cms()->request->get_bool('published');
			$article->created_at = cms()->request->get_string('created_at');
			$article->updated_at = date('Y-m-d H:i:s');
			$article->publish_at = cms()->request->get_string('publish_at');
			
			$article->save();
			
			// how about auto_increment value
			cms()->redirect('index');
			return;
		}
		
		echo cms()->load_view('create', ['article' => $article]);
	}
	
	function actionUpdate($id)
	{
		$article = new Article();
		if (!$article->find($id))
		{
			die('404 - not found');
			return;
		}
		
		if (cms()->request->method == 'post')
		{
			$article->title = cms()->request->get_string('title');
			$article->content = cms()->request->get_html('content');
			$article->published = cms()->request->get_bool('published');
			$article->created_at = cms()->request->get_string('created_at');
			$article->updated_at = date('Y-m-d H:i:s');
			$article->publish_at = cms()->request->get_string('publish_at');
			
			$article->save();
		}
		
		echo cms()->load_view('update', ['article' => $article]);
	}
	
	function actionDelete($id)
	{
		// action to delete, i suppose through ajax?
	}
}

