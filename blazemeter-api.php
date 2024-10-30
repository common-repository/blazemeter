<?php
define('BLAZEMETER_URL',	    'https://a.blazemeter.com');
define('BLAZEMETER_API_URL',        '/api/rest/blazemeter/');
define('BLAZEMETER_APPKEY',	    'wps2kc68z4a907i2mmkd');

/**
 * BlazeMeter API Wrapper
 * Wraps the API supplied from BlazeMeter to the wordpress plugin.
 * 
 * @package BlazeMetter Plugin for WordPress
 * @author Lyubomir Gardev
 * @version 1.0
 */

class Blazemeter_Api {
    private $settings = null;
    private $message = '';
    private $message_type = 'error';
    private $errno = 0;
    private $error = '';
    
    public function __construct() {
        $this->settings = get_option('blaze_setting', array());
        
        $this->init();
    }
    
    public function init() {
        add_action( 'wp_ajax_blazemeter_submit', array(&$this, 'blazemeter_submit') );
        add_action( 'wp_ajax_blazemeter_clear', array(&$this, 'blazemeter_clear') );
        add_action( 'wp_ajax_blazemeter_login', array(&$this, 'ajax_login') );
        add_action( 'wp_ajax_blazemeter_user_key', array(&$this, 'ajax_user_key') );
    }
    
    public function reload($input = null) {
        $this->settings = !is_array($input) || empty($input) ? get_option('blaze_setting', array()) : $input;
    }
    
    public function get_settings($reload = false) {
        if($reload)
            $this->reload();
        
        return $this->settings;
    }
    
    public function is_clean($reload = false) {
        if($reload)
            $this->reload();
        
        return (int) @$this->settings['blaze_meta_test_id'] <= 0;
    }
    
    private function _admin_settings_submit($values) {
	if($values == null || empty($values))
            return;
        
        if(!isset($values['blaze_meta_user_key']) || !trim($values['blaze_meta_user_key']))
            $values['blaze_meta_user_key'] = @$this->settings['blaze_meta_user_key'];
        
        ini_set('max_execution_time', 120);
        
        $json = $this->create_json($values);
        $url = $this->get_api_url('setCMSTest');
        
        //die(print_r($json));
        
        $ch = curl_init();

        //set the url, number of POST vars, POST data 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        // Set so curl_exec returns the result instead of outputting it.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //configure cURL to accept HTTPS connections.
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        
        if(curl_errno($ch)){
            $this->errno = curl_errno($ch);
            $this->error = curl_error($ch);
        }
        
        curl_close($ch);
        
        ini_set('max_execution_time', 30);
        
        $result = mb_convert_encoding($result, 'utf-8');
        
        // Server is not responding
        if(!$result) {
            $this->message = 'Blazemeter server not responding.';
            $this->message_type = 'error';
            return;
        }
        
        $json = json_decode($result);

        if ($json->response_code == 200) {
            $this->settings['blaze_meta_test_id'] = $json->test_id;
            update_option('blaze_setting', $this->settings);
            
            $this->message = 'Test successfully created in Blazemeter. Your test id is ' . $json->test_id;
            $this->message_type = 'success';
        }
        else if($json->error){
            $this->message = $json->error;
            $this->message_type = 'error';
        }
        
        return $json;
    }
    
    public function blazemeter_submit() {
	if(empty($this->settings))
            die(json_encode(array('message' => 'Settings reload failed.', 'type' => 'error')));
	
	
        $json = $this->_admin_settings_submit($this->settings);
	
        die(json_encode(array(
            'message' => $this->message,
            'type' => $this->message_type,
            'errno' => $this->errno,
            'error' => $this->error,
            'response' => $json
        )));
    }
    
