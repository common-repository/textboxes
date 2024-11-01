<?php
/*
Plugin Name: Textboxes
Description: Add nice text boxes using shortcodes. Code based on http://www.beliefmedia.com/wordpress-text-box
Author: Team Bright Vessel
Version: 0.1.3.1
Author URI: http://brightvessel.com/
*/

if (!defined('ABSPATH'))
    die();


function brightvessel_textbox($atts, $content = null) {
    $uploads_dir = wp_upload_dir();

    $atts = shortcode_atts(array(
        'url' => false,
        'type' => false,
        'title' => false,
        'image' => false,
        'entity' => '',
        'class' => '',
        'padding' => '8px 30px 10px 30px',
        'textcolor' => '',
        'bgcolor' => '',
        'bordercolor' => '',
        'border' => '',
        'bordertype' => '',
        'borderradius' => '0px',
        'width' => '90%',
        'titlecolor' => '',
        'titlebgcolor' => '',
        'bgoptions' => ''
    ), $atts);


    switch ($atts['type']) {

        case 'caution':
            $options = array('bgoptions' => 'no-repeat 10px 50%','textcolor' => '#555555', 'bgcolor' => '#FFFFBF','titlebgcolor' => '#FFFFBF','bordercolor' => '#09A3E2', 'border' => '1px', 'bordertype' => 'solid', 'url' =>  plugins_url( 'media/', __FILE__ ), 'image' => 'caution.png', 'titlecolor' => '#ffffff', 'entity' => '&excl;');
            break;
        case 'alert':
            $options = array('bgoptions' => 'no-repeat 10px 50%','textcolor' => '#555555', 'bgcolor' => '#CFFFCC', 'titlebgcolor' => '#CFFFCC', 'bordercolor' => '#88C882','border' => '1px', 'bordertype' => 'solid', 'url' =>  plugins_url( 'media/', __FILE__ ), 'image' => 'alert.png', 'titlecolor' => '#000000', 'entity' => '&check;');
            break;
        case 'warning':
            $options = array('bgoptions' => 'no-repeat 10px 50%','textcolor' => '#ffffff', 'bgcolor' => '#D84242', 'titlebgcolor' => '#D84242', 'bordercolor' => '#C01A19', 'border' => '1px','bordertype' => 'solid', 'url' =>  plugins_url( 'media/', __FILE__ ), 'image' => 'warning.png', 'titlecolor' => '#ffffff', 'entity' => '&cross;');
            break;
        case 'bm':
            $options = array('bgoptions' => 'no-repeat 10px 50%','textcolor' => '#ffffff', 'bgcolor' => '#09A3E2', 'titlebgcolor' => '#09A3E2', 'bordercolor' => '#000000','border' => '1px','bordertype' => 'solid', 'titlecolor' => '#ffffff', 'entity' => '&plus;');
            break;

        /* Default */

        default:
            $options = array('bgoptions' => 'no-repeat 10px 50%', 'textcolor' => '#ffffff', 'titlecolor' => '#ffffff', 'bgcolor' => '#09A3E2', 'titlebgcolor' => '#000000', 'bordercolor' => '#000000', 'border' => '1px', 'bordertype' => 'solid', 'image' => false, 'entity' => '&ofcir;');
            break;
    }



    /* Overwrite the option defaults */
    $options = array_replace($options, array_filter($atts));
    if(isset($options['url']) && $options['url'] !== '' && $options['padding'] == '8px 30px 10px 30px')
        $options['padding'] = '15px 30px 18px 65px';

    if(!isset($options['url']))
        $options['url'] = $uploads_dir['baseurl'];


    /* Build our little box with a title */
    $entity = $options['entity'].' ';
    if($atts['entity'] == 'null')
        $entity = '';

    $return = '<p><div class="'.$options['class'].'" style="border-radius: '.$options['borderradius'].'; width: ' . $atts['width'] . '; margin: auto; border: ' . $options['bordercolor'] . ' ' . $options['border'] . ' ' . $options['bordertype'] . '">';
    if ($atts['title'] !== false) $return .= '<div style="background-color: ' . $options['titlebgcolor'] . '; padding: 2px 30px 2px 30px ; font-weight: bold; color: ' .  $options['titlecolor'] . ';">' . $entity.$atts['title'] . '</div>';

    /* Content */




    $return .= '<div style="padding: '.$options['padding'].'; color: ' . $options['textcolor'] . ';';
    $return .= ( ($options['image'] != false) && (array_key_exists('image', $options)) ) ? ' background: ' . $options['bgcolor'] . ' url(' . $img_url = (stripos($options['image'], 'http') !== false) ? $options['image'].') '.$options['bgoptions'].';' : $options['url'] . '/' . $options['image'] . ') '.$options['bgoptions'].';' : ' background-color: ' . $options['bgcolor'] . ';';
    $return .= '">' . do_shortcode($content) . '</div></div></p>';


    return $return;
}
add_shortcode('bvtextbox','brightvessel_textbox');

function bv_textbox_create_support_notice() {
    $class = 'notice notice-warning';
    $message ='[Textboxes] If you need dedicated/professional assistance with this plugin or just want an expert to get your site built and or to run the faster, you may hire us at';

    printf( '<div class="%1$s"><p>%2$s <a href="https://www.brightvessel.com/" target="_blank">Bright Vessel</a>. <small><a href="?bvtbclose=true">[x]</a></small></p></div>', esc_attr( $class ), esc_html( $message ) );
}

function bv_textbox_check_notice(){
    if(isset($_GET['bvtbclose']) && $_GET['bvtbclose'] == 'true'){
        add_option('bvtbclose',1);
    }

    if(intval(get_option('bvtbclose')) !== 1){
        add_action( 'admin_notices', 'bv_textbox_create_support_notice' );
    }
}

add_action('admin_init','bv_textbox_check_notice');