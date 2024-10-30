<?php
class BlazeMeter_Settings {

    private $blaze_setting = array();

    public function BlazeMeter_Settings() {
            $this->init();
    }

    public function init() {

            $this->blaze_setting = get_option('blaze_setting', '');

            register_setting('blaze_setting', 'blaze_setting', array( $this, 'blaze_validate_config' ));

            add_settings_section(
                    'blaze_settings_section',         // ID used to identify this section and with which to register options
                    'BlazeMeter Settings',                  // Title to be displayed on the administration page
                    array($this, 'blaze_settings_callback'), // Callback used to render the description of the section
                    'blazemeter'                           // Page on which to add this section of options
            );

            // anonymous section - max users and pages

            add_settings_field(
                    'blaze_anon_max_users',                      // ID used to identify the field throughout the theme
                    'Max Concurrent Anonymous Visitors',                           // The label to the left of the option interface element
                    array($this, 'blaze_anon_max_users_callback'),   // The name of the function responsible for rendering the option interface
                    'blazemeter',                          // The page on which this option will be displayed
                    'blaze_settings_section'         // The name of the section to which this field belongs
            );

            add_settings_field(
                    'blaze_anon_pages',                      // ID used to identify the field throughout the theme
                    'Anonymous Pages',                           // The label to the left of the option interface element
                    array($this, 'blaze_anon_pages_callback'),   // The name of the function responsible for rendering the option interface
                    'blazemeter',                          // The page on which this option will be displayed
                    'blaze_settings_section'         // The name of the section to which this field belongs
            );

            // authenticated section - max users and pages

            add_settings_field(
                    'blaze_auth_max_users',                      // ID used to identify the field throughout the theme
                    'Max Concurrent Authenticated Users',                           // The label to the left of the option interface element
                    array($this, 'blaze_auth_max_users_callback'),   // The name of the function responsible for rendering the option interface
                    'blazemeter',                          // The page on which this option will be displayed
                    'blaze_settings_section'         // The name of the section to which this field belongs
            );

            add_settings_field(
                    'blaze_auth_pages',                      // ID used to identify the field throughout the theme
                    'Authenticated Pages',                           // The label to the left of the option interface element
                    array($this, 'blaze_auth_pages_callback'),   // The name of the function responsible for rendering the option interface
                    'blazemeter',                          // The page on which this option will be displayed
                    'blaze_settings_section'         // The name of the section to which this field belongs
            );

            // Meta data

            add_settings_field(
                    'blaze_meta_scenario',                      // ID used to identify the field throughout the theme
                    'Load Scenario',                           // The label to the left of the option interface element
                    array($this, 'blaze_meta_scenario_callback'),   // The name of the function responsible for rendering the option interface
                    'blazemeter',                          // The page on which this option will be displayed
                    'blaze_settings_section'         // The name of the section to which this field belongs
            );

            add_settings_field(
                    'blaze_meta_domain',                      // ID used to identify the field throughout the theme
                    'Domain',                           // The label to the left of the option interface element
                    array($this, 'blaze_meta_domain_callback'),   // The name of the function responsible for rendering the option interface
                    'blazemeter',                          // The page on which this option will be displayed
                    'blaze_settings_section'         // The name of the section to which this field belongs
            );

            add_settings_field(
                    'blaze_meta_ip',                      // ID used to identify the field throughout the theme
                    'IP',                           // The label to the left of the option interface element
                    array($this, 'blaze_meta_ip_callback'),   // The name of the function responsible for rendering the option interface
                    'blazemeter',                          // The page on which this option will be displayed
                    'blaze_settings_section'         // The name of the section to which this field belongs
            );

            add_settings_field(
                    'blaze_meta_user_key',                      // ID used to identify the field throughout the theme
                    'User Key',                           // The label to the left of the option interface element
                    array($this, 'blaze_meta_user_key_callback'),   // The name of the function responsible for rendering the option interface
                    'blazemeter',                          // The page on which this option will be displayed
                    'blaze_settings_section'         // The name of the section to which this field belongs
            );

            add_settings_field(
                    'blaze_meta_test_id',                      // ID used to identify the field throughout the theme
                    'Test ID',                           // The label to the left of the option interface element
                    array($this, 'blaze_meta_test_id_callback'),   // The name of the function responsible for rendering the option interface
                    'blazemeter',                          // The page on which this option will be displayed
                    'blaze_settings_section'         // The name of the section to which this field belongs
            );

            add_settings_field(
                    'blaze_meta_test_name',                      // ID used to identify the field throughout the theme
                    'Test Name',                           // The label to the left of the option interface element
                    array($this, 'blaze_meta_test_name_callback'),   // The name of the function responsible for rendering the option interface
                    'blazemeter',                          // The page on which this option will be displayed
                    'blaze_settings_section'         // The name of the section to which this field belongs
            );
    }


    // layout for all of the settings fields 

    public function blaze_settings_callback() {
            $out = '';

            echo $out;
    }

    // layout for anonymous group

