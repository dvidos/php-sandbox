<?php

echo '<h1>Create article</h1>';

echo '<p><a href="' . cms()->create_url('index') . '">All articles</a></p>';

echo cms()->load_view('_form', ['article' => $article ]);

