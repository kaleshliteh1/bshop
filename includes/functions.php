function brs_log_transaction($user_id, $amount, $type, $description = '') {
    global $wpdb;
    $table = $wpdb->prefix . 'brs_transactions';
    $wpdb->insert(
        $table,
        [
            'user_id' => $user_id,
            'amount' => $amount,
            'type' => $type,
            'description' => $description
        ]
    );
}
