<?php
get_header();

while (have_posts()) {
    the_post();
    pageBanner();
    ?>

  <div class="container container--narrow page-section">

<?php

    $theParent = wp_get_post_parent_id(get_the_ID());

    if ($theParent) {
        ?>
    <div class="metabox metabox--position-up metabox--with-home-link">
      <p>
        <a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent); ?>">
        <i class="fa fa-home" aria-hidden="true"></i>
        Back to <?php echo get_the_title($theParent); ?></a>
        <span class="metabox__main"><?php the_title();?></span>
      </p>
    </div>
   <?php
}
    ?>


<?php

    $childsArray = get_pages(array(
        'child_of' => get_the_ID(),
    ));
    if ($theParent or $childsArray) {

        ?>
    <div class="page-links">
      <h2 class="page-links__title">
        <a href="<?php echo get_permalink($theParent); ?>">
          <?php echo get_the_title($theParent); ?>
        </a>
      </h2>
      <ul class="min-list">
      <?php

        $childsOf = 0;
        if ($theParent) {
            $childsOf = $theParent;
        } else {
            $childsOf = get_the_ID();
        }
        wp_list_pages(array(
            'title_li' => null,
            'child_of' => $childsOf,
            'sort_column' => 'menu_order',
        ));
        ?>
      </ul>
    </div>

  <?php }
  get_search_form();
  ?>

  </div>

    <?php
}

get_footer();
?>

