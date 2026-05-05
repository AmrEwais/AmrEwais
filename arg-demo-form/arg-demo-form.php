<?php
/**
 * Plugin Name: Argiatech Demo Form
 * Description: Demo booking form with submissions, email notifications, and admin management
 * Version: 1.0
 * Author: Argiatech
 */

if (!defined('ABSPATH'))
    exit;

define('ARG_DEMO_FORM_VERSION', '1.0');
define('ARG_DEMO_FORM_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Activation - create DB tables
register_activation_hook(__FILE__, 'arg_demo_form_activate');
function arg_demo_form_activate()
{
    global $wpdb;
    $table = $wpdb->prefix . 'arg_demo_submissions';
    $log_table = $wpdb->prefix . 'arg_demo_log';
    $charset = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table (
        id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        contact_time varchar(100) NOT NULL,
        communication_method varchar(50) NOT NULL,
        country_code varchar(10) DEFAULT '+966',
        phone varchar(20) DEFAULT NULL,
        email varchar(255) DEFAULT NULL,
        company varchar(255) DEFAULT NULL,
        secondary_country_code varchar(10) DEFAULT NULL,
        secondary_phone varchar(20) DEFAULT NULL,
        secondary_email varchar(255) DEFAULT NULL,
        startup varchar(20) DEFAULT NULL,
        priorities text DEFAULT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset;";

    $log_sql = "CREATE TABLE $log_table (
        id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        submission_id bigint(20) DEFAULT NULL,
        action varchar(50) NOT NULL,
        message text,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    dbDelta($log_sql);

    add_option('arg_demo_form_receiver_email', get_option('admin_email'));
    add_option('arg_demo_form_thank_you_slug', 'thank-you');
}

// Form handler
add_action('admin_post_nopriv_arg_demo_form', 'arg_handle_demo_form');
add_action('admin_post_arg_demo_form', 'arg_handle_demo_form');

function arg_handle_demo_form()
{
    global $wpdb;
    $table = $wpdb->prefix . 'arg_demo_submissions';

    if (!isset($_POST['arg_form_nonce']) || !wp_verify_nonce($_POST['arg_form_nonce'], 'arg_demo_form')) {
        arg_demo_form_log('error', 'Security verification failed');
        wp_die(__('Security verification failed. Please try again.', 'arg-demo-form'));
    }

    $name = sanitize_text_field($_POST['name'] ?? '');
    $contact_time = sanitize_text_field($_POST['contact_time'] ?? '');
    $communication_method = sanitize_text_field($_POST['communication_method'] ?? '');
    $country_code = sanitize_text_field($_POST['country_code'] ?? '+966');
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $company = sanitize_text_field($_POST['company'] ?? '');
    $secondary_country_code = sanitize_text_field($_POST['secondary_country_code'] ?? '');
    $secondary_phone = sanitize_text_field($_POST['secondary_phone'] ?? '');
    $secondary_email = sanitize_email($_POST['secondary_email'] ?? '');
    $startup = sanitize_text_field($_POST['startup'] ?? '');
    $priorities = isset($_POST['priority']) && is_array($_POST['priority'])
        ? array_map('sanitize_text_field', $_POST['priority'])
        : [];
    $priorities_str = implode(', ', $priorities);

    $errors = [];
    if (empty($name))
        $errors[] = 'Name is required';
    if (empty($contact_time))
        $errors[] = 'Contact time is required';
    if (empty($communication_method))
        $errors[] = 'Communication method is required';

    if ($communication_method === 'email') {
        if (empty($email) || !is_email($email))
            $errors[] = 'Valid email is required';
    } else {
        if (empty($phone) || strlen(preg_replace('/\D/', '', $phone)) < 9)
            $errors[] = 'Valid phone is required';
    }

    if (!empty($errors)) {
        arg_demo_form_log('error', implode('; ', $errors));
        wp_die(implode('<br>', $errors));
    }

    $result = $wpdb->insert($table, [
        'name' => $name,
        'contact_time' => $contact_time,
        'communication_method' => $communication_method,
        'country_code' => $country_code,
        'phone' => $phone ?: null,
        'email' => $email ?: null,
        'company' => $company ?: null,
        'secondary_country_code' => $secondary_country_code ?: null,
        'secondary_phone' => $secondary_phone ?: null,
        'secondary_email' => $secondary_email ?: null,
        'startup' => $startup ?: null,
        'priorities' => $priorities_str ?: null,
    ]);

    if ($result === false) {
        arg_demo_form_log('error', 'Database insert failed');
        wp_die(__('Submission failed. Please try again.', 'arg-demo-form'));
    }

    $submission_id = $wpdb->insert_id;
    arg_demo_form_log('submission', 'Form submitted successfully', $submission_id);

    $receiver = get_option('arg_demo_form_receiver_email', get_option('admin_email'));
    $message = "Demo booking form submission:\n\n";
    $message .= "Name: $name\n";
    $message .= "Contact Time: $contact_time\n";
    $message .= "Communication Method: $communication_method\n";
    $message .= "Phone: $country_code $phone\n";
    $message .= "Email: $email\n";
    $message .= "Company: $company\n";
    $message .= "Secondary Phone: $secondary_country_code $secondary_phone\n";
    $message .= "Secondary Email: $secondary_email\n";
    $message .= "Startup: $startup\n";
    $message .= "Priorities: $priorities_str\n";

    $subject = 'طلب عرض توضيحي جديد - ' . $name;
    $headers = ['Content-Type: text/plain; charset=UTF-8'];
    $mail_sent = wp_mail($receiver, $subject, $message, $headers);
    arg_demo_form_log($mail_sent ? 'email_sent' : 'email_failed', $mail_sent ? 'Email sent' : 'Email failed', $submission_id);

    $thank_you_slug = get_option('arg_demo_form_thank_you_slug', 'thank-you');
    $redirect_url = home_url('/' . trim($thank_you_slug, '/') . '/');
    wp_redirect($redirect_url);
    exit;
}

function arg_demo_form_translate_to_arabic($field, $value)
{
    $map = [
        'contact_time' => [
            'morning' => 'صباحاً (9 ص - 12 م)',
            'afternoon' => 'ظهراً (12 م - 3 م)',
            'evening' => 'مساءً (3 م - 6 م)',
            'anytime' => 'أي وقت',
        ],
        'communication_method' => [
            'call' => 'مكالمة هاتفية',
            'whatsapp' => 'واتساب',
            'email' => 'بريد إلكتروني',
        ],
        'startup' => [
            'yes' => 'نعم',
            'no' => 'لا',
        ],
        'priorities' => [
            'cost' => 'التكلفة',
            'zatca' => 'التكامل مع زاتكا',
            'modules' => 'تنوع الوحدات',
        ],
    ];
    if ($field === 'priorities' && $value) {
        $items = array_map('trim', explode(',', $value));
        $translated = [];
        foreach ($items as $item) {
            $translated[] = isset($map['priorities'][$item]) ? $map['priorities'][$item] : $item;
        }
        return implode('، ', $translated);
    }
    return isset($map[$field][$value]) ? $map[$field][$value] : $value;
}

function arg_demo_form_log($action, $message, $submission_id = null)
{
    global $wpdb;
    $wpdb->insert($wpdb->prefix . 'arg_demo_log', [
        'submission_id' => $submission_id,
        'action' => $action,
        'message' => $message,
    ]);
}

// Shortcode for form
add_shortcode('arg_demo_form', 'arg_demo_form_shortcode');
function arg_demo_form_shortcode()
{
    ob_start();
    include ARG_DEMO_FORM_PLUGIN_DIR . 'form-template.php';
    return ob_get_clean();
}

// Shortcode for thank you page
add_shortcode('arg_demo_thankyou', 'arg_demo_thankyou_shortcode');
function arg_demo_thankyou_shortcode()
{
    $back_url = home_url('/ar/erp/');
    return '<div class="arg-demo-thankyou-wrap" style="font-family:\'Cairo\',sans-serif;text-align:center;padding:60px 20px;max-width:600px;margin:0 auto;min-height: 100vh;">
        <div style="font-size:64px;margin-bottom:20px;">✅</div>
        <h1 style="color:#0E9FD7;font-size:32px;margin-bottom:20px;">شكراً لتسجيلك!</h1>
        <p style="font-size:18px;color:#4a4a4a;margin-bottom:30px;">تم استلام طلبك بنجاح. سنتواصل معك خلال 24 ساعة لعرضنا التوضيحي.</p>
        <a href="' . esc_url($back_url) . '" style="display:inline-block;padding:14px 30px;background:linear-gradient(135deg,#0E9FD7,#003148);color:white;text-decoration:none;border-radius:10px;font-weight:700;font-size:16px;">← العودة للصفحة الرئيسية</a>
    </div>';
}

// Admin menu
add_action('admin_menu', 'arg_demo_form_admin_menu');
function arg_demo_form_admin_menu()
{
    add_menu_page(
        'Demo Form',
        'Demo Form',
        'manage_options',
        'arg-demo-form',
        'arg_demo_form_admin_page',
        'dashicons-email-alt',
        30
    );
}

function arg_demo_form_admin_page()
{
    global $wpdb;
    $table = $wpdb->prefix . 'arg_demo_submissions';
    $log_table = $wpdb->prefix . 'arg_demo_log';

    if (isset($_POST['arg_save_settings']) && check_admin_referer('arg_demo_form_settings')) {
        update_option('arg_demo_form_receiver_email', sanitize_email($_POST['receiver_email'] ?? ''));
        update_option('arg_demo_form_thank_you_slug', sanitize_text_field($_POST['thank_you_slug'] ?? 'thank-you'));
        echo '<div class="notice notice-success"><p>Settings saved.</p></div>';
    }

    $receiver = get_option('arg_demo_form_receiver_email', get_option('admin_email'));
    $thank_you_slug = get_option('arg_demo_form_thank_you_slug', 'thank-you');
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'submissions';

    ?>
    <div class="wrap">
        <h1>Argiatech Demo Form</h1>
        <nav class="nav-tab-wrapper">
            <a href="?page=arg-demo-form&tab=submissions"
                class="nav-tab <?php echo $active_tab === 'submissions' ? 'nav-tab-active' : ''; ?>">Submissions</a>
            <a href="?page=arg-demo-form&tab=settings"
                class="nav-tab <?php echo $active_tab === 'settings' ? 'nav-tab-active' : ''; ?>">Settings</a>
            <a href="?page=arg-demo-form&tab=log"
                class="nav-tab <?php echo $active_tab === 'log' ? 'nav-tab-active' : ''; ?>">Log</a>
        </nav>

        <?php if ($active_tab === 'settings'): ?>
            <div class="card" style="min-width:100%;margin-top:20px;">
                <h2>Settings</h2>
                <form method="post">
                    <?php wp_nonce_field('arg_demo_form_settings'); ?>
                    <table class="form-table">
                        <tr>
                            <th><label for="receiver_email">Receiver Email</label></th>
                            <td><input type="email" id="receiver_email" name="receiver_email"
                                    value="<?php echo esc_attr($receiver); ?>" class="regular-text" required></td>
                        </tr>
                        <tr>
                            <th><label for="thank_you_slug">Thank You Page Slug</label></th>
                            <td>
                                <input type="text" id="thank_you_slug" name="thank_you_slug"
                                    value="<?php echo esc_attr($thank_you_slug); ?>" class="regular-text">
                                <p class="description">URL will be:
                                    <?php echo esc_html(home_url('/' . $thank_you_slug . '/')); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <p class="submit"><input type="submit" name="arg_save_settings" class="button button-primary"
                            value="Save Settings"></p>
                </form>
            </div>

        <?php elseif ($active_tab === 'log'): ?>
            <div class="card" style="min-width:100%;margin-top:20px;">
                <h2>Activity Log</h2>
                <?php
                $logs = $wpdb->get_results("SELECT * FROM $log_table ORDER BY created_at DESC LIMIT 100");
                if ($logs): ?>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Action</th>
                                <th>Submission ID</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><?php echo esc_html($log->id); ?></td>
                                    <td><?php echo esc_html($log->created_at); ?></td>
                                    <td><code><?php echo esc_html($log->action); ?></code></td>
                                    <td><?php echo esc_html($log->submission_id); ?></td>
                                    <td><?php echo esc_html($log->message); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No log entries yet.</p>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <div class="card" style="min-width:100%;margin-top:20px;">
                <h2>Submissions</h2>
                <?php
                $submissions = $wpdb->get_results("SELECT * FROM $table ORDER BY created_at DESC");
                if ($submissions): ?>
                    <table class="wp-list-table widefat fixed striped" dir="rtl">
                        <thead>
                            <tr>
                                <th>التاريخ</th>
                                <th>الاسم</th>
                                <th>التواصل</th>
                                <th>الوقت</th>
                                <th>الطريقة</th>
                                <th>الشركة</th>
                                <th>شركة ناشئة</th>
                                <th>الأولويات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($submissions as $s):
                                $contact = $s->communication_method === 'email' ? $s->email : ($s->country_code . ' ' . $s->phone);
                                ?>
                                <tr>
                                    <td><?php echo esc_html($s->created_at); ?></td>
                                    <td><strong><?php echo esc_html($s->name); ?></strong></td>
                                    <td><?php echo esc_html($contact); ?></td>
                                    <td><?php echo esc_html(arg_demo_form_translate_to_arabic('contact_time', $s->contact_time)); ?>
                                    </td>
                                    <td><?php echo esc_html(arg_demo_form_translate_to_arabic('communication_method', $s->communication_method)); ?>
                                    </td>
                                    <td><?php echo esc_html($s->company); ?></td>
                                    <td><?php echo esc_html(arg_demo_form_translate_to_arabic('startup', $s->startup)); ?></td>
                                    <td><?php echo esc_html(arg_demo_form_translate_to_arabic('priorities', $s->priorities)); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No submissions yet.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="card" style="min-width:100%;margin-top:20px;">
            <h3>Shortcodes</h3>
            <p>Use <code>[arg_demo_form]</code> on the form page (e.g. /zatca-compliant-form).</p>
            <p>Use <code>[arg_demo_thankyou]</code> on the thank you page (e.g. /thank-you).</p>
        </div>
    </div>
    <?php
}