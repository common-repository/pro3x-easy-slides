<?php
/*
Plugin Name: Pro3x Easy Slides
Plugin URI: http://acosoft.pro3x.com/pro3x-easy-slides/
Description: Simple and free slider plugin for Wordpress. This plugin is wrapper for jQuery Easy Slider script developed by Alen Grakalic.
Version: 1.1
Author: Aleksandar Zgonjan
Author URI: http://www.pro3x.com
*/

class EasySlides extends WP_Widget
{
    function EasySlides()
    {
        $widget    = array('description' => 'Create Featured Slides');
        $control   = array('width' => 400);
        parent::WP_Widget(false, "Pro3x Easy Slides", $widget, $control);
    }

    function widget($args, $instance)
    {
        extract($args);

        echo $before_widget;

        if(!empty($instance['image']))
            echo '<img alt="image" src="' . $instance['image'] . '" />';
            
        echo '<div class="info">';

        if(!empty($instance['title']))
        {
            echo $before_title;

            if(!empty($instance['titleLink']))
                echo '<a href="' . $instance['titleLink'] . '">';

            echo $instance['title'];

            if(!empty($instance['titleLink']))
                echo '</a>';
            
            echo $after_title;
        }

        echo $instance['message'];

        if(!(empty($instance['link']) || empty($instance['extra'])))
        {
            echo '<div>';

            if(!empty($instance['link']))
                echo '<a class="slide-button" href="' . $instance['link'] . '">' . $instance['desc'] . '</a>';

            if(!empty($instance['extra']))
                echo '<a class="slide-button" href="' . $instance['extra'] . '">' . $instance['extraDesc'] . '</a>';

            echo '</div>';
        }

        echo '</div>';

        echo $after_widget;
    }

    function form($instance)
    {
        extract($instance);

        $xTitle     = $this->get_field_name('title');
        $xTitleLink = $this->get_field_name('titleLink');
        $xLink      = $this->get_field_name('link');
        $xDesc      = $this->get_field_name('desc');
        $xImage     = $this->get_field_name('image');
        $xMessage   = $this->get_field_name('message');
        $xExtra     = $this->get_field_name('extra');
        $xExtraDesc = $this->get_field_name('extraDesc');
        
        echo "<p> Slide title";
        echo "<input class='widefat' type='text' name='$xTitle' value='$title' />";
        echo "</p>";

        echo "<p> Slide title URL";
        echo "<input class='widefat' type='text' name='$xTitleLink' value='$titleLink' />";
        echo "</p>";

        echo "<p> Image URL";
        echo "<input class='widefat' type='text' name='$xImage' value='$image' />";
        echo "</p>";

        echo "<p> Slide content";
        echo "<textarea cols='20' rows='5' class='widefat' name='$xMessage'>$message</textarea>";
        echo "</p>";

        echo "<p> Left link button";
        echo "<input class='widefat' type='text' name='$xDesc' value='$desc' />";
        echo "</p>";

        echo "<p> Left link button URL";
        echo "<input class='widefat' type='text' name='$xLink' value='$link' />";
        echo "</p>";

        echo "<p> Right link button";
        echo "<input class='widefat' type='text' name='$xExtraDesc' value='$extraDesc' />";
        echo "</p>";

        echo "<p> Right link button URL";
        echo "<input class='widefat' type='text' name='$xExtra' value='$extra' />";
        echo "</p>";        
    }
}

// register_widget('EasySlides');
add_action('widgets_init', create_function('', 'return register_widget("EasySlides");'));

function ConfigureSlider()
{
  register_sidebar(array(
    'name' => 'Pro3x Easy Slides Sidebar',
    'id' => 'home-page-featured',
    'before_widget' => '<li id="%1$s" class="widgetcontainer %2$s"><div class="slide-content">',
    'after_widget' => '</div></li>',
      'before_title' => '<h2>',
      'after_title' => '</h2>',
  ));

  wp_enqueue_script("jquery");

  //WP_PLUGIN_URL
  $uri = get_bloginfo('stylesheet_directory');
  wp_enqueue_script("easy-slider", WP_PLUGIN_URL . "/pro3x-easy-slides/easySlider1.7.js");
  wp_enqueue_style('easy-slider', WP_PLUGIN_URL . '/pro3x-easy-slides/easySlider.css');
}

add_action("init", 'ConfigureSlider');

function StartSlider($slider)
{
    echo '<script type="text/javascript">';
    echo 'jQuery(document).ready(function($) {';

    echo '$("' . $slider . '").easySlider(
    { 
        auto: true, 
        pause: 5000, 
        vertical: false, 
        continuous: true, 
        numeric: true, 
        numericId: "controls" 
    });';

    echo '});';
    echo '</script>';
}

function ShowSlider($slider)
{
    if(is_front_page() && is_active_sidebar('home-page-featured'))
    {
        echo "\n\n\n";
        echo "<!-- Pro3x Easy Slides Start -->\n";
        echo '<div class="Pro3xEasySlidesBox">';
        echo    '<div id="' . $slider . '">';

        if(is_active_sidebar('home-page-featured'))
        {
            echo        '<ul class="xoxo">';
            dynamic_sidebar("home-page-featured");
            echo        "</ul>";
        }
        
        echo    "</div>";
        echo "</div>";

        StartSlider('#' . $slider);

        echo "\n<!-- Pro3x Easy Slides End -->";
        echo "\n\n\n";
    }
}

function DisplaySlider()
{
    ShowSlider('Pro3xEasySlides');
}

add_action('displayslider', 'DisplaySlider');
?>
