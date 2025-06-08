<?php
/**
 * Plugin Name: Binary Referral System
 * Description: Adds a binary referral system with WooCommerce integration.
 * Version: 1.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) exit;

// Include plugin files
require_once plugin_dir_path(__FILE__) . 'includes/functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-settings.php';

// WooCommerce integration
if (class_exists('WooCommerce')) {
    require_once plugin_dir_path(__FILE__) . 'includes/woocommerce-integration.php';
}

// Add referral code field to WooCommerce register form
add_action('woocommerce_register_form_start', 'brs_add_referral_field');
function brs_add_referral_field() {
    ?>
    <p class="form-row form-row-wide">
        <label for="reg_referral_code"><?php _e('Referral Code (optional)', 'brs'); ?></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="referral_code" id="reg_referral_code" value="<?php echo (!empty($_POST['referral_code']) ? esc_attr($_POST['referral_code']) : ''); ?>" />
    </p>
    <?php
}

// Validate referral code
add_action('woocommerce_register_post', 'brs_validate_referral_code', 10, 3);
function brs_validate_referral_code($username, $email, $validation_errors) {
    if (!empty($_POST['referral_code'])) {
        $referral_code = sanitize_text_field($_POST['referral_code']);
        $referrer = get_user_by('login', $referral_code);
        if (!$referrer) {
            $validation_errors->add('referral_code_error', __('Invalid referral code.', 'brs'));
        }
    }
}

// Save referral relationship
add_action('woocommerce_created_customer', 'brs_save_referral_relationship');
function brs_save_referral_relationship($customer_id) {
    if (!empty($_POST['referral_code'])) {
        $referrer = get_user_by('login', sanitize_text_field($_POST_