    public function blaze_anon_max_users_callback() {
            $val = '0';
            if(! empty( $this->blaze_setting ) && isset ( $this->blaze_setting['blaze_anon_max_users'] ) ) {
                    $val = $this->blaze_setting['blaze_anon_max_users'];
            }

            // $out = '<input type="text" id="blaze_anon_max_users" name="blaze_setting[blaze_anon_max_users]" value="' . $val . '" />';

            $out = '<div id="blaze-anon-slide" class="blaze-anon-max-users"></div>
                <div id="slider-anon-max-users-result" onclick="showModifier(this, \'anon\')">
                    ' . $val . '
                </div>
                <div class="blaze_modifier">
                    <input name="blaze_anon_max_users_modifier" id="blaze_anon_max_users_modifier" type="text" value="' . $val . '"
                        onkeyup="keyModifier(event, \'anon\')" onblur="storeModifier(this, \'anon\')" />
                </div>
                <input type="hidden" id="blaze-anon-max-users" name="blaze_setting[blaze_anon_max_users]" value="'. $val . '" />';

            echo $out;
    }

    public function blaze_anon_pages_callback() {
            $val = '';

            $out = '<div id="blaze-anon-pages-wrapper">';
            if(! empty( $this->blaze_setting ) && isset ( $this->blaze_setting['blaze_anon_pages'] ) && is_array( $this->blaze_setting['blaze_anon_pages'] ) ) {
				$anon_pages = $this->blaze_setting['blaze_anon_pages'];

		foreach($anon_pages as $no => $page)
		    $out .= '<input name="blaze_setting[blaze_anon_pages][]" id="blaze_anon_pages_' . ($no + 1) . '"
			type="text" class="blaze_anon_pages" value="'. $page .'"
			onkeyup="showPageHints(this)" />';
            } else {
				$out .= '<input name="blaze_setting[blaze_anon_pages][]" id="blaze_anon_pages_1" type="text" class="blaze_anon_pages" value="" onkeyup="showPageHints(this)"  />';
				$out .= '<input name="blaze_setting[blaze_anon_pages][]" id="blaze_anon_pages_2" type="text" class="blaze_anon_pages" value="" onkeyup="showPageHints(this)"  />';
            }

            $out .= '</div>';

            $out .= "<a id='blaze-anon-pages-add' class='button-secondary' href='#' title='Add Page'>Add Page</a><br /><br /><br />";

            echo $out;
    }

    // layout for authorized group

    public function blaze_auth_max_users_callback() {
            $val = '0';
            if(! empty( $this->blaze_setting ) && isset ( $this->blaze_setting['blaze_auth_max_users'] ) ) {
                    $val = $this->blaze_setting['blaze_auth_max_users'];
            }

            // $out = '<input type="text" id="blaze_auth_max_users" name="blaze_setting[blaze_auth_max_users]" value="' . $val . '"  />';

            $out = '<div id="blaze-auth-slide" class="blaze-auth-max-users"></div>
                    <div id="slider-auth-max-users-result" onclick="showModifier(this, \'auth\')">' . $val . '</div>
                    <div class="blaze_modifier">
                        <input name="blaze_auth_max_users_modifier" id="blaze_auth_max_users_modifier" type="text" value="' . $val . '"
                            onkeyup="keyModifier(event, \'auth\')" onblur="storeModifier(this, \'auth\')" />
                    </div>
                    <input type="hidden" id="blaze-auth-max-users" name="blaze_setting[blaze_auth_max_users]"  value="'. $val . '"  />';

            echo $out;
    }


    public function blaze_auth_pages_callback() {
            $val = '';

            $out = '<div id="blaze-auth-pages-wrapper">';
            if(! empty( $this->blaze_setting ) && isset ( $this->blaze_setting['blaze_auth_pages'] ) && is_array( $this->blaze_setting['blaze_auth_pages'] ) ) {
				$auth_pages = $this->blaze_setting['blaze_auth_pages'];

		foreach($auth_pages as $no => $page)
		    $out .= '<input name="blaze_setting[blaze_auth_pages][]" id="blaze_auth_pages_' . ($no + 1) . '"
			type="text" class="blaze_auth_pages" value="'. $page .'"
			onkeyup="showPageHints(this)" />';
            } else {
				$out .= '<input name="blaze_setting[blaze_auth_pages][]" id="blaze_auth_pages_1" type="text" class="blaze_auth_pages" value="" onkeyup="showPageHints(this)" />';
				$out .= '<input name="blaze_setting[blaze_auth_pages][]" id="blaze_auth_pages_2" type="text" class="blaze_auth_pages" value="" onkeyup="showPageHints(this)" />';
            }

            $out .= '</div>';

            $out .= "<a id='blaze-auth-pages-add' class='button-secondary' href='#' title='Add Page'>Add Page</a><br /><br /><br />";

            echo $out;
    }

    // layout for meta fields

