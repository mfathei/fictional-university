<?php

add_action('rest_api_init', 'universityLikesApi');

function universityLikesApi(){
    register_rest_route('university/v1', 'manageLike', array(
        'methods' => 'POST',
        'callback'=> 'createLike'
    ));

    register_rest_route('university/v1', 'manageLike', array(
        'methods' => 'DELETE',
        'callback'=> 'deleteLike'
    ));

    function createLike($data){
        wp_insert_post(array(
            'post_type' => 'like',
            'post_status' => 'publish',
            'post_title' => 'New Like',
            'meta_input' => array(
                'liked_professor_id' => $data['professorId']
            )
        ));
    }

    function deleteLike(){
        return "Thansk for delete like.";
    }
}