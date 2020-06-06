<?php
add_action( 'wp_enqueue_scripts', 'child_theme_enqueue_styles' );
function child_theme_enqueue_styles() {
    // don't need to enqueue child style because parent uses get_stylesheet_uri()
    // which is the child style
    // see "To enqueue or not to enqueue" section of: https://digwp.com/2016/01/include-styles-child-theme/
    // though they don't give the solution below which took a bit of trial and error
    // note that the guidance to load both parent and child given by WordPress here:
    // https://developer.wordpress.org/themes/advanced-topics/child-themes/#3-enqueue-stylesheet
    // is wrong
    // similarly wrong is: https://make.wordpress.org/training/handbook/lesson-plans/theme-school/child-themes/child-themes-twentyseventeen/
    // unfortunately while they mention not having to enqueue the child style
    // there isn't an example, in fact the examples given are incorrect or out of date
    // for the very themes I'm using here
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

add_filter( 'wp_nav_menu_items', 'add_extra_item_to_nav_menu', 10, 2 );
function add_extra_item_to_nav_menu( $items, $args ) {
    if ($args->theme_location == 'top') {
//        $items .= '<li>' . wp_loginout( $_SERVER['REQUEST_URI'], false ) . '</li>';
        $items .= '<li>' . get_search_form( false ) . '</li>';
    }
    return $items;
}

function modify_login_logo() {
    $logo_style = '<style type="text/css">';
    $logo_style .= '#login h1 a {background-image: url(' . get_stylesheet_directory_uri() . '/login/logo.png) !important;}';
    $logo_style .= '</style>';
    echo $logo_style;
}
//add_action('login_head', 'modify_login_logo');
add_action('login_enqueue_scripts', 'modify_login_logo');

function custom_login_url() {
    return home_url();
}
add_filter('login_headerurl', 'custom_login_url');

// changing the alt text on the logo to show your site name
function mb_login_title() { return "Powered by TRobertson"; }
add_filter( 'login_headertext', 'mb_login_title' );

// remove website field from comment form
function wpb_disable_comment_url($fields) { 
unset($fields['url']);
return $fields;
}
add_filter('comment_form_default_fields','wpb_disable_comment_url');

// change comment form cookie comment
add_filter( 'comment_form_default_fields', 'wc_comment_form_change_cookies' );
function wc_comment_form_change_cookies( $fields ) {
	$commenter = wp_get_current_commenter();

	$consent   = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';

	$fields['cookies'] = '<p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />' .
					 '<label for="wp-comment-cookies-consent">'.__('Save my name and email in this browser for the next time I comment.', 'textdomain').'</label></p>';
	return $fields;
}

// filter html in comments
function wpb_comment_post( $incoming_comment ) {
    $incoming_comment['comment_content'] = htmlspecialchars($incoming_comment['comment_content']);
    $incoming_comment['comment_content'] = str_replace( "'", '&apos;', $incoming_comment['comment_content'] );
    return( $incoming_comment );
}
function wpb_comment_display( $comment_to_display ) {
    $comment_to_display = str_replace( '&apos;', "'", $comment_to_display );
    return $comment_to_display;
}
add_filter( 'preprocess_comment', 'wpb_comment_post', '', 1);
add_filter( 'comment_text', 'wpb_comment_display', '', 1);
add_filter( 'comment_text_rss', 'wpb_comment_display', '', 1);
add_filter( 'comment_excerpt', 'wpb_comment_display', '', 1);
remove_filter( 'comment_text', 'make_clickable', 9 );


// reCAPTCHA
/**
 * Google recaptcha add before the submit button
 * from: https://www.oueta.com/wordpress/add-google-recaptcha-to-wordpress-comments-without-plugin/
 * register w/ Google at https://www.google.com/recaptcha/admin#list
 * add site and secret keys below at "your_site_key" and 'your_secret_key'
 */
function add_google_recaptcha($submit_field) {
    $submit_field['submit_field'] = '<div class="g-recaptcha" data-sitekey="your_site_key"></div><br>' . $submit_field['submit_field'];
    return $submit_field;
}
if (!is_user_logged_in()) {
    add_filter('comment_form_defaults','add_google_recaptcha');
}
/**
 * Google recaptcha check, validate and catch the spammer
 */
function is_valid_captcha($captcha) {
$captcha_postdata = http_build_query(array(
                            'secret' => 'your_secret_key',
                            'response' => $captcha,
                            'remoteip' => $_SERVER['REMOTE_ADDR']));
$captcha_opts = array('http' => array(
                      'method'  => 'POST',
                      'header'  => 'Content-type: application/x-www-form-urlencoded',
                      'content' => $captcha_postdata));
$captcha_context  = stream_context_create($captcha_opts);
$captcha_response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify" , false , $captcha_context), true);
if ($captcha_response['success'])
    return true;
else
    return false;
}
 
function verify_google_recaptcha() {
$recaptcha = $_POST['g-recaptcha-response'];
if (empty($recaptcha))
    wp_die( __("<b>ERROR:</b> please select <b>I'm not a robot!</b><p><a href='javascript:history.back()'>« Back</a></p>"));
else if (!is_valid_captcha($recaptcha))
    wp_die( __("<b>Sorry, you seem to be a robot!</b>"));
}
if (!is_user_logged_in()) {
    add_action('pre_comment_on_post', 'verify_google_recaptcha');
}