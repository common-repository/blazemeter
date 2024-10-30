<?php
/**
 *	Plugin Name: BlazeMeter
 *	Plugin URI: http://blazemeter.com/
 *	Description: BlazeMeter Plugin for load and performance testing.
 *	Author: nofearinc
 *	Author URI: http://devwp.eu/
 *	Version: 1.3
 *	License: GPLv2 or later
 *
 */
/*
 * Copyright (C) 2013 Blazemeter

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * */

/**
 * BlazeMeter major class 
 * 
 * Representing the activity flow and general features
 * 
 * @author nofearinc
 *
 */

require_once 'blazemeter-utils.class.php';
require_once 'blaze-settings-setup.php';
require_once 'blazemeter-api.php';

class BlazeMeter {
    public function BlazeMeter() {
        $this->init();
    }	

    // setup everything in the init function
    public function init() {
		add_action( 'wp_ajax_blazemeter_datalist', array($this, 'ajax_datalist') );
	
        add_action( 'admin_menu', array($this, 'blaze_admin_page_callback') );
        add_action( 'admin_enqueue_scripts', array( $this, 'blaze_enqueue_scripts' ) );
        add_action( 'admin_init', array($this, 'blaze_register_settings') );
        add_action( 'init', array($this, 'register_external_urls'));
        add_action( 'parse_request', array($this, 'parse_request') );
	
        add_filter( 'query_vars', array($this, 'filter_query_string') );
    }

    // add menu page here
    public function blaze_admin_page_callback() {
        add_menu_page('BlazeMeter', 'BlazeMeter', 'edit_themes', 'blazemeter', array($this, 'blaze_admin_page_content'));
    }

    public function blaze_enqueue_scripts() {
	wp_register_script('jquery-ui', ((isset($_SERVER["HTTPS"])&&$_SERVER["HTTPS"]!='off')? 'https': 'http').'://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js');
	
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui', false, array('jquery'));
        wp_enqueue_script('jquery-simplemodal', plugins_url('/js/jquery-simplemodal.min.js', __FILE__), array('jquery', 'jquery-ui'));
        wp_enqueue_script('blazemeter', plugins_url('/js/blazemeter.js', __FILE__), array('jquery', 'jquery-ui', 'jquery-simplemodal'));

        wp_enqueue_style('blaze-style', plugins_url('/css/blazemeter.css', __FILE__));
        wp_enqueue_style('blaze-simplemodal', plugins_url('/css/simplemodal.css', __FILE__));
        wp_enqueue_style('blaze-vader', plugins_url('/css/smoothness/jquery-ui-smoothness.min.css', __FILE__));
    }

    // define admin page content
    public function blaze_admin_page_content() {
	if(!function_exists('curl_init')){
		echo "Please install php5-curl by running command :sudo apt-get install php5-curl";
	} else {
        	require_once('blaze-admin-view.php');
	}
    }

    // register all settings to be used in the admin page form
    public function blaze_register_settings() {
        $blaze_settings = new BlazeMeter_Settings();
    }
    
    public function register_external_urls() {        
        add_rewrite_rule('^blazemeter-login/?(.*)', 'index.php?blazemeter=ajax_login&blazedata=$matches[1]', 'top');
        flush_rewrite_rules();
    }
    
    public function parse_request(&$wp) {
        
        if(!array_key_exists('blazemeter', $wp->query_vars))
            return;
        
        $blazeview = $wp->query_vars['blazemeter'];
        
        require_once('blazemeter-listener.php');
        exit;
    }
    
    public function filter_query_string($query_vars) {
        $query_vars[] = 'blazemeter';
        $query_vars[] = 'blazedata';
        
        return $query_vars;
    }
    
    public function ajax_datalist() {
		$datalist = $this->get_system_urls(/*$phrase*/);
		
		die(json_encode($datalist));
    }
    
    public function get_system_urls(/*$phrase = ''*/) {
		global $wpdb;
		
		$sql = "
		    SELECT
			p.ID,
			p.post_title,
			p.post_name,
			p.post_type
		    FROM {$wpdb->posts} AS p
		    WHERE
			p.post_status IN ('publish')
		    AND
			p.post_type IN ('post', 'page')
		";
		    
		$result = array();
		$data = $wpdb->get_results( $sql );
		
		foreach($data as $row) {
		    $entry = new stdClass();
		    $entry->id = $row->ID;
		    $entry->name = $row->post_name;
		    $entry->title = $row->post_title;
		    $entry->type = $row->post_type;
		    $entry->url = get_permalink($row->ID);
		    
		    $result[] = $entry;
		}
		
		return $result;
    }
    
    public function getCallbackUrl() {
		$htaccess_path = ABSPATH . '.htaccess';
		
		return get_option('permalink_structure')
		?   site_url() . '/blazemeter-login'
		:   site_url() . '/index.php?blazemeter=ajax_login&blazedata=';
    }
}

$blazemeter = new BlazeMeter();
