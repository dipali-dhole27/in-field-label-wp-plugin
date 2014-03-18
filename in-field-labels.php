<?php
/*
  Plugin Name: In Field Labels
  Plugin URI: #
  Description: This plugin,properly formatted HTML forms turns into forms with in-field label. Labels fade when the field is focussed and disappear when text entry begins.
  Version: 1.1
  Author: Dipali Dhole
  Author URI: http://profiles.wordpress.org/dipalidhole27gmailcom/
  Donate link: #

 */

add_action('admin_menu', 'register_in_field_labels_submenu_page');

function register_in_field_labels_submenu_page() {
    add_submenu_page('options-general.php', 'In field labels', 'In field labels', 'manage_options', 'in-field-labels', 'in_field_labels_callback');
    add_action('admin_init', 'register_in_field_labels_settings');
}

function register_in_field_labels_settings() {
    //register our settings
    register_setting('in-field-labels-settings', 'in-field-labels-form-names');
}

function in_field_labels_callback() {
    echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
    echo '<h2>In Field Labels Settings</h2>';
    echo '<h3>Add form id (not including # sign) with colon( like commentform,test )</h3>';
    echo '</div>';
    ?>
    <form method="post" action="options.php">
        <?php settings_fields('in-field-labels-settings'); ?>
        <?php do_settings_sections('in-field-labels-settings'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Enabled forms</th>
                <td><input type="text" class="regular-text" name="in-field-labels-form-names" value="<?php echo get_option('in-field-labels-form-names'); ?>" /></td>
            </tr>
        </table>
        <?php submit_button(); ?>

    </form>
    <?php
}

function in_field_labels_scripts() {

    wp_enqueue_script('in-field-labels-script', plugins_url('js/jquery.infieldlabel.min.js', __FILE__), array(), '1.0.0', true);
}

add_action('wp_enqueue_scripts', 'in_field_labels_scripts');

add_action('wp_head', 'in_field_labels_javascript');

function in_field_labels_javascript() {
    $in_field = get_option('in-field-labels-form-names');
    if(!empty($in_field)){
    $output.="<script type='text/javascript'>";
    $output.=" jQuery(document).ready(function(){";
    
    $in_field_array = explode(",", $in_field);

    foreach ($in_field_array as $form) {
        $output.=" jQuery('#" . trim($form). " label').inFieldLabels(); ";
    }
    $output.="});";
    $output.="</script>";

    echo $output;
    }
}

add_action('wp_head', 'in_field_labels_css');

function in_field_labels_css() {
    $in_field = get_option('in-field-labels-form-names');
    if(!empty($in_field)){
    $output = "<style type='text/css'>";
    
    $in_field_array = explode(",", $in_field);
    foreach ($in_field_array as $form) {
        $add_form = "#" .trim($form);
        $output.="$add_form p br {display: none;}$add_form p {margin: 10px 0;position: relative;}$add_form fieldset p label {
  display: block;margin: 5px 5px 5px 6px;padding: 0;width: 380px;}$add_form p label {left: 8px;position: absolute;top: 0;}";
    }
    $output.="</style>";
    echo $output;
    }
}
