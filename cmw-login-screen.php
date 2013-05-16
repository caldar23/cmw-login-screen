<?php
/*
Plugin Name: CloneMyWebsite Login Screen
Plugin URI: http://www.clonemywebsite.com/
Description: This handy plugin allows you to customize your login screen. This is great if you're managing a membership and you want to brand the wp-login.php page to your membership.
Version: 1.2.0
Author: Andrew Myers
Author URI: http://www.clonemywebsite.com
License: GPL2
*/

/* Notes:
	1.2.0 - Now has the option to manually override height and width for the logo.

*/

// Activation of the plugin
register_activation_hook( __FILE__, 'cmw_login_screen_activation' );
function cmw_login_screen_activation() {
	// Check whether or not the 'cmw_login_screen_options' exists. If not, create new one.
    if ( ! get_option( 'cmw_login_screen_options' ) ) {
        $options = array(
            'logo' => '',
			'logo_url' => '',
            'logo_image_title' => '',
            'logo_message' => '',
			'footer_message' => '',
        );
        update_option( 'cmw_login_screen_options', $options );
    }     
}


// Makes the admin page link
add_action('admin_menu', 'cmw_login_screen_admin_actions');	

// makes the admin page, using the function cmw_login_screen_admin_page 
function cmw_login_screen_admin_actions() {
			$cmw_login_screen_options_page = add_options_page('Login Screen', 'Login Screen', 'manage_options', 'LoginScreen', 'cmw_login_screen_admin_page');
			add_action( 'admin_print_scripts-' . $cmw_login_screen_options_page, 'cmw_login_screen_print_scripts' ); 
}		

// The actual admin page content
function cmw_login_screen_admin_page() {  ?>
	<div class='wrap'>
        <?php screen_icon(); ?><h2>CloneMyWebsite Login Screen Customizer</h2>
        <form action='options.php' method='post'>
            <?php settings_fields( 'cmw_login_screen_options' ); ?>
            <?php do_settings_sections( 'cmw_login_screen' ); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php 
}

//Calls the functions with the content into the page
function cmw_login_screen_add_options() {
    register_setting( 'cmw_login_screen_options', 'cmw_login_screen_options', 'cmw_login_screen_options_validate' );
    add_settings_section( 'cmw_login_screen_section', 'Options', 'cmw_login_screen_section_callback', 'cmw_login_screen' );
    add_settings_field( 'cmw_login_screen_logo', 'Login Screen Logo', 'cmw_login_screen_logo_callback', 'cmw_login_screen', 'cmw_login_screen_section' );
	add_settings_field( 'cmw_login_screen_logo_url', 'Login Screen Logo URL', 'cmw_login_screen_logo_url_callback', 'cmw_login_screen', 'cmw_login_screen_section' );
	add_settings_field( 'cmw_login_screen_logo_image_title', 'Login Screen Logo Image Title', 'cmw_login_screen_logo_image_title_callback', 'cmw_login_screen', 'cmw_login_screen_section' );
	add_settings_field( 'cmw_login_screen_logo_message', 'Login Screen Logo Message', 'cmw_login_screen_logo_message_callback', 'cmw_login_screen', 'cmw_login_screen_section' );
	add_settings_field( 'cmw_login_screen_footer_message', 'Login Screen Footer Message', 'cmw_login_screen_footer_message_callback', 'cmw_login_screen', 'cmw_login_screen_section' );
}
add_action( 'admin_init', 'cmw_login_screen_add_options' );

//Saves the info to the database
function cmw_login_screen_options_validate($values) { 
    foreach ( $values as $n => $v ) 
        //$values[$n] = esc_url($v);
    return $values; 
}	


function cmw_login_screen_section_callback() { /* You can put instructions here. */ };	


function cmw_login_screen_logo_callback() {
    $options = get_option( 'cmw_login_screen_options' ); 
	$logo = esc_url($options["logo"]);
	if (! $logo) { $show = 'display:none'; $hide = ''; } else { $show = ''; $hide = 'display:none'; }
	?>
    
	<p>this is the instructions. Um... click the upload button and either upload an image or choose one from your media library. You'll want the image to be <b>no wider than 323 pixels wide.</b> Kinda important, otherwise things get kinda wonky. And wonky in a bad way... Also making your image's background transparent is a great idea.</p>
	<div id="logo-preview" class="_show-logo-uploader" style="height:105px; <?php echo $show ;?> ">
        <b> Preview:</b> <i>Click to change the image.</i> <br />
        <div style="float:left; width:300px; border: 1px solid; border-color:#CCC; padding:5px;">
            <div class="logo-preview" style="background-color:#FBFBFB;"> 
                <img style='max-width: 300px; display: block;' src='<?php echo $logo; ?>' class='preview-upload'/>
            </div>
        </div>
    </div>
    <div id="logo-uploader" class="upload" style="height:105px; <?php echo $hide ;?> ">
        <input type='text' id='cmw_login_screen_logo' class='regular-text text-upload' name='cmw_login_screen_options[logo]' value='<?php echo esc_url($options["logo"]); ?>'/>
        <input type='button' id="cmw_login_screen_logo" class="button button-upload button-primary" value='Upload a logo'/>
        <input type='button' id="cmw_login_screen_logo_cancel" class="button cancel-upload _show-logo-uploader" value='Back'/></br>

    </div>
<div id="advanced" class="_advanced" style="height:105px;  <?php echo $show ;?>">
    	<p>Does it look squished on the login page? <a>Click here for some advanced settings.</a></p>
    </div>
    <div id="advanced-settings" style="height:105px; <?php echo $hide ;?>">
		<p><label name='cmw_login_screen_options[advanced]'> Use manual dimensions? </label> <input type='checkbox' id='cmw_login_screen_advanced' name='cmw_login_screen_options[advanced]' <?php if ( $options["advanced"] === "Advanced") echo 'checked'; ?> value='Advanced'/></p>
		<p><label name='cmw_login_screen_options[width]'> Width </label> <input type='text' id='cmw_login_screen_width' name='cmw_login_screen_options[width]' value='<?php echo $options["width"]; ?>'/><br />
        <label name='cmw_login_screen_options[height]'> Height </label><input type='text' id='cmw_login_screen_height' name='cmw_login_screen_options[height]' value='<?php echo $options["height"]; ?>'/></p>

    </div>
<?php
};

function cmw_login_screen_logo_url_callback() { 
	$options = get_option( 'cmw_login_screen_options' ); 
	$logo_url = esc_url($options["logo_url"]);
	
	?>
	<p>This is probably something you won't need to change, it simply links back to your site url (<?php echo home_url(); ?>). If you want to change it so it goes somewhere else, like a sales page for your membership, or some other crazy page, simply enter a url below.</p>
	 URL: <input type='text' id='cmw_login_screen_logo_url' class='regular-text text-upload' name='cmw_login_screen_options[logo_url]' value='<?php echo $logo_url ?>'/>
<?php
};

function cmw_login_screen_logo_image_title_callback() { 
	$options = get_option( 'cmw_login_screen_options' ); 
	$logo_image_title = $options["logo_image_title"];
	
	?>
    <p>This is simply the text that appears when you hover over the logo with your mouse. It defaults to your tagline that you can set in your general settings ("<?php echo get_bloginfo('description'); ?>"). You can change it below. I suggest you keep it short and simple, don't try to stick too much in there. </p> 
    Logo Hover Text: <input type='text' id='cmw_login_screen_logo_image_title' class='regular-text' name='cmw_login_screen_options[logo_image_title]' value='<?php echo $logo_image_title ?>'/>
<?php
};

