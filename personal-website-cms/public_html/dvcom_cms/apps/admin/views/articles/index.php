<?php

echo '<h1>Articles</h1>';

echo '<p><a href="' . cms()->create_url('create') . '">Create new</a></p>';

echo '<table class="bordered">';
echo '<tr><th>ID</th><th>Title</th><th>Published</th><th>At</th><th>View</th></tr>';

foreach ($articles as $article)
{
	echo '<tr>';
	echo '<td>' . $article->id . '</td>';
	
	$url = cms()->create_url('update', ['id' => $article->id]);
	$link = Html::tag('a', ['href'=>$url], htmlentities($article->title));
	echo '<td>' . $link . '</td>';


	echo '<td>' . ($article->published ? 'Yes' : '&nbsp;') . '</td>';
	echo '<td>' . $article->publish_at . '</td>';

	$url = cms()->create_url('/site/article', ['id' => $article->id]);
	$link = Html::tag('a', ['href' => $url, 'target' => '_blank'], 'view');
	echo '<td>' . $link . '</td>';


	echo '</tr>';
}

echo '</table>';

