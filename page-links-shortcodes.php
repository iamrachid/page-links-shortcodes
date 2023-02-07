<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https:///
 * @since             1.0.0
 * @package           Page_Links_Shortcodes
 *
 * @wordpress-plugin
 * Plugin Name:       Page Links Shortcodes
 * Plugin URI:        https:///
 * Description:       Page Links Shortcodes uses the shortcode [page-links tags=""] for displaying a list of links that takes you to other pages filtered by tags
 * Version:           1.0.0
 * Author:            Rachid EL Aissaoui
 * Author URI:        https:///
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       page-links-shortcodes
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PAGE_LINKS_SHORTCODES_VERSION', '1.0.0' );

function page_links_shortcodes( $atts = [], $content = null, $tag = '' ) {
    $atts = shortcode_atts(array(
        'tag' => ''
    ), $atts, 'page-links');

    $tax_term = sanitize_text_field( $atts['tag'] );

    $tax_term = str_replace( ', ', ',', $tax_term );
    $tax_term = explode( ',', $tax_term );

    if ('' == $atts['tag'])
        $args = array(
            'post_type' => 'page',
            'nopaging'  => true,
            'order' => 'ASC',
            'orderby' => 'title',
        );
    else
        $args = array(
            'post_type' => 'page',
            'nopaging'  => true,
            'order' => 'ASC',
            'orderby' => 'title',
            'tax_query' => array(
                array(
                    'taxonomy'         => 'city-pages',
                    'field'            => 'slug',
                    'terms'            => $tax_term,
                ),
            ),
        );

    $posts = new WP_Query( $args );


    $inner = '';
    while ( $posts->have_posts() ) :
        $posts->the_post();

        $item = '<li class="col-xs-12 col-sm-6 col-md-4"><a href="' . get_permalink() . '">'.get_the_title().'</a></li>';
        $inner .= $item;

    endwhile;
    wp_reset_postdata();


    return '<ul class="row">'.$inner.'</ul>';

}


function add_style(){
    wp_enqueue_style( 'your-stylesheet-name', plugins_url('/style.css', __FILE__), false, '1.0.0');
}

add_action('wp_enqueue_scripts', 'add_style');
add_shortcode('page-links', 'page_links_shortcodes');