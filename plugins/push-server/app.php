<?php
/*
Plugin Name: Push Server
Description: Push Server is a wrapper for Perfecty Push that lets you add push notifications to any website, even if your website is not built using Wordpress.
Version: 1.0
Author: Ryan Huang
*/

function push_server_helper_remove_menu_pages()
{
    remove_menu_page('index.php'); // Dashboard
    remove_menu_page('edit.php'); // Posts
    remove_menu_page('upload.php'); // Media
    remove_menu_page('link-manager.php'); // Links
    remove_menu_page('edit-comments.php'); // Comments
    remove_menu_page('edit.php?post_type=page'); // Pages
    remove_menu_page('plugins.php'); // Plugins
    remove_menu_page('themes.php'); // Appearance
    remove_menu_page('users.php'); // Users
    remove_menu_page('tools.php'); // Tools
    remove_menu_page('options-general.php'); // Settings
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'push_server_helper_remove_menu_pages');
add_filter('show_admin_bar', '__return_false');

function dashboard_redirect()
{
    wp_redirect(admin_url('admin.php?page=perfecty-push'));
}
add_action('load-index.php', 'dashboard_redirect');


add_action('admin_head', 'webroom_add_css_js_to_admin');

function webroom_add_css_js_to_admin()
{
    echo '<style>
    #wpfooter {
        display: none;
      }
      
      .notice-notice {
        display: none;
      }
      
      #adminmenuwrap {
        position: relative !important;
        top: -13px;
      }
      
      #wp-admin-bar-site-name {
        display: none;
      }
      
      #wp-admin-bar-wp-logo {
        display: none;
      }
      
      #wp-admin-bar-comments {
        display: none;
      }
      
      #wp-admin-bar-new-content {
        display: none;
      }      
        </style>';
}

// Add a menu item to the WordPress admin menu
function push_server_menu()
{
    add_menu_page(
        'Push Server',     // Page title
        'Push Server',     // Menu title
        'manage_options',  // Capability required to access the menu
        'push-server',     // Menu slug
        'push_server_page', // Callback function to render the page
        'dashicons-megaphone',
        1
    );
    add_submenu_page('push-server', 'Logout', 'Logout', 'read', 'push-logout', 'push_logout_page');
}

function push_server_page()
{
    $site_url = esc_url(home_url('/')); // Get the site URL

    $code = '<link rel="stylesheet" id="perfecty-push-css" href="' . $site_url . 'wp-content/plugins/push-server/style.css" media="all" />' . "\n";
    $code .= '<script>
        window.PerfectyPushOptions = {
            path: "' . $site_url . 'wp-content/plugins/perfecty-push-notifications/public/js",
            dialogTitle: "Do you want to receive notifications?",
                         dialogSubmit: "Continue",
            dialogCancel: "Not now",
            settingsTitle: "Notifications preferences",
            settingsOptIn: "I want to receive notifications",
            settingsUpdateError: "Could not change the preference, try again",
            serverUrl: "' . $site_url . 'wp-json/perfecty-push",
            vapidPublicKey: "'. PERFECTY_PUSH_VAPID_PUBLIC_KEY .'",
            token: "'.wp_create_nonce('wp_rest').'",
            tokenHeader: "X-WP-Nonce",
            enabled: true,
            unregisterConflicts: false,
            serviceWorkerScope: "/perfecty/push",
            loggerLevel: "error",
            loggerVerbose: false,
            hideBellAfterSubscribe: false,
            askPermissionsDirectly: false,
            unregisterConflictsExpression: "(OneSignalSDKWorker|wonderpush-worker-loader|webpushr-sw|subscribers-com\/firebase-messaging-sw|gravitec-net-web-push-notifications|push_notification_sw)",
            promptIconUrl: "",
            visitsToDisplayPrompt: 0
        }
    </script>' . "\n";
    $code .= '<script src="' . $site_url . 'wp-content/plugins/perfecty-push-notifications/public/js/perfecty-push-sdk/dist/perfecty-push-sdk.min.js?ver=1.6.2" id="perfecty-push-js"></script>';

    echo '<h1>Push Server</h1>';
    echo '<p>Push Server is a wrapper that lets you add push notifications to any website, even if your website is not built using Wordpress.</p>';
    // Display the code in a code box using <pre> tag and htmlspecialchars()
    echo '<textarea style="width:calc(100% - 40px); height:300px; padding:20px; border-radius:0.25em;">' . htmlspecialchars($code) . '</textarea>';
}

