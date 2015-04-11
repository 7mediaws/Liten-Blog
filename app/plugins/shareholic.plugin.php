<?php
if (!defined('BASE_PATH'))
    exit('No direct script access allowed');

/*
  Plugin Name: Shareholic
  Plugin URI: http://github.com/
  Version: 1.0.0
  Description: The most effective way to grow your website traffic.
  Author: Joshua Parker
  Author URI: http://www.7mediaws.org/
  Plugin Slug: shareholic
 */

$app = \Liten\Liten::getInstance();

$app->hook->add_action('admin_menu', 'shareholic_page', 10);

function shareholic_page()
{
    $app = \Liten\Liten::getInstance();
    // parameters: page slug, page title, and function that will display the page itself
    $app->hook->register_admin_page('shareholic', 'Shareholic', 'shareholic_do_page');
}

function shareholic_do_page()
{
    $app = \Liten\Liten::getInstance();
    $options = [ 'shareholic_app_id'];

    if ($_POST) {
        foreach ($options as $option_name) {
            if (!isset($_POST[$option_name]))
                continue;
            $value = $_POST[$option_name];
            $app->hook->update_option($option_name, $value);
        }

        // Update more options here
        $app->hook->do_action('update_options');
    }

    ?>

    <div class="container clearfix">
        <form name="form" method="post" action="<?= url('/admin/options/?page=shareholic'); ?>">
            <div class="box"> 
                <!--Title-->
                <div class="header">
                    <h2>Shareholic</h2>
                </div>
                <!--Content-->
                <div class="content padding">
                    <div class="accordion">
                        <div class="content">
                            <div class="field">
                                <label>App ID:</label>
                                <input name="shareholic_app_id" type="text" class="medium" value="<?= _h($app->hook->get_option('shareholic_app_id')); ?>" required/>
                            </div>
                            <button type="submit" name="Submit">Update Settings</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php
}

function embed_shareholic_code()
{
    $app = \Liten\Liten::getInstance();

    ?>
    <script type="text/javascript">
    //<![CDATA[
        (function () {
            var shr = document.createElement('script');
            shr.setAttribute('data-cfasync', 'false');
            shr.src = '//dsms0mj1bbhn4.cloudfront.net/assets/pub/shareaholic.js';
            shr.type = 'text/javascript';
            shr.async = 'true';
            shr.onload = shr.onreadystatechange = function () {
                var rs = this.readyState;
                if (rs && rs != 'complete' && rs != 'loaded')
                    return;
                var site_id = 'f160f4d005ff7f4d3584b8f704126434';
                try {
                    Shareaholic.init(site_id);
                } catch (e) {
                }
            };
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(shr, s);
        })();
    //]]>
    </script>

    <?php
}

function shareholic_share_buttons()
{
    $app = \Liten\Liten::getInstance();

    ?>

    <div class='shareaholic-canvas' data-app='share_buttons' data-app-id='<?= $app->hook->get_option('shareholic_app_id'); ?>'></div>

    <?php
}
$app->hook->add_action('head', 'embed_shareholic_code', 5);
$app->hook->add_action('post_content_footer', 'shareholic_share_buttons', 15);
