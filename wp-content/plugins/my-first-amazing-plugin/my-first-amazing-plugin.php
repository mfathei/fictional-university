<?php
/**
Plugin Name: My First Amazing Plugin
Description: This plugin will change your life.
 */

add_filter('the_content', 'amazingContentEdits');

function amazingContentEdits($content)
{
    $content = str_replace('Ipsum', '*****', $content);
    $content = $content . "<br><p>All content belongs Fictionl University.</p>";
    return $content;
}

// shortCode in about-us page like "We have [programsCount] programs."
add_shortCode('programsCount', 'programsCountFunction');

function programsCountFunction(){
    return 3 * 2 + 1;
}
