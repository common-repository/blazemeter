<?php

class Blazemeter_Utils {

	/**
	 * Create users and return their IDs
	 * @param int $count number of users to be created
	 */
	public static function create_users($count) {
		self::delete_users();
		
		$auth_users = array();
		
		for ($i=1; $i <= $count;$i++) {
			$username = 'bz-user' . $i;
			$password = md5('bz-pass' . $i . time());
			$email = 'bz-user'. $i . '@example.com';
			
			wp_create_user( $username, $password, $email );
		
			$auth_users[] = array(
					'username' => $username,
					'password' => $password,
			);
		}
		
		return $auth_users;
	} 
	
	public static function delete_users() {
		global $wpdb;
		
		$wpdb->query("DELETE FROM $wpdb->users WHERE user_login LIKE 'bz-user%'");
	}

}