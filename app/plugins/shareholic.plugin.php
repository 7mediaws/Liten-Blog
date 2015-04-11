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
$app->hook->add_action('head', 'embed_shareholic_code', 5);