function cmw_login_screen_logo_message_callback() { 
	$options = get_option( 'cmw_login_screen_options' ); 
	$logo_message = $options["logo_message"];
	?>
    <p>Here's a handy area that fits between your logo and your login box. This is really useful to stick some text that you want people to see before they log in. We've used it for something as simple as "Public Beta" and as complex as a three paragraph warning about the passwords being changed (the shorter the better, that warning was FUUUUGLY). <b>HTML Friendly ;)</b></p>
    
    Logo Message:<br />
	<textarea name="cmw_login_screen_options[logo_message]" type="textarea" style="margin:0;height:10em;width:310px;" ><?php echo $logo_message; ?></textarea>
<?php
};

function cmw_login_screen_footer_message_callback() { 
	$options = get_option( 'cmw_login_screen_options' ); 
	$footer_message = $options["footer_message"];
	?>
    <p>Here's a handy area that fits under your login box. This is really useful but much harder to pull off nicely. Just makin' ya aware of what you're up for. <b>HTML Required ;)</b></p>
    
    Footer Message:<br />
    <textarea name="cmw_login_screen_options[footer_message]" type="textarea" style="margin:0;height:10em;width:310px" ><?php echo $footer_message; ?></textarea>
<?php
};

function cmw_login_screen_print_scripts() {
	wp_enqueue_script( 'cmw_login_screen_scripts', plugins_url( 'scripts/scripts.js', __FILE__ ) );
	wp_enqueue_media();
};

		

// Output

//---- Logo
function cmw_login_screen_login_logo() { 

	$options = get_option( 'cmw_login_screen_options' ); 
	$logo = esc_url($options["logo"]);
	
	$width = $options["width"] . "px";
	$height = $options["height"]. "px";
?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url('<?php echo $logo; ?>');	
			<?php if ( $options["advanced"] === "Advanced") { ?>
			background-size:<?php echo $width; ?> <?php echo $height; ?>;
			<?php } ?>
        }
    </style>
<?php 
}
//--- end logo


//---- logo url
function cmw_login_screen_login_logo_url() {
	$options = get_option( 'cmw_login_screen_options' ); 
	$logo_url = esc_url($options["logo_url"]);
	if ( $logo_url == NULL ) { $logo_url = home_url(); }
	
    return $logo_url ;
}
//---- end logo url


//---- logo image title
function cmw_login_screen_login_logo_title() {
	
	$options = get_option( 'cmw_login_screen_options' ); 
	$logo_image_title = $options["logo_image_title"];
	if ( $logo_image_title == NULL ) { $logo_image_title = get_bloginfo('description'); }
    
    return $logo_image_title;
}
//---- end logo image title


//---- logo message (between logo and login area)
function cmw_login_screen_login_logo_message() {
	$options = get_option( 'cmw_login_screen_options' ); 
	$logo_message = $options["logo_message"];
    return $logo_message;
}
//---- end logo message


//---- Footer message
function cmw_login_screen_login_footer_message() {
	$options = get_option( 'cmw_login_screen_options' ); 
	$footer_message = $options["footer_message"];
	
    echo $footer_message;
}
//---- End footer message




//---- Remove the admin bar for non-admins

function cmw_login_screen_hide_admin_bar() { 
	if ( is_admin() )
		return FALSE;
	
	elseif ( current_user_can( 'edit_posts' ) )
		return TRUE;
		
	else
		return FALSE;
}






// ACTIONS MEAN GOOOOOOO! Filters too.
add_action( 'login_enqueue_scripts', 'cmw_login_screen_login_logo' );
add_filter( 'login_headerurl', 'cmw_login_screen_login_logo_url' );
add_filter( 'login_headertitle', 'cmw_login_screen_login_logo_title' );
add_filter( 'login_message', 'cmw_login_screen_login_logo_message' );
add_action( 'login_footer', 'cmw_login_screen_login_footer_message' );
add_filter( 'show_admin_bar', 'cmw_login_screen_hide_admin_bar' ); 
?>