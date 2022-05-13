<?php

echo Html::openTag('form', ['method' => 'post']);
echo '<div style="float:left; width: 72%;">';


echo '<p>';
// echo Html::label('Title', 'title') . '<br>';
echo Html::textField('title', $article->title, ['size' => 40, 'style' => 'font-size: 200%; width: 99.5%;', 'placeholder' => 'Title']) . '<br>';
//echo '</p>';

//echo '<p>';
// echo Html::label('Content', 'content') . '<br>';
echo Html::textArea('content', $article->content, ['style' => 'width: 100%;']) . '<br>';
echo cms()->load_template('html_editor', ['selector' => 'textarea#content']);
echo '</p>';


echo '</div><div style="float:right; width: 25%;">';


echo '<p>';
echo Html::label('Published', 'published') . '<br>';
echo Html::checkBox('published', $article->published) . '<br>';
echo '</p>';

echo '<p>';
echo Html::label('Created At', 'created_at') . '<br>';
echo Html::textField('created_at', $article->created_at, ['size'=>20]) . '<br>';
echo '</p>';

echo '<p>';
echo Html::label('Updated At', 'updated_at') . '<br>';
echo Html::textField('updated_at', $article->updated_at, ['size'=>20]) . '<br>';
echo '</p>';

echo '<p>';
echo Html::label('Publish At', 'publish_at') . '<br>';
echo Html::textField('publish_at', $article->publish_at, ['size'=>20]) . '<br>';
echo '</p>';

echo '<p>';
echo Html::submit('Save');
echo '</p>';


echo '</div><div style="clear: both;">';
echo Html::closeTag('form');

