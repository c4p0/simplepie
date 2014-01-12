<?php
/**
 * Group simplepie:rss module
 */
elgg_load_library('simplepie');

$group = elgg_get_page_owner_entity();

if ($group->rss_enable != "yes") {
	return true;
}

elgg_push_context('widgets');

$allowed_tags = '<a><p><br><b><i><em><del><pre><strong><ul><ol><li><img>';
$feed_url = $group->feed_url;
$content = '';

if ($group->canEdit()) {
	$content .= elgg_view_form("simplepie/save_group_feed", array(
		'id' => 'simplepie-form',
		'class' => $feed_url ? 'hidden' : '',
	), $vars);
}

if ($feed_url) {

// get widget settings
	$excerpt   = true;
	$post_date = true;
	$num_items = 7;
	$cache_location = elgg_get_data_path() . '/simplepie_cache/';
	if (!file_exists($cache_location)) {
		mkdir($cache_location, 0777);
	}
	$feed = new SimplePie($feed_url, $cache_location);

	// doubles timeout if going through a proxy
	//$feed->set_timeout(20);
	// only display errors to profile owner

	$num_posts_in_feed = $feed->get_item_quantity();
	if (!$num_posts_in_feed) {
		if (elgg_get_logged_in_user_guid() == elgg_get_page_owner_guid()) {
			$content .= '<p>' . elgg_echo('simplepie:notfound') . '</p>';
		}
	}

	// don't display more feed items than user requested
	if ($num_items > $num_posts_in_feed) {
	$num_items = $num_posts_in_feed;
}

	$feed_link = elgg_view('output/url', array(
		'href' => $feed->get_permalink(),
		'text' => $feed->get_title(),
	));

	// need to center
	$content .= "<h2 class=\"simplepie-heading\">$feed_link</h2>";
	$content .= '<ul class="simplepie-list">';
	foreach ($feed->get_items(0, $num_items) as $item) {
		$item_link = elgg_view('output/url', array(
			'href' => $item->get_permalink(),
			'text' => $item->get_title(),
		));

		if ($excerpt) {
			$text = strip_tags($item->get_description(true), $allowed_tags);
			$excerpt = elgg_get_excerpt($text);
		}

		if ($post_date) {
			$item_date_label = elgg_echo('simplepie:postedon');
			$item_date = $item->get_date('j F Y | g:i a');
			$post_date = "$item_date_label $item_date";
		}	

		$content .= <<<HTML
<li class="mbm elgg-item">
	<h4 class="mbs">$item_link</h4>
	<p class="elgg-subtext">$post_date</p>
	<div class="elgg-content">$excerpt</div>
</li>
HTML;
	}
        $content .= "</ul>";
}

elgg_pop_context();

if (!$content) {
        $content = '<p>' . elgg_echo('simplepie:none') . '</p>';
}
if ($group->canEdit()) {
        $edit = elgg_view('output/url', array(
                'href' => '#simplepie-form',
                'text' => elgg_echo('edit'),
                'rel' => 'toggle'
        ));
} else {
        $edit = false;
}

echo elgg_view('groups/profile/module', array(
        'title' => elgg_echo('RSS Group'),
        'content' => $content,
        'all_link' => $edit,
));
