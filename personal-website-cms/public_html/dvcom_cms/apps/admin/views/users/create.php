<?php

echo '<h1>Create user</h1>';

echo '<p><a href="' . cms()->create_url('index') . '">All users</a></p>';

echo cms()->load_view('_form', ['user' => $user ]);

