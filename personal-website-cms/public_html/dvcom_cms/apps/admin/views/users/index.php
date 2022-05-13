<?php

echo '<h1>Users</h1>';

echo '<p><a href="' . cms()->create_url('create') . '">Create new</a></p>';

echo '<table class="bordered">';
echo '<tr><th>Email</th><th>Is Admin</th><th>Last Login</th></tr>';

foreach ($users as $user)
{
	echo '<tr>';
	
	$url = cms()->create_url('update', ['id' => $user->id]);
	$link = Html::tag('a', ['href'=>$url], htmlentities($user->email));
	
	echo '<td>' . $link . '</a></td>';
	echo '<td>' . ($user->allow_admin ? 'Yes' : '&nbsp;') . '</td>';
	echo '<td>' . $user->last_login_at . '</td>';
	
	echo '</tr>';
}

echo '</table>';