    public function blazemeter_clear($array = null) {
        if($array != null && (!is_array($array) || empty($array)))
            return;
        
        $toplevel = ($array == null);
        
        if($toplevel)
            $array = $this->settings;
        
        $keys = array_keys($array);
                
        for($i = 0; $i < count($array); $i++) {
            if(is_array($array[$keys[$i]]))
            {                
                $array[$keys[$i]] = $this->blazemeter_clear($array[$keys[$i]]);
                continue;
            }
            
            $array[$keys[$i]] = '';
        }
        
        if(!$toplevel)
            return $array;
	
		$array['action'] = 'clear';
        update_option('blaze_setting', $array);	
        $this->reload();
        
        $this->message = 'BlazeMeter settings cleaned successfully. Reloading in 2 seconds&hellip;';
        $this->message_type = 'success';
        
        die(json_encode(array(
            'message' => $this->message,
            'type' => $this->message_type,
            'errno' => $this->errno,
            'error' => $this->error
        )));
    }
    
    public function get_api_url($method) {
        if(empty($this->settings))
            return;
        
        $url = '';
        
        switch(strtolower($method)) {
          case 'getip':
            $url = BLAZEMETER_URL . BLAZEMETER_API_URL . 'getIp?app_key=' . BLAZEMETER_APPKEY;
          break;
          case 'setcmstest':
            $testid = (int) (isset($this->settings['blaze_meta_test_id']) ? @$this->settings['blaze_meta_test_id'] : '-1');
            $url = BLAZEMETER_URL . BLAZEMETER_API_URL . 'startCustomTest.json?user_key=' . @$this->settings['blaze_meta_user_key'] . '&app_key=' . BLAZEMETER_APPKEY . '&test_id=' . $testid;
          break;
          case 'usercreate':
            $url = BLAZEMETER_URL . BLAZEMETER_API_URL . 'userCreate';
          break;
        }
        return $url;
    }
    
    public function create_json($values) {

        //Create auth users
	$num_of_anons = (int) $values['blaze_anon_max_users'];
        $num_of_users = (int) $values['blaze_auth_max_users'];
        $auth_users = Blazemeter_Utils::create_users($num_of_users);
        
        $auth_pages = array();
        
        foreach($values['blaze_auth_pages'] as $auth_page) {
            $page = explode(' - ', $auth_page);

			// Wrong format - same phrase twice for label and url
			if(1 >= count($page))
				$page[1] = $page[0];
			
			if(!trim($auth_page) || count($page) < 2)
                continue;
            
            $auth_pages[] = array(
                'label' => $page[1],
                'url' => $page[0]
            );
        }
        
        $anon_pages = array();
        
        foreach($values['blaze_anon_pages'] as $anon_page) {
			$page = explode(' - ', $anon_page);
			
			// Wrong format - same phrase twice for label and url
			if(1 >= count($page))
				$page[1] = $page[0];
	    
            if(!trim($anon_page) || count($page) < 2)
                continue;
            
            $anon_pages[] = array(
                'label' => $page[1],
                'url' => $page[0]
            );
        }
        
        $json = array(
            'scenario' => $values['blaze_meta_scenario'],
            'host' => $values['blaze_meta_domain'],
            'ip' => $values['blaze_meta_ip'],
            'custom_type' => 'wordpress',
            'auth_users' => $auth_users, // optional. if NULL, no auth pages
            'auth_pages' => $auth_pages, // optional. if NULL, no auth pages
            'anon_pages' => $anon_pages, // optional. if NULL, no auth pages
            'auth_user_load' => $num_of_users, //$values['authenticated']['auth'],
            'anon_user_load' => $num_of_anons, //$values['anonymous']['anon'],
            'test_name' => $values['blaze_meta_test_name']
        );
        
        // die(print_r($json));
        
        return json_encode($json);
    }
    
    public function ajax_login() {
		$this->settings['blaze_meta_user_key'] = (string) @$_REQUEST['userKey'];
	        update_option('blaze_setting', $this->settings);
	        
	        $this->reload();
		
	        exit;
    }

    public function ajax_user_key($non_ajax = false) {
        $this->reload();
        
        $status = isset($this->settings['blaze_meta_user_key'])
		?   !!$this->settings['blaze_meta_user_key']
		:   false;
        
        if($non_ajax)
            return $status;
        
        die(json_encode(array('status' => $status), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT));
    }
}

$blazemeter_api = new Blazemeter_Api();
?>
