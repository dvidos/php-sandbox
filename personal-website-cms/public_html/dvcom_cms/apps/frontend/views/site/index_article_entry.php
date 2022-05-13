<article>
<?php
	
	$url = cms()->create_url('article', ['id' => $entry->id]);
	$link = Html::tag('a', ['href' => $url], htmlentities($entry->title));
	
	echo Html::tag('h1', ['class' => 'title'], $link);
	echo $entry->content;
	
	echo Html::tag('hr');
?>
</article>