function hello_world_message()
{
    $code = '<html><head><title>Push Server</title></head><body>';
    $code .= '<link rel="stylesheet" id="perfecty-push-css" href="' . $site_url . 'wp-content/plugins/push-server/style.css" media="all" />' . "\n";
    $code .= '<script>
        window.PerfectyPushOptions = {
            path: "' . $site_url . 'wp-content/plugins/perfecty-push-notifications/public/js",
            dialogTitle: "Do you want to receive notifications?",
                         dialogSubmit: "Continue",
            dialogCancel: "Not now",
            settingsTitle: "Notifications preferences",
            settingsOptIn: "I want to receive notifications",
            settingsUpdateError: "Could not change the preference, try again",
            serverUrl: "' . $site_url . 'wp-json/perfecty-push",
            vapidPublicKey: "'. PERFECTY_PUSH_VAPID_PUBLIC_KEY .'",
            token: "'.wp_create_nonce('wp_rest').'",
            tokenHeader: "X-WP-Nonce",
            enabled: true,
            unregisterConflicts: false,
            serviceWorkerScope: "/perfecty/push",
            loggerLevel: "error",
            loggerVerbose: false,
            hideBellAfterSubscribe: false,
            askPermissionsDirectly: false,
            unregisterConflictsExpression: "(OneSignalSDKWorker|wonderpush-worker-loader|webpushr-sw|subscribers-com\/firebase-messaging-sw|gravitec-net-web-push-notifications|push_notification_sw)",
            promptIconUrl: "",
            visitsToDisplayPrompt: 0
        }
    </script>' . "\n";
    $code .= '<script src="' . $site_url . 'wp-content/plugins/perfecty-push-notifications/public/js/perfecty-push-sdk/dist/perfecty-push-sdk.min.js?ver=1.6.2" id="perfecty-push-js"></script>';
    $code .= '</body></html>';
    echo $code;
    exit;
}
add_action('wp', 'hello_world_message');

function push_logout_page()
{
    wp_logout();
    wp_redirect(wp_login_url());
    exit;
}

add_action('admin_menu', 'push_server_menu');

function my_custom_login_logo()
{
    echo '<style>
        #login h1 { display: none; }
        #nav, #backtoblog { display: none; }
        #wp-submit { background: #000 !important; border: 0; border-radius: 0.25em; }
        input[type=checkbox]:focus, input[type=color]:focus, input[type=date]:focus, input[type=datetime-local]:focus,
        input[type=datetime]:focus, input[type=email]:focus, input[type=month]:focus, input[type=number]:focus,
        input[type=password]:focus, input[type=radio]:focus, input[type=search]:focus, input[type=tel]:focus,
        input[type=text]:focus, input[type=time]:focus, input[type=url]:focus, input[type=week]:focus,
        select:focus, textarea:focus { border-color: #000; box-shadow: 0 0 0 1px #000; }
        .login .button.wp-hide-pw:focus { border-color: #000; box-shadow: 0 0 0 1px #000; }
        .login .button.wp-hide-pw .dashicons { color: #000; }
        label[for="user_login"] {
            visibility: hidden;
            position: relative;
        }
        label[for="user_login"]::after {
            content: "Username";
            display: inline-block;
            visibility: visible;
            position: absolute;
            top: 0;
            left: 0;
        }
        .login form {
            border: 1px solid rgb(226 232 240);
            border-radius: 0.25rem;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }
        </style>';
}

add_filter('login_head', 'my_custom_login_logo');
