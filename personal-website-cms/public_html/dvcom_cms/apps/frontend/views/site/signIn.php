<h1>Sign in</h1>

<?php

echo Html::openTag('form', ['method' => 'post']);

if (!empty($error))
{
	echo Html::tag('p', ['class' => 'error-message'], $error);
}

echo '<p>';
echo Html::label('Email', 'email') . '<br>';
echo Html::textField('email', $user->email, ['size' => 40]) . '<br>';
echo '</p>';

echo '<p>';
echo Html::label('Password', 'password') . '<br>';
echo Html::passwordField('password', '', ['size' => 40]) . '<br>';
echo '</p>';

echo '<p>';
echo Html::submit('Submit');
echo '</p>';


echo Html::closeTag('form');
