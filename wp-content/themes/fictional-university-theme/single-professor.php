<?php
get_header();

while (have_posts()) {
    the_post();
    pageBanner();

?>
  <div class="container container--narrow page-section">

  <div class="generic-content">
      <div class="row group">
          <div class="one-third">
              <img src="<?php the_post_thumbnail_url('professorPortrait'); ?>" alt="">
          </div>

          <?php 
            $likesCount = new WP_Query(array(
              'post_type' => 'like',
              'meta_query' => array(
                array(
                  'key' => 'liked_professor_id',
                  'compare' => '=',
                  'value' => get_the_ID()
                )
              )
            ));

            $likedIt = "no";
            if(is_user_logged_in()){
              $alreadyLiked = new WP_Query(array(
                'author' => get_current_user_id(),
                'post_type' => 'like',
                'meta_query' => array(
                  array(
                    'key' => 'liked_professor_id',
                    'compare' => '=',
                    'value' => get_the_ID()
                  )
                )
              ));

              if($alreadyLiked->found_posts){
                $likedIt = "yes";
              }
            }

          ?>

          <div class="two-thirds">
              <span class="like-box" data-professor="<?php the_ID(); ?>" data-exists="<?php echo $likedIt; ?>">
                <i class="fa fa-heart-o" area-hidden="true"></i>
                <i class="fa fa-heart" area-hidden="true"></i>
                <span class="like-count"><?php echo $likesCount->found_posts; ?></span>
              </span>
              <?php the_content(); ?>
          </div>
      </div>
  </div>
    <?php
    
      $relatedPrograms = get_field('related_programs');
      if($relatedPrograms){
        echo '<hr class="section-break">';
        echo '<h2 class="headline headline--medium">Subject(s) Taught</h2>';
        echo '<ul class="link-list min-list">';
        foreach($relatedPrograms as $program){
          echo '<li><a href="'. get_the_permalink($program) .'">' . get_the_title($program) . "</a></li>";
        }
        echo '</ul>';
      }
    ?>

  </div>


    <?php
}

get_footer();
?>
