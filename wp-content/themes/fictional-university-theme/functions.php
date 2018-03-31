<?php

function getCacheVersion(){
    // return "1.0"; // for production
    return microtime();// for development
}

function university_files()
{
    wp_enqueue_script('university-main-scripts', get_theme_file_uri('/js/scripts-bundled.js'), null, getCacheVersion(), true);
    wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_main_style', get_stylesheet_uri(), null, getCacheVersion());
}

add_action('wp_enqueue_scripts', 'university_files');

function theme_features(){
    register_nav_menu('headerMenuLocation', 'Main Header Menu');
    register_nav_menu('footerMenuOne', 'Explore Footer Menu');
    register_nav_menu('footerMenuTwo', 'Learn Footer Menu');
    add_theme_support('title-tag');
}

add_action('after_setup_theme' , 'theme_features');

function university_adjust_queries($query)
{

    if(!is_admin() && is_post_type_archive('program') && $query->is_main_query()){
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', '-1');
    }

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

add_action('pre_get_posts', 'university_adjust_queries');
