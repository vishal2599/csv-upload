<?php 

function csv_import_uninstall_me()
{
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
 

// unregister_post_type('csv_data');
$option_name = 'csv_import_version';
 
delete_option($option_name);
 
// for site options in Multisite
delete_site_option($option_name);
 
// drop a custom database table
global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}all_csv_tables");

}
register_uninstall_hook(__FILE__, 'csv_import_uninstall_me');

if ( ! function_exists( 'unregister_post_type' ) ) :
    function unregister_post_type( $post_type ) {
        global $wp_post_types;
        if ( isset( $wp_post_types[ $post_type ] ) ) {
            unset( $wp_post_types[ $post_type ] );
            return true;
        }
        return false;
    }
endif;


function csv_import_deactivation()
{
    // unregister the post type, so the rules are no longer in memory
    // unregister_post_type( 'csvdata' );
    // clear the permalinks to remove our post type's rules from the database
    // flush_rewrite_rules();

    $option_name = 'ads_manager_version';

    delete_option($option_name);

    // for site options in Multisite
    delete_site_option($option_name);

    // drop a custom database table
    global $wpdb;
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}all_csv_tables");
}
register_deactivation_hook(__FILE__, 'csv_import_deactivation');