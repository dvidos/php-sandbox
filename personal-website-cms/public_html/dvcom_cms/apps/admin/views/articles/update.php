<?php

echo '<h1>Update article</h1>';

echo '<p><a href="' . cms()->create_url('index') . '">All articles</a>';
echo ' | <a href="' . cms()->create_url('/site/article', ['id' => $article->id]) . '" target="_blank">View Public</a>';
echo '</p>';

echo cms()->load_view('_form', ['article' => $article ]);

