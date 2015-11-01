<?php
/*
Plugin Name: Sage Donate
Plugin URI:
Description: Accept donations on your WordPress site via SagePay
Version: 1.0
Author: James Doc
Author URI: http://www.jamesdoc.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!class_exists('SD_Sage_Donate'))
{
    class SD_Sage_Donate
    {

        public static $input_data = array();
        public static $validation = array('hi'=>'yes');

        /**
         * Construct the plugin object
         */
        public function __construct()
        {
            global $sd_db_version;
            global $sb_db_tablename;
            global $wpdb;
            $sd_db_version = '1.0';
            $sb_db_tablename = $wpdb->prefix . 'sd_donations';

            // register actions
            add_action('admin_init', array(&$this, 'admin_init'));
            add_action('admin_menu', array(&$this, 'add_menu'));
            //add_action('template_redirect', array(&$this, 'check_for_post'));

            // register shortcodes for forms, etc
            add_shortcode('sage_donate', array(&$this, 'show_donate_form'));
            add_shortcode('sage_after_donate', array(&$this, 'post_donation'));

            // Add setting link on plugin page
            $plugin = plugin_basename(__FILE__);
            add_filter("plugin_action_links_$plugin", array(&$this, 'add_settings_link') );

        } // END public function __construct


        /**
         * Activate the plugin
         */
        public function activate()
        {
            // Create database table
            global $sb_db_tablename;
            global $sd_db_version;
            global $wpdb;

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE $sb_db_tablename (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                init_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                updated_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                name_first varchar(55) DEFAULT '' NOT NULL,
                name_last varchar(55) DEFAULT '' NOT NULL,
                email varchar(55) DEFAULT '' NOT NULL,
                phone varchar(55) DEFAULT '' NOT NULL,
                address1 varchar(55) DEFAULT '' NOT NULL,
                address2 varchar(55) DEFAULT '' NOT NULL,
                city varchar(55) DEFAULT '' NOT NULL,
                county varchar(55) DEFAULT '' NOT NULL,
                postcode varchar(55) DEFAULT '' NOT NULL,
                country varchar(55) DEFAULT '' NOT NULL,
                giftaid tinyint(1) DEFAULT '0' NOT NULL,
                mailinglist tinyint(1) DEFAULT '0' NOT NULL,
                amount numeric(10,2) DEFAULT '0' NOT NULL,
                currency varchar(3) DEFAULT 'GBP' NOT NULL,
                status varchar(255) DEFAULT 'NOT STARTED' NOT NULL,
                sage_vendortx varchar(255) DEFAULT '' NOT NULL,
                sage_vpstx_id varchar(255) DEFAULT '' NOT NULL,
                UNIQUE KEY id (id)
            ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );

            add_option( 'sd_db_version', $sd_db_version );
        } // END public function activate




        /**
         * Deactivate the plugin
         */
        public static function deactivate()
        {

        } // END public static function deactivate

        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init()
        {
            // Set up the settings for this plugin
            $this->init_settings();
        } // END public static function activate


        /**
         * Initialize some custom settings
         */
        public function init_settings()
        {
            // register the settings for this plugin
            register_setting('sd_sage_donate', 'sd_vendor_id');
            register_setting('sd_sage_donate', 'sd_vendor_passphrase');
            register_setting('sd_sage_donate', 'sd_live_staging');
            register_setting('sd_sage_donate', 'sd_currency');
            register_setting('sd_sage_donate', 'sd_giftaid');
            register_setting('sd_sage_donate', 'sd_success_url');
            register_setting('sd_sage_donate', 'sd_failure_url');
            register_setting('sd_sage_donate', 'sd_notify_email');
            register_setting('sd_sage_donate', 'sd_confirmation');
            register_setting('sd_sage_donate', 'sd_confirmation_body');
        } // END public function init_custom_settings()

        /**
         * add a menu
         */
        public function add_menu()
        {
            add_options_page('Sage Donate Settings', 'Sage Donate', 'manage_options', 'sd_sage_donate', array(&$this, 'plugin_settings_page'));
        } // END public function add_menu()

        // Add settings link on plugin page
        public function add_settings_link($links) {
            $settings_link = '<a href="options-general.php?page=sd_sage_donate">Settings</a>';
            array_unshift($links, $settings_link);
            return $links;
        }


        /**
         * Menu Callback
         */
        public function plugin_settings_page()
        {
            if(!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            // Render the settings template
            include(sprintf("%s/templates/tpl_settings.php", dirname(__FILE__)));
        } // END public function plugin_settings_page()


        public function check_for_post()
        {

            if ( isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) {

                // Validate all the datas
                self::validate_and_clean_post_array();

                // If data validation fails then exit early
                if (is_array(self::$validation)) {
                    // Validations errors are handled in `function show_donate_form()`
                    return;

                // Otherwise the data is clean! Huzzah
                } else {

                    // 0 | Set up ready for sagepay
                    require_once ('lib/sagepay/lib/SagePay.php');
                    $sagePay = new SagePay();
                    self::$input_data['sage_vendortx'] = $sagePay->getVendorTxCode();

                    // 1 | Save to database
                    self::save_donation_to_db();

                    // 2 | Format for SagePay
                    $sagePay->setCurrency(self::$input_data['currency']);
                    $sagePay->setAmount(self::$input_data['amount']);
                    $sagePay->setDescription('Thrive');
                    $sagePay->setBillingFirstnames(self::$input_data['name_first']);
                    $sagePay->setBillingSurname(self::$input_data['name_last']);
                    $sagePay->setBillingCity(self::$input_data['city']);
                    $sagePay->setBillingPostCode(self::$input_data['postcode']);
                    $sagePay->setBillingAddress1(self::$input_data['address1']);
                    $sagePay->setBillingCountry(self::$input_data['country']);
                    $sagePay->setAllowGiftAid(self::$input_data['giftaid']);
                    $sagePay->setDeliverySameAsBilling();
                    $sagePay->setWebsite(site_url());
                    $sagePay->setSuccessURL(get_option( 'sd_success_url' ));
                    $sagePay->setFailureURL(get_option( 'sd_failure_url' ));

                    $crypt = $sagePay->getCrypt();

                    // 3 | Tell the user that we're going to redirect them to SagePay
                    include(sprintf("%s/templates/tpl_sage_redirect.php", dirname(__FILE__)));
                    return TRUE;
                }
            }
        }

        /**
         * Validate and clean data
         * In:  Nothing (cleans the $_POST[])
         * Out: Clean data array
         */
        public function validate_and_clean_post_array(){

            self::$input_data = array(
                'name_first'    => sanitize_text_field($_POST['txt_name_first']),
                'name_last'     => sanitize_text_field($_POST['txt_name_last']),
                'email'         => sanitize_email($_POST['txt_email']),
                'phone'         => sanitize_text_field($_POST['txt_phone']),
                'address1'      => sanitize_text_field($_POST['txt_address1']),
                'address2'      => sanitize_text_field($_POST['txt_address2']),
                'city'          => sanitize_text_field($_POST['txt_city']),
                'county'        => sanitize_text_field($_POST['txt_county']),
                'postcode'      => sanitize_text_field($_POST['txt_postcode']),
                'country'       => sanitize_text_field($_POST['cbo_country']),
                'amount'        => sanitize_text_field($_POST['txt_amount']),
                'currency'      => sanitize_text_field($_POST['hdn_currency'])
            );


            if (isset($_POST['chk_giftaid'])) {
                self::$input_data['giftaid'] = 1;
            } else { self::$input_data['giftaid'] = 0; }

            if (isset($_POST['chk_mailinglist'])) {
                self::$input_data['mailinglist'] = 1;
            } else { self::$input_data['mailinglist'] = 0; }

            self::$validation = array();
            if (self::$input_data['name_first'] == "")       { self::$validation[] = 'txt_name_first'; }
            if (self::$input_data['name_last'] == "")        { self::$validation[] = 'txt_name_last'; }
            if (self::$input_data['email'] == "")            { self::$validation[] = 'txt_email'; }
            if (self::$input_data['address1'] == "")         { self::$validation[] = 'txt_address1'; }
            if (self::$input_data['city'] == "")             { self::$validation[] = 'txt_city'; }
            if (self::$input_data['postcode'] == "")         { self::$validation[] = 'txt_postcode'; }
            if (self::$input_data['country'] == "")          { self::$validation[] = 'cbo_country'; }
            if (self::$input_data['amount'] == "")           { self::$validation[] = 'txt_amount'; }

            if (!$this->isCurrency(self::$input_data['amount'])){
                self::$validation[] = 'txt_amount';
            } else { self::$input_data['amount'] = floatval(self::$input_data['amount']); }

            if ( empty(self::$validation)) {
                self::$validation = TRUE;
            }

            return;

        } // END validate and clean

        /**
         * Save dontation details to database
         * Out: Last inserted ID
         */
        public function save_donation_to_db(){

            global $sb_db_tablename;
            global $wpdb;

            $wpdb->insert(
                $sb_db_tablename,
                array(
                    'init_time'     => current_time( 'mysql' ),
                    'updated_time'  => current_time( 'mysql' ),
                    'name_first'    => self::$input_data['name_first'],
                    'name_last'     => self::$input_data['name_last'],
                    'email'         => self::$input_data['email'],
                    'phone'         => self::$input_data['phone'],
                    'address1'      => self::$input_data['address1'],
                    'address2'      => self::$input_data['address2'],
                    'city'          => self::$input_data['city'],
                    'county'        => self::$input_data['county'],
                    'postcode'      => self::$input_data['postcode'],
                    'country'       => self::$input_data['country'],
                    'giftaid'       => self::$input_data['giftaid'],
                    'mailinglist'   => self::$input_data['mailinglist'],
                    'amount'        => self::$input_data['amount'],
                    'currency'      => self::$input_data['currency'],
                    'status'        => 'SENT TO SAGEPAY',
                    'sage_vendortx' => self::$input_data['sage_vendortx']
                )
            );
            return $wpdb->insert_id;
        } // END save to database


        /**
         * Display a donation form
         */
        public function show_donate_form()
        {
            if (self::check_for_post() != TRUE) {
                $user_input = self::$input_data;
                $user_validation = self::$validation;
                $currency = get_option( 'sd_currency' , 'GBP' );
                include(sprintf("%s/templates/tpl_donate_form.php", dirname(__FILE__)));
            }
        } // END donation form

        /**
         * Success page
         */
        public function post_donation()
        {
            if (!isset($_GET['crypt'])) { return; }

            // 0 | Set up
            require_once ('lib/sagepay/lib/SagePay.php');
            $sagePay = new SagePay();

            // 1 | Decode crypt from SagePay
            $crypt = $_GET['crypt'];
            $decoded = $sagePay->decode($crypt);

            // 2 | Look up record id ($donation->id) in database based on `VendorTxCode`
            //$donation = self::select_donation_detail($decoded['VendorTxCode']);

            // 3 | Update record with donation amount, success/fail and `VPSTxId`
            self::update_donation_detail($decoded['VendorTxCode'], $decoded);

            // 4 | Send notification email to admin


            // 5 | if success send a success email


            // 6 | if fail send a fail email


        } // END success page

        /**
         * Check if input is currency
         * In:  String to validate
         * Out: True / False
         */
        public function isCurrency($number)
        {
            return preg_match("/^-?[0-9]+(?:\.[0-9]{1,2})?$/", $number);
        } // END currency check

        /*
         * Selects record from database based on sage_vendortx
         * In:  Sage Vendo Tx Code
         * Out: Donation information
         */
        protected function select_donation_detail($vendortxcode)
        {
            global $wpdb;
            global $sb_db_tablename;
            $query = "SELECT * FROM " . $sb_db_tablename . " WHERE sage_vendortx = '" . $vendortxcode . "' LIMIT 1;";
            return $wpdb->get_row($query);
        } // END select donation detail

        /*
         * Updates donation record with new information from SagePay
         * In:  array- status, amount, sage_vpstx_id
         * Out: null
         */
        protected function update_donation_detail($vendortxcode, $data) {
            global $wpdb;
            global $sb_db_tablename;

            $t = $wpdb->update(
                $sb_db_tablename,
                array(
                    'amount' => $data['Amount'],  // string
                    'status' => $data['StatusDetail'],   // integer (number)
                    'sage_vpstx_id' => $data['VPSTxId']   // integer (number)
                ),
                array( 'sage_vendortx' => $vendortxcode )
            );
        } // END update dontation detail


    } // END class SD_Sage_Donate
} // END if(!class_exists('SD_Sage_Donate'))

if(class_exists('SD_Sage_Donate'))
{
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('SD_Sage_Donate', 'activate'));
    register_deactivation_hook(__FILE__, array('SD_Sage_Donate', 'deactivate'));

    // instantiate the plugin class
    $wp_plugin_template = new SD_Sage_Donate();
}
