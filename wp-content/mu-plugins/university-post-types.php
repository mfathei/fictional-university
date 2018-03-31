<?php

//  That is for install plugins add this line in wp-config.php
// define('FS_METHOD','direct');

function university_post_types()
{
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
        'menu_icon' => 'dashicons-calendar',
    ));
}

add_action('init', 'university_post_types');

function university_custom_query($query)
{
    if (!is_admin() && is_post_type_archive('event') && $query->is_main_query()) {
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric',
            ),
        ));
    }
}

add_action('pre_get_posts', 'university_custom_query');
