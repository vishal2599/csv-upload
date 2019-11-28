<?php

/**
 * Plugin Name: CSV Upload
 * Plugin URI: http://www.mywebsite.com/csv-upload
 * Description: Just Upload a CSV and see the Magic Table.
 * Version: 1.0
 * Author: Vishal Singh
 * Author URI: http://www.mywebsite.com
 */

error_reporting(E_ALL);

include __DIR__ . '/columns-class.php';


global $csv_upload_version;
$csv_upload_version = '1.0.0';

function csv_upload_install()
{
    global $wpdb;
    global $csv_upload_version;

    $table_name = $wpdb->prefix . 'all-csv-tables';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		name tinytext NOT NULL,
		text text NOT NULL,
		url varchar(55) DEFAULT '' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    add_option('csv_upload_version', $csv_upload_version);
}

function csv_upload_install_data()
{
    global $wpdb;

    $welcome_name = 'Hello There!';
    $welcome_text = 'Congratulations, you just completed the installation!';

    $table_name = $wpdb->prefix . 'all-csv-tables';

    $wpdb->insert(
        $table_name,
        array(
            'time' => current_time('mysql'),
            'name' => $welcome_name,
            'text' => $welcome_text,
        )
    );
}

register_activation_hook(__FILE__, 'csv_upload_install');
register_activation_hook(__FILE__, 'csv_upload_install_data');

add_action('admin_menu', 'csv_upload_menu');
function csv_upload_menu()
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
