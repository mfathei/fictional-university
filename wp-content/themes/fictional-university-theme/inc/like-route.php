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

    function createLike(){
        return "Thanks for create like.";
    }

    function deleteLike(){
        return "Thansk for delete like.";
    }
}