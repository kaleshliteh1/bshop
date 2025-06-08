<?php
if (!defined('ABSPATH')) exit;

add_shortcode('brs_user_dashboard', 'brs_user_dashboard_shortcode');
function brs_user_dashboard_shortcode() {
    if (!is_user_logged_in()) {
        return '<p>Please log in to view your dashboard.</p>';
    }

    ob_start();
    $user_id = get_current_user_id();
    $balance = get_user_meta($user_id, 'brs_balance', true);
    $team_size = brs_count_team($user_id);

    echo '<h2>Referral Dashboard</h2>';
    echo '<p><strong>Balance:</strong> ' . wc_price($balance) . '</p>';
    echo '<p><strong>Team Size:</strong> ' . intval($team_size) . '</p>';

    // Transaction history
    global $wpdb;
    $table = $wpdb->prefix . 'brs_transactions';
    $transactions = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table WHERE user_id = %d ORDER BY date DESC LIMIT 10", $user_id)
    );

    if ($transactions) {
        echo '<h3>Recent Transactions</h3>';
        echo '<table class="shop_table shop_table_responsive">';
        echo '<thead><tr><th>Date</th><th>Type</th><th>Amount</th><th>Description</th></tr></thead><tbody>';
        foreach ($transactions as $txn) {
            echo '<tr>';
            echo '<td>' . esc_html(date('Y-m-d H:i', strtotime($txn->date))) . '</td>';
            echo '<td>' . esc_html(ucfirst($txn->type)) . '</td>';
            echo '<td>' . wc_price($txn->amount) . '</td>';
            echo '<td>' . esc_html($txn->description) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p>No transactions found.</p>';
    }

    return ob_get_clean();
}
