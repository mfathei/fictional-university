<?php

require get_theme_file_path('/inc/search-route.php');


function getCacheVersion(){
    // return "1.0"; // for production
    return microtime();// for development
}


function pageBanner($args = array()){

    if(!$args['title']){
        $args['title'] = get_the_title();
    }

    if(!$args['subtitle']){
        $args['subtitle'] = get_field('page_banner_subtitle');
    }

    if(!$args['photo']){
        $bannerImage = get_field('page_banner_background_image'); 
        if($bannerImage){
            $args['photo'] = $bannerImage['sizes']['bannerImage'];
        } else {
            $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        }
    }

    ?>
    <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle']; ?></p>
      </div>
    </div>
  </div>
<?php } 


function university_files()
{
    wp_enqueue_script('google-map-scripts', '//maps.googleapis.com/maps/api/js?key=AIzaSyCHKpJEMZJs05FXsOy1NIueYvc4eyYkdtE', null, getCacheVersion(), true);
    wp_enqueue_script('university-main-scripts', get_theme_file_uri('/js/scripts-bundled.js'), null, getCacheVersion(), true);
    wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_main_style', get_stylesheet_uri(), null, getCacheVersion());
    wp_localize_script('university-main-scripts', 'universityData', array(
        'root_url' => esc_url(get_site_url()),
        'nonce' => wp_create_nonce('wp_rest')
    ));
}

add_action('wp_enqueue_scripts', 'university_files');

function theme_features(){
    // register_nav_menu('headerMenuLocation', 'Main Header Menu');
    // register_nav_menu('footerMenuOne', 'Explore Footer Menu');
    // register_nav_menu('footerMenuTwo', 'Learn Footer Menu');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('bannerImage', 1500, 350, true);
}

add_action('after_setup_theme' , 'theme_features');

function university_adjust_queries($query)
{

    if(!is_admin() && is_post_type_archive('campus') && $query->is_main_query()){
        $query->set('posts_per_page', -1);
    }

    if(!is_admin() && is_post_type_archive('program') && $query->is_main_query()){
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
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

function universityMapKey($api){
    $api['key'] = 'AIzaSyCHKpJEMZJs05FXsOy1NIueYvc4eyYkdtE';
    return $api;
}

add_filter('acf/fields/google_map/api', 'universityMapKey');

function university_custom_api(){
    register_rest_field('post', 'authorName', array(
        'get_callback' => function(){ return get_the_author(); }
    ));
}

add_action('rest_api_init', 'university_custom_api');

// redirect subscriber accounts out of admin and onto homepage
add_action('admin_init', 'redirectSubsToFrontEnd');

function redirectSubsToFrontEnd(){
    $currentUser = wp_get_current_user();

    if(count($currentUser->roles) == 1 && $currentUser->roles[0] == 'subscriber'){
        wp_redirect(site_url('/'));
        exit;
    }
}

// hiding admin bar for subscriber
add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar(){
    $currentUser = wp_get_current_user();

    if(count($currentUser->roles) == 1 && $currentUser->roles[0] == 'subscriber'){
        show_admin_bar(false);
    }
}

// for login page url
add_action('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl(){
    return esc_url(site_url('/'));
}

// for login page logo
add_action('login_enqueue_scripts', 'ourHeaderCSS');

function ourHeaderCSS(){
    wp_enqueue_style('university_main_style', get_stylesheet_uri(), null, getCacheVersion());
    wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
}

// for login page logo alt
add_action('login_headertitle', 'ourHeaderTitle');

function ourHeaderTitle(){
    return get_bloginfo('name');
}


add_filter('wp_insert_post_data', 'makeNotePrivate');

function makeNotePrivate($data){
    if($data['post_type'] == 'note' && $data['post_status'] != 'trash'){
        $data['post_status'] = "private";
    }
    return $data;
}