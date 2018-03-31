<?php

//  That is for install plugins add this line in wp-config.php
// define('FS_METHOD','direct');

function university_post_types(){
    register_post_type('event', array(
        // 'supports' => array('title', 'editor', 'excerpt', 'custom-fields'), use Advanced custom fields plugin
        'supports' => array('title', 'editor', 'excerpt'),
        'rewrite' => array('slug' => 'events'),
        'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Events',
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event',

        ),
        'menu_icon' => 'dashicons-calendar'
    ));
}

add_action('init', 'university_post_types');
