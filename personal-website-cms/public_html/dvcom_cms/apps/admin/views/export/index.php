<?php

echo '<h1>Export</h1>';

echo '<p>Click the button to export database</p>';

echo Html::openTag('form', ['method' => 'post', 'action'=> cms()->create_url('export') ]);

echo Html::submit('Export', ['name' => 'export']);

echo Html::closeTag('form');


