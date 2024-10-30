<?php
global $blazemeter, $blazemeter_api;

$listener_url = $blazemeter->getCallbackUrl();

$blazemeter->get_system_urls();
?>

<script type="text/javascript">
    var tip_ajax_url = '<?php echo plugin_dir_url( __FILE__ )?>data/';
</script>

<div id="blaze_loader">Loading&hellip; Please wait.</div>
<div id="blaze_cover"></div>

<div id="icon-plugins" class="icon32"></div>

<div class="wrap">
    <h2>BlazeMeter Admin Page</h2>
    
    <div id="message"></div>

    <form id="blazemeter-form" action="options.php" method="POST" onsubmit="return false">

        <?php settings_fields('blaze_setting') ?>
        <?php do_settings_sections( 'blazemeter' ) ?>
	
	<div class="buttonset">
	    <div id="blaze-logged">
		<a class="button-secondary" href="#" title="Go To Test Page"
		   onclick="blazemeter_open_test('<?php echo BLAZEMETER_URL ?>'); return false">Start Testing / Go To Test Page</a>
	    </div>
	    
	    <?php if(!$blazemeter_api->ajax_user_key(true)): ?>

	    <div id="blaze-nonlogged">
		<a class="button-secondary" id="blaze-signup" title="Sign Up" onclick="return false">Sign up to BlazeMeter and Get 10 Free Tests for Free</a>
		<div style="display: none;" id="blazemeter-signup-modal">
		    <iframe src="<?php echo BLAZEMETER_URL ?>/api/plugins/wordpress/register?callback=<?php echo $listener_url ?>"
			class="blaze_dialog" height="630px" width="450px" frameborder="0">
		    </iframe>
		</div>

		<a class="button-secondary" id="blaze-login" href="#" title="Login" onclick="return false">Login to BlazeMeter</a>
		<div style="display: none;" id="blazemeter-login-modal">
		    <iframe src="<?php echo BLAZEMETER_URL ?>/api/plugins/wordpress/login?callback=<?php echo $listener_url ?>"
			class="blaze_dialog" height="420px" width="450px" scrolling="no" frameborder="0">
		    </iframe>
		</div>
	    </div>

	    <?php endif; ?>

	    <a href="#" id="blaze-save" class="button-primary" title="Save test setup" onclick="blazemeter_submit(); return false;">Save</a>
	    <a href="#" id="blaze-clean" class="button-secondary" title="Clean up current configuration" onclick="blazemeter_cleanup(); return false;">Cleanup</a>

	    <a class="button-secondary" title="Knowledge base of Blazemeter for WordPress"
	       href="http://community.blazemeter.com/knowledgebase/articles/123648-wordpress-plugin"
	       target="_blank">Help</a>
	</div>

    </form> <!-- end of #blazemeter-form -->
</div>

<div id="blaze_tip" onclick="hideTip()"></div>