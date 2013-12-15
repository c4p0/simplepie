<?php

$group = get_entity(get_input('group_guid'));
$feed_url = get_input('feed_url');

if ($group && $group->canEdit()) {
	$group->feed_url = $feed_url;
}
