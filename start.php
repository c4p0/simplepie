<?php
/**
 * Simplepie Plugin
 *
 * Loads the simplepie feed parser library and provides a widget
 */

elgg_register_event_handler('init', 'system', 'simplepie_init');

function simplepie_init() {
	elgg_register_widget_type(
			'feed_reader',
			elgg_echo('simplepie:widget'),
			elgg_echo('simplepie:description'),
			'all',
			true
			);

	elgg_extend_view('css/elgg', 'simplepie/css');

	$lib = elgg_get_plugins_path() . 'simplepie/vendors/simplepie.inc';
	elgg_register_library('simplepie', $lib);

        // Add group option
        add_group_tool_option('rss', elgg_echo('simplepie:enablerss'), false);
        elgg_extend_view('groups/tool_latest', 'simplepie/group_module');

	elgg_register_action('simplepie/group_module', elgg_get_plugins_path() . 'simplepie/actions/simplepie/group_module.php');
}

