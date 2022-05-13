<?php
/* This template should present PREV / NEXT buttons, direct pages buttons, or any combination of them.
 * variables passed: 
 * - $currentPage, one based
 * - $totalPages
 * _ $url, string, where __PAGE__ should be replicated for the page number.
 */
 
// echo Html::tag('p', ['style'=>'border:1px solid red;'], 'currentPage = ' . $currentPage . ', totalPages = ' . var_export($totalPages, true) . ', baseUrl = ' . var_export($baseUrl, true));

$html = '';

if ($currentPage > 1)
{
	$html .= Html::link('Prev', array_merge($baseUrl, ['page' => $currentPage - 1]), ['class' => 'prev']);
}

if ($currentPage < $totalPages)
{
	$html .= Html::link('Next', array_merge($baseUrl, ['page' => $currentPage + 1]), ['class' => 'next']);
}

echo Html::tag('p', ['class' => 'pagination'], $html); 
