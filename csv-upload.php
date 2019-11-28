<?php

/**
 * Plugin Name: CSV Upload
 * Plugin URI: 
 * Description: Just Upload a CSV and see the Magic Table.
 * Version: 1.0.0
 * Author: Vishal Singh
 * Author URI: 
 */


include __DIR__ . '/uninstall.php';

if (!function_exists('wp_handle_upload')) {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
}

//global $campaigns_list;
error_reporting(E_ALL);
global $csv_import_version;
$csv_import_version = '1.0.0';

// function csv_import_setup_post_type()
// {
//     register_post_type(
//         'csv_data',
//         array(
//             'labels'      => array(
//                 'name'          => __('CSVs'),
//                 'singular_name' => __('CSV'),
//             ),
//             'public'      => true,
//             'has_archive' => true,
//             'rewrite'     => array('slug' => 'csv-data'), // my custom slug
//         )
//     );
// }

function csv_import_install()
{
    global $wpdb;
    global $csv_import_version;

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    // csv_import_setup_post_type();
    //add_action('init', 'csv_import_setup_post_type');

    // flush_rewrite_rules();
    $csv_table = $wpdb->prefix . 'all_csv_tables';

    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE " . $csv_table . " IF NOT EXISTS (
        id int(11) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        status tinyint NOT NULL DEFAULT 0,
        created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY  (id)
    ) " . $charset_collate . ";";

    dbDelta($sql);
    update_option('csv_import_version', $csv_import_version);
}

register_activation_hook(__FILE__, 'csv_import_install');

add_action('admin_menu', 'csv_import_menu');
function csv_import_menu()
{
    $page_title = 'All Tables';
    $menu_title = 'All Tables';
    $capability = 'manage_options';
    $menu_slug  = 'manage-tables';
    $function   = 'list_csv_tables';
    $icon_url   = 'dashicons-media-document';
    $position   = 4;

    add_menu_page(
        $page_title,
        $menu_title,
        $capability,
        $menu_slug,
        $function,
        $icon_url,
        $position
    );

    add_submenu_page(
        'manage-tables',
        'Upload a CSV',
        'Upload a CSV',
        'manage_options',
        'upload-csv',
        'upload-csv'
    );
}

wp_enqueue_style('csv-upload-style', plugin_dir_url(__FILE__) . 'style.css', array(), '1.0', 'all');


add_filter('set-screen-option', 'set_screen', 10, 3);

function csv_import_screen_option()
{

    $option = 'csv_per_page';
    $args   = [
        'label'   => 'CSV Tables',
        'default' => 10,
        'option'  => 'csv_tables_per_page'
    ];

    add_screen_option($option, $args);
}
