<?php
if (!defined('ABSPATH')) exit;

// Add balance discount to cart
add_action('woocommerce_cart_calculate_fees', 'brs_apply_balance_discount');
function brs_apply_balance_discount($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;
    $user_id = get_current_user_id();
    if (!$user_id) return;

    $balance = floatval(get_user_meta($user_id, 'brs_balance', true));
    $withdrawal_percentage = intval(get_option('brs_withdrawal_percentage', 50));

    if ($balance <= 0) return;

    $max_discount = ($balance * $withdrawal_percentage) / 100;
    $max_discount = min($max_discount, $cart->subtotal);

    if ($max_discount > 0) {
        $cart->add_fee(__('Referral Balance Discount', 'brs'), -$max_discount);
        WC()->session->set('brs_balance_discount', $max_discount);
    }
}

// Deduct balance on order complete
add_action('woocommerce_checkout_order_processed', 'brs_deduct_user_balance');
function brs_deduct_user_balance($order_id) {
    $user_id = get_current_user_id();
    if (!$user_id) return;

    $discount = WC()->session->get('brs_balance_discount');
    if ($discount > 0) {
        $balance = floatval(get_user_meta($user_id, 'brs_balance', true));
        $new_balance = max(0, $balance - $discount);
        update_user_meta($user_id, 'brs_balance', $new_balance);
        WC()->session->__unset('brs_balance_discount');
    }
}
