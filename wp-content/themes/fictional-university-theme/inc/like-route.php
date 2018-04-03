<?php

add_action('rest_api_init', 'universityLikesApi');

function universityLikesApi()
{
    register_rest_route('university/v1', 'manageLike', array(
        'methods' => 'POST',
        'callback' => 'createLike',
    ));

    register_rest_route('university/v1', 'manageLike', array(
        'methods' => 'DELETE',
        'callback' => 'deleteLike',
    ));

    function createLike($data)
    {
        $professor = sanitize_text_field($data['professorId']);
        if (is_user_logged_in()) {

            $alreadyLiked = new WP_Query(array(
                'author' => get_current_user_id(),
                'post_type' => 'like',
                'meta_query' => array(
                    array(
                        'key' => 'liked_professor_id',
                        'compare' => '=',
                        'value' => $professor,
                    ),
                ),
            ));

            if ($alreadyLiked->found_posts == 0 && get_post_type($professor) == 'professor') {

                return wp_insert_post(array(
                    'post_type' => 'like',
                    'post_status' => 'publish',
                    'post_title' => 'New Like',
                    'meta_input' => array(
                        'liked_professor_id' => $professor,
                    ),
                ));
            } else {
                die("Not a valid professor id.");
            }
        } else {
            die("Only logged in users can like a professor.");
        }

    }

    function deleteLike($data)
    {
        $likeId = sanitize_text_field($data['like']);
        if (get_current_user_id() == get_post_field('post_author', $likeId) && get_post_type($likeId) == 'like') {
            wp_delete_post($likeId, true);
            return "Congrats, you deleted this like.";
        } else {
            die("You don't have permission to delete this like");
        }
    }
}
