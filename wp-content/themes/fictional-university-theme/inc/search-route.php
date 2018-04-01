<?php

add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch()
{
    register_rest_route('university/v1', 'search', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'universitySearchResults',
    ));
}

function universitySearchResults($data)
{
    $mainQuery = new WP_Query(array(
        'post_type' => array('post', 'professor', 'page', 'event', 'program', 'campus'),
        's'         => sanitize_text_field($data['term'])
    ));

    $results = array(
        'generalInfo'       => array(),
        'professors'        => array(),
        'events'            => array(),
        'programs'          => array(),
        'campuses'          => array()
    );

    while($mainQuery->have_posts()){
        $mainQuery->the_post();
        $type = get_post_type();

        if($type == 'post' || $type == 'page'){
            array_push($results['generalInfo'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'postType'  => $type,
                'authorName'=> get_the_author()
            ));
        } else if($type == 'professor'){
            array_push($results['professors'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
            ));
        } else if($type == 'program'){
            array_push($results['programs'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        } else if($type == 'event'){
            $eDate = new DateTime(get_field('event_date'));
            $description = null;
            if(has_excerpt()){
                $description = get_the_excerpt();
            } else {
                $description = wp_trim_words(get_the_content(), 18);
            }
            array_push($results['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eDate->format('M'),
                'day'   => $eDate->format('d'),
                'description' => $description
            ));
        } else if($type == 'campus'){
            array_push($results['campuses'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }
        
    }

    return $results;
}
