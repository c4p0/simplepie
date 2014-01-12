<?php

$group = get_entity((int)get_input('group_guid'));
$feed_url = get_input('feed_url');

if (!simplepie_is_url($feed_url)) {
	register_error (elgg_echo("simplepie:invalid_url"));
	forward(REFERER);
}

if (!elgg_instanceof($group, 'group') || !group->canEdit()) {
	forward(REFERER);
}
$group->feed_url = $feed_url;
