<?php
if (!defined('ABSPATH')) exit;

add_action('admin_menu', 'brs_add_admin_menu');
function brs_add_admin_menu() {
    add_options_page('Binary Referral Settings', 'Binary Referral', 'manage_options', 'brs-settings', 'brs_settings_page');
}

function brs_settings_page() {
    if (isset($_POST['brs_commission_percentage'])) {
        update_option('brs_commission_percentage', intval($_POST['brs_commission_percentage']));
        update_option('brs_withdrawal_percentage', intval($_POST['brs_withdrawal_percentage']));
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }
    $commission_percentage = get_option('brs_commission_percentage', 10);
    $withdrawal_percentage = get_option('brs_withdrawal_percentage', 50);
    ?>
    <div class="wrap">
        <h1>Binary Referral Settings</h1>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th scope="row">Commission Percentage</th>
                    <td><input type="number" name="brs_commission_percentage" value="<?php echo esc_attr($commission_percentage); ?>" /></td>
                </tr>
                <tr>
                    <th scope="row">Withdrawal Percentage</th>
                    <td><input type="number" name="brs_withdrawal_percentage" value="<?php echo esc_attr($withdrawal_percentage); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
