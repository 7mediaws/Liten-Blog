<?php
if (!defined('BASE_PATH'))
    exit('No direct script access allowed');

/*
  Plugin Name: Disqus Commenting System
  Plugin URI: http://github.com/
  Version: 1.0.0
  Description: Adds the ability to use Disqus as the commenting system.
  Author: Joshua Parker
  Author URI: http://www.7mediaws.org/
  Plugin Slug: disqus
 */

$app = \Liten\Liten::getInstance();

$app->hook->add_action('admin_menu', 'disqus_page', 10);

function disqus_page()
{
    $app = \Liten\Liten::getInstance();
    // parameters: page slug, page title, and function that will display the page itself
    $app->hook->register_admin_page('disqus', 'Disqus', 'disqus_do_page');
}

function disqus_do_page()
{
    $app = \Liten\Liten::getInstance();
    $options = [ 'disqus_shortname'];

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
        <form name="form" method="post" action="<?= url('/admin/options/?page=disqus'); ?>">
            <div class="box"> 
                <!--Title-->
                <div class="header">
                    <h2>Disqus Comment System</h2>
                </div>
                <!--Content-->
                <div class="content padding">
                    <div class="accordion">
                        <div class="content">
                            <div class="field">
                                <label>Disqus Shortname:</label>
                                <input name="disqus_shortname" type="text" class="medium" value="<?= _h($app->hook->get_option('disqus_shortname')); ?>" required/>
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

function embed_disqus_code()
{
    $app = \Liten\Liten::getInstance();

    ?>
    <div id="disqus_thread"></div>
    <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES * * */
        var disqus_shortname = '<?= $app->hook->get_option('disqus_shortname'); ?>';

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function () {
            var dsq = document.createElement('script');
            dsq.type = 'text/javascript';
            dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>

    <?php
}

function disqus_comment_link()
{
    $app = \Liten\Liten::getInstance();

    ?>
    <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES * * */
        var disqus_shortname = '<?= $app->hook->get_option('disqus_shortname'); ?>';

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function () {
            var s = document.createElement('script');
            s.async = true;
            s.type = 'text/javascript';
            s.src = '//' + disqus_shortname + '.disqus.com/count.js';
            (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
        }());
    </script>
    <?php
}
$app->hook->add_action('post_content_footer', 'embed_disqus_code', 10);
$app->hook->add_action('footer', 'disqus_comment_link', 10);