    public function blaze_meta_scenario_callback() {
            $val = '';
            if(! empty( $this->blaze_setting ) && isset ( $this->blaze_setting['blaze_meta_scenario'] ) ) {
                    $val = $this->blaze_setting['blaze_meta_scenario'];
            }

            switch($val) {
                case 'load':
                case 'stress':
                case 'extreme stress':
                    break;
                default:
                    $val = 'load';
            }            

            $out  = '<input id="scenario_load" type="button" value="Load" class="scenario' . ($val == 'load' ? ' selected' : '') . '"
		onmouseover="showTip(\'load\')"
		onmouseout="hideTip()"
                onclick="blazemeter_change_scenario(jQuery(this), \'load\')" />&nbsp;';
            $out .= '<input id="scenario_stress" type="button" value="Stress" class="scenario' . ($val == 'stress' ? ' selected' : '') . '" 
		onmouseover="showTip(\'stress\')"
		onmouseout="hideTip()"
                onclick="blazemeter_change_scenario(jQuery(this), \'stress\')" />&nbsp;';
            $out .= '<input id="scenario_extreme" type="button" value="Extreme Stress" class="scenario' . ($val == 'extreme stress' ? ' selected' : '') . '" 
		onmouseover="showTip(\'extreme stress\')"
		onmouseout="hideTip()"
                onclick="blazemeter_change_scenario(jQuery(this), \'extreme stress\')" />';

            $out .= '<input type="hidden" id="blaze_meta_scenario" name="blaze_setting[blaze_meta_scenario]" value="' . $val . '"  />';

            echo $out;
    }

    public function blaze_meta_domain_callback() {
            $val = '';
            if(! empty( $this->blaze_setting ) && isset ( $this->blaze_setting['blaze_meta_domain'] ) ) {
                    $val = $this->blaze_setting['blaze_meta_domain'];
            }


            $out = '<input type="text" id="blaze_meta_domain" name="blaze_setting[blaze_meta_domain]" value="' . $val . '"  />';

            echo $out;
    }

    public function blaze_meta_ip_callback() {
            $val = $_SERVER['SERVER_ADDR'];
            if(! empty( $this->blaze_setting ) && isset ( $this->blaze_setting['blaze_meta_ip'] ) && ! empty( $this->blaze_setting['blaze_meta_ip'] ) ) {
                    $val = $this->blaze_setting['blaze_meta_ip'];
            }


            $out = '<input type="text" id="blaze_meta_ip" name="blaze_setting[blaze_meta_ip]" value="' . $val . '"  />';

            echo $out;
    }

    public function blaze_meta_user_key_callback() {
        global $blazemeter_api;

        $val = '';
        if(! empty( $this->blaze_setting ) && isset ( $this->blaze_setting['blaze_meta_user_key'] ) ) {
                $val = $this->blaze_setting['blaze_meta_user_key'];
        }


        $out = ($blazemeter_api->ajax_user_key(true))
	        ?   '<b>User key given.</b> Clean up configuration to enter new one, by pressing Cleanup button below.'
	        :   '<input type="password" id="blaze_meta_user_key" name="blaze_setting[blaze_meta_user_key]" value="' . $val . '"  />';

        echo $out;
    }

    public function blaze_meta_test_id_callback() {
            $val = '';
            if(! empty( $this->blaze_setting ) && isset ( $this->blaze_setting['blaze_meta_test_id'] ) ) {
                    $val = (int) $this->blaze_setting['blaze_meta_test_id'] > 0
                    ?   (int) $this->blaze_setting['blaze_meta_test_id']
                    :   '';
            }


            $out = '<input type="text" id="blaze_meta_test_id" name="blaze_setting[blaze_meta_test_id]" value="' . $val . '" />';

            echo $out;
    }

    public function blaze_meta_test_name_callback() {
            $val = '';
            if(! empty( $this->blaze_setting ) && isset ( $this->blaze_setting['blaze_meta_test_name'] ) ) {
                    $val = $this->blaze_setting['blaze_meta_test_name'];
            }


            $out = '<input type="text" id="blaze_meta_test_name" name="blaze_setting[blaze_meta_test_name]" value="' . $val . '"  />';

            echo $out;
    }

    /* validate settings and manage save options */
    public function blaze_validate_config($input) {
        global $blazemeter_api;

        $current = $blazemeter_api->get_settings(true);
        $action = isset($input['action']) ? $input['action'] : null;

        if((int) $input['blaze_meta_test_id'] <= 0)
            $input['blaze_meta_test_id'] = -1;

        if(!trim($input['blaze_meta_domain']))
            $input['blaze_meta_domain'] = site_url();

        if(!trim($input['blaze_meta_scenario']) || !in_array($input['blaze_meta_scenario'], array('load', 'stress', 'extreme stress')))
            $input['blaze_meta_scenario'] = 'load';

        //if((int) $input['blaze_meta_test_id'] != -1 && !trim($input['blaze_meta_user_key']))
        $input['blaze_meta_user_key'] = $current['blaze_meta_user_key'];

        switch($action) {
            case 'clear':
                $input['blaze_meta_user_key'] = '';
                break;
        }

        unset($input['action']);

        return $input;
    }
}