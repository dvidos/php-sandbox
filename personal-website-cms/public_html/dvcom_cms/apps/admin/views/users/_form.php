<?php

echo Html::openTag('form', ['method' => 'post']);
echo '<div style="float:left; width: 72%;">';


echo '<p>';
echo Html::label('Email', 'email') . '<br>';
echo Html::textField('email', $user->email, ['size' => 40]) . '<br>';
echo '</p>';

echo '<p>';
echo Html::label('Password (leave blank to keep the same)', 'password1') . '<br>';
echo Html::passwordField('password1', '', ['size' => 40]) . '<br>';
echo '</p>';

echo '<p>';
echo Html::label('Password (repeat)', 'password2') . '<br>';
echo Html::passwordField('password2', '', ['size' => 40]) . '<br>';
echo '</p>';


echo '</div><div style="float:right; width: 25%;">';


echo '<p>';
echo Html::label('Allow admin', 'allow_admin') . '<br>';
echo Html::checkBox('allow_admin', $user->allow_admin) . '<br>';
echo '</p>';

echo '<p>';
echo Html::label('Created At', 'created_at') . '<br>';
echo Html::textField('created_at', $user->created_at, ['size'=>20]) . '<br>';
echo '</p>';

echo '<p>';
echo Html::label('Updated At', 'updated_at') . '<br>';
echo Html::textField('updated_at', $user->updated_at, ['size'=>20]) . '<br>';
echo '</p>';

echo '<p>';
echo Html::submit('Save');
echo '</p>';


echo '</div><div style="clear: both;">';
echo Html::closeTag('form');
