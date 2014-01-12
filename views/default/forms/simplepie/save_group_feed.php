<?php
/**
 * Simplepie feed reader widget settings
 */

// set default value

$url_label = elgg_echo("simplepie:feed_url");
$url_textbox = elgg_view('input/text', array(
	'name' => 'feed_url',
	'id'=> 'feed_url',
	'value' => $vars['entity']->feed_url,
));

$group_field = elgg_view('input/hidden', array(
	'name' => 'group_guid',
	'value' => $vars['entity']->guid,
));

$save_button = elgg_view('input/submit', array(
	'value' => elgg_echo('save'),
));




echo <<<HTML
<div>
	<label for="feed_url">$url_label</label>
        $url_textbox
        $group_field
</div>
<div>
        $save_button
</div>
HTML;



