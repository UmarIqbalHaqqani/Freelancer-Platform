<?php
/**
 * Settings
 *
 * @package    wp-freeio
 * @author     Habq 
 * @license    GNU General Public License, version 3
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WP_Freeio_Ziprecruiter_Jobs_Hooks {

    private static $key = 'wp_freeio_ziprecruiter_import';

    public static function init() {
        
        // Job Fields
        add_filter( 'cmb2_meta_boxes', array( __CLASS__, 'job_meta_fields' ), 100 );

        add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );

        add_action( 'wp_ajax_wp_freeio_ajax_ziprecruiter_job_import', array( __CLASS__, 'process_import_jobs' ) );

        add_filter( 'wp-freeio-get-company-name', array( __CLASS__, 'get_company_name' ) );

        add_filter( 'wp-freeio-get-company-name-html', array( __CLASS__, 'get_company_name_html' ) );
    }

    public static function admin_menu() {
        if ( wp_freeio_get_option('ziprecruiter_job_import_enable') || (isset($_POST['ziprecruiter_job_import_enable']) && $_POST['ziprecruiter_job_import_enable'] == 'on') ) {
            add_submenu_page( 'edit.php?post_type=job_listing', esc_html__('Import Ziprecruiter Jobs', 'wp-freeio'), esc_html__('Import Ziprecruiter Jobs', 'wp-freeio'), 'manage_options', 'import-ziprecruiter-jobs', array( __CLASS__, 'jobs_settings' ) );
        }
    }

    public static function jobs_settings() {
        ?>
        <div class="wrap wp_freeio_settings_page cmb2_options_page">
            <h2><?php esc_html_e('Import Ziprecruiter Jobs', 'wp-freeio'); ?></h2>
            
            <?php cmb2_metabox_form( self::import_ziprecruiter_fields(), self::$key ); ?>

        </div>

        <?php
    }

    public static function import_ziprecruiter_fields() {
        $fields = array(
            'id'         => 'options_page',
            'wp_freeio_title' => __( 'Ziprecruiter Jobs Import', 'wp-freeio' ),
            'show_on'    => array( 'key' => 'options-page', 'value' => array( self::$key, ), ),
            'fields'     => apply_filters( 'wp_freeio_ziprecruiter_job_import_fields', array(
                    
                    array(
                        'name'    => __( 'Keywords', 'wp-freeio' ),
                        'desc'    => __( 'Enter job title, keywords or company name. Default keyword is all.', 'wp-freeio' ),
                        'id'      => 'ziprecruiter_job_import_keywords',
                        'type'    => 'text',
                    ),
                    array(
                        'name'    => __( 'Location', 'wp-freeio' ),
                        'desc'    => __( 'Enter a location for search.', 'wp-freeio' ),
                        'id'      => 'ziprecruiter_job_import_location',
                        'type'    => 'text',
                    ),
                    array(
                        'name'    => __( 'Per page jobs', 'wp-freeio' ),
                        'desc'    => __( 'Enter per page jobs.', 'wp-freeio' ),
                        'id'      => 'ziprecruiter_job_import_per_page',
                        'type'    => 'text',
                        'default' => '10',
                    ),
                    array(
                        'name'    => __( 'Radius', 'wp-freeio' ),
                        'desc'    => __( 'Enter radius for search.', 'wp-freeio' ),
                        'id'      => 'ziprecruiter_job_import_radius',
                        'type'    => 'text',
                        'default' => '20',
                    ),
                    array(
                        'name'    => __( 'Expired on', 'wp-freeio' ),
                        'desc'    => __( 'Enter number of days (numeric format) for expiray date after job posted date.', 'wp-freeio' ),
                        'id'      => 'ziprecruiter_job_import_expired_on',
                        'type'    => 'text',
                        'default' => '0',
                    ),
                    array(
                        'name'    => __( 'Posted By Type', 'wp-freeio' ),
                        'id'      => 'ziprecruiter_job_import_posted_by_type',
                        'type'    => 'select',
                        'options' => array(
                            'auto'  => __( 'Auto Generate', 'wp-freeio' ),
                            'employer' => __( 'Choose A Employer', 'wp-freeio' ),
                        ),
                        'default' => 'auto',
                    ),
                    array(
                        'name'          => __( 'Posted By', 'wp-freeio' ),
                        'id'            => 'ziprecruiter_job_import_posted_by',
                        'type'          => 'user_ajax_search',
                        'query_args'    => array(
                            'role'              => array( 'wp_freeio_employer' ),
                            'search_columns'    => array( 'user_login', 'user_email' )
                        ),
                        'desc'    => __( 'Choose an employer for job author', 'wp-freeio' ),
                    )
                )
            )        
        );
        return $fields;
    }

    public static function job_meta_fields( $metaboxes ) {
        if ( wp_freeio_get_option('ziprecruiter_job_import_enable') ) {
            $prefix = WP_FREEIO_JOB_LISTING_PREFIX;

            $metaboxes[ $prefix . 'ziprecruiter_job_fields' ] = array(
                'id'                        => $prefix . 'ziprecruiter_job_fields',
                'title'                     => __( 'Ziprecruiter Job Fields', 'wp-freeio' ),
                'object_types'              => array( 'job_listing' ),
                'context'                   => 'normal',
                'priority'                  => 'high',
                'show_names'                => true,
                'show_in_rest'              => true,
                'fields'                    => array(
                    array(
                        'name'              => __( 'Job Detail Url', 'wp-freeio' ),
                        'id'                => WP_FREEIO_JOB_LISTING_PREFIX . 'ziprecruiter_detail_url',
                        'type'              => 'text',
                    ),
                    array(
                        'name'              => __( 'Company Name', 'wp-freeio' ),
                        'id'                => WP_FREEIO_JOB_LISTING_PREFIX . 'ziprecruiter_company_name',
                        'type'              => 'text',
                    ),
                ),
            );
        }
        return $metaboxes;
    }

    public static function process_import_jobs() {
        $search_keywords = !empty($_POST['ziprecruiter_job_import_keywords']) ? sanitize_text_field(stripslashes($_POST['ziprecruiter_job_import_keywords'])) : '';
        $search_location = !empty($_POST['ziprecruiter_job_import_location']) ? sanitize_text_field(stripslashes($_POST['ziprecruiter_job_import_location'])) : '';
        $per_page = !empty($_POST['ziprecruiter_job_import_per_page']) ? sanitize_text_field($_POST['ziprecruiter_job_import_per_page']) : '';
        $radius = !empty($_POST['ziprecruiter_job_import_radius']) ? sanitize_text_field($_POST['ziprecruiter_job_import_radius']) : '';
        $expired_on = !empty($_POST['ziprecruiter_job_import_expired_on']) ? sanitize_text_field($_POST['ziprecruiter_job_import_expired_on']) : '';
        $posted_by = !empty($_POST['ziprecruiter_job_import_posted_by']) ? sanitize_text_field($_POST['ziprecruiter_job_import_posted_by']) : '';


        if ($per_page < 0) {
            $per_page = 10;
        }

        if ($radius < 0) {
            $radius = 20;
        }

        $api_args = array(
            'search' => $search_keywords,
            'location' => $search_location,
            'jobs_per_page' => $per_page,
            'radius_miles' => $radius,
        );

        $ziprecruiter_jobs = WP_Freeio_Ziprecruiter_API::get_jobs($api_args);
        
        if (isset($ziprecruiter_jobs['error']) && $ziprecruiter_jobs['error'] != '') {
            $json = array(
                'status' => false,
                'msg' => $ziprecruiter_jobs['error'],
            );
            echo json_encode($json);
            die();
        } elseif (empty($ziprecruiter_jobs)) {
            $json = array(
                'status' => false,
                'msg' => esc_html__('Sorry! There are no jobs found for your search query.', 'wp-freeio')
            );
            echo json_encode($json);
            die();
        } else {
            $post_author = '';
            if ( $posted_by_type == 'employer' ) {
                $post_author = !empty($posted_by) ? $posted_by : '';
            }
            foreach ($ziprecruiter_jobs as $ziprecruiter_job) {

                $job_url = isset($ziprecruiter_job['url']) ? $ziprecruiter_job['url'] : '';
                $existing_id = WP_Freeio_Mixes::get_post_id_by_meta_value( WP_FREEIO_JOB_LISTING_PREFIX.'ziprecruiter_detail_url', $job_url);

                if ( !empty($existing_id) ) {
                    continue;
                }

                $post_data = array(
                    'post_type' => 'job_listing',
                    'post_title' => isset($ziprecruiter_job['title']) ? $ziprecruiter_job['title'] : '',
                    'post_content' => isset($ziprecruiter_job['tagline']) ? $ziprecruiter_job['tagline'] : '',
                    'post_status' => 'publish'
                );
                if ( $post_author ) {
                    $post_data['post_author'] = $post_author;
                } elseif ( $posted_by_type == 'auto' && !empty($ziprecruiter_job['company']) ) {
                    $user_id = WP_Freeio_User::generate_user_by_post_name($ziprecruiter_job['company']);
                    if ( $user_id ) {
                        $post_data['post_author'] = $user_id;
                    }
                }
                // Insert the job into the database
                $post_id = wp_insert_post($post_data);
                
                if ( !empty($user_id) ) {
                    $employer_id = WP_Freeio_User::get_employer_by_user_id($user_id);
                    update_post_meta($post_id, WP_FREEIO_JOB_LISTING_PREFIX . 'employer_posted_by', $employer_id, true);
                }
                
                // Insert job expired on meta key
                if ( $expired_on > 0 ) {
                    $expired_date = date('Y-m-d', strtotime("$expired_on days", current_time('timestamp')));
                    update_post_meta($post_id, WP_FREEIO_JOB_LISTING_PREFIX . 'expiry_date', $expired_date, true);
                }

                // Insert job address meta key
                $location_addrs = array();
                if (!empty($ziprecruiter_job['location'])) {
                    $location_addrs['address'] = $ziprecruiter_job['location'];
                    add_post_meta($post_id, WP_FREEIO_JOB_LISTING_PREFIX . 'address', $ziprecruiter_job['location'], true);
                } else {
                    $location_addrs['address'] = '';
                }

                // Insert job latitude meta key
                if (!empty($ziprecruiter_job['latitude'])) {
                    add_post_meta($post_id, WP_FREEIO_JOB_LISTING_PREFIX . 'map_location_latitude', esc_attr($ziprecruiter_job['latitude']), true);
                    $location_addrs['latitude'] = $ziprecruiter_job['latitude'];
                }

                // Insert job longitude meta key
                if (!empty($ziprecruiter_job['longitude'])) {
                    add_post_meta($post_id, WP_FREEIO_JOB_LISTING_PREFIX . 'map_location_longitude', esc_attr($ziprecruiter_job['longitude']), true);
                    $location_addrs['longitude'] = $ziprecruiter_job['longitude'];
                }

                add_post_meta($post_id, WP_FREEIO_JOB_LISTING_PREFIX . 'map_location', $location_addrs, true);

                // Insert job referral meta key
                add_post_meta($post_id, WP_FREEIO_JOB_LISTING_PREFIX . 'job_referral', 'ziprecruiter', true);

                // Insert job detail url meta key
                if ( $job_url ) {
                    add_post_meta($post_id, WP_FREEIO_JOB_LISTING_PREFIX . 'ziprecruiter_detail_url', $job_url, true);

                    update_post_meta($post_id, WP_FREEIO_JOB_LISTING_PREFIX . 'apply_type', 'external', true);
                    update_post_meta($post_id, WP_FREEIO_JOB_LISTING_PREFIX . 'apply_url', $job_url, true);
                }

                // Insert job comapny name meta key
                if ( !empty($ziprecruiter_job['company']) ) {
                    add_post_meta($post_id, WP_FREEIO_JOB_LISTING_PREFIX . 'ziprecruiter_company_name', $ziprecruiter_job['company'], true);
                }

                

                // Create and assign taxonomy to post
                if ( !empty($ziprecruiter_job['type']) ) {
                    wp_insert_term($ziprecruiter_job['type'], 'job_listing_type');
                    $term = get_term_by('name', $ziprecruiter_job['type'], 'job_listing_type');
                    wp_set_post_terms($post_id, $term->term_id, 'job_listing_type');
                }
                
            }
            $json = array(
                'status' => false,
                'msg' => sprintf(__('%s ziprecruiter jobs are imported successfully.', 'wp-freeio'), count($ziprecruiter_jobs))
            );
            echo json_encode($json);
            die();
        }
        die();
    }

    public static function get_company_name($ouput, $post) {
        $job_referral = get_post_meta($post->ID, WP_FREEIO_JOB_LISTING_PREFIX . 'job_referral', true);
        $ziprecruiter_company_name = get_post_meta($post->ID, WP_FREEIO_JOB_LISTING_PREFIX . 'ziprecruiter_company_name', true);
        if ($job_referral == 'ziprecruiter' && $ziprecruiter_company_name != '') {
            $ouput = $ziprecruiter_company_name;
        }
        return $ouput;
    }

    public static function get_company_name_html($ouput, $post) {
        $job_referral = get_post_meta($post->ID, WP_FREEIO_JOB_LISTING_PREFIX . 'job_referral', true);
        $ziprecruiter_company_name = get_post_meta($post->ID, WP_FREEIO_JOB_LISTING_PREFIX . 'ziprecruiter_company_name', true);
        if ($job_referral == 'ziprecruiter' && $ziprecruiter_company_name != '') {
            $ouput = sprintf(wp_kses(__('<span class="employer text-theme">%s</span>', 'wp-freeio'), array( 'span' => array('class' => array()) ) ), $ziprecruiter_company_name );
        }
        return $ouput;
    }

    public static function view_more_btn($post) {
        $ouput = '';
        $job_referral = get_post_meta($post->ID, WP_FREEIO_JOB_LISTING_PREFIX . 'job_referral', true);
        $ziprecruiter_detail_url = get_post_meta($post->ID, WP_FREEIO_JOB_LISTING_PREFIX . 'ziprecruiter_detail_url', true);
        if ($job_referral == 'ziprecruiter' && $ziprecruiter_detail_url != '') {
            $ouput = '<div class="view-more-link"><a class="btn btn-theme" href="' . $ziprecruiter_detail_url . '">' . esc_html__('view more', 'wp-freeio') . '</a></div>';
        }
        return $ouput;
    }
}

add_filter( 'cmb2_get_metabox_form_format', 'wp_freeio_ziprecruiter_modify_cmb2_form_output', 10, 3 );

function wp_freeio_ziprecruiter_modify_cmb2_form_output( $form_format, $object_id, $cmb ) {
    if ( 'wp_freeio_ziprecruiter_import' == $object_id && 'options_page' == $cmb->cmb_id ) {

        return '<form class="cmb-form" method="post" id="%1$s" enctype="multipart/form-data" encoding="multipart/form-data"><input type="hidden" name="object_id" value="%2$s">%3$s<div class="wp_freeio-submit-wrap"><input type="button" name="submit-cmb-ziprecruiter-job-import" value="' . __( 'Import Ziprecruiter Jobs', 'wp-freeio' ) . '" class="button-primary"></div></form>';
    }

    return $form_format;
}

WP_Freeio_Ziprecruiter_Jobs_Hooks::init();