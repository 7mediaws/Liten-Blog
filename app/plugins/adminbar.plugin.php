<?php
if (!defined('BASE_PATH'))
    exit('No direct script access allowed');

/*
  Plugin Name: Admin Bar
  Plugin URI: http://github.com/
  Version: 1.0.0
  Description: Activate to show an admin bar at the top of the site when a user is logged in.
  Author: Joshua Parker
  Author URI: http://www.7mediaws.org/
  Plugin Slug: adminbar
 */

$app = \Liten\Liten::getInstance();

function adminbar_css_stylesheet()
{
    $app = \Liten\Liten::getInstance();
    if (isUserLoggedIn()) {

        ?>
        <style>
            /* Utility Bar
        --------------------------------------------- */

            .utility-bar .wrap {
                width: 1140px;
                margin: auto;
            }

            .utility-bar {
                background-color: #333;
                border-bottom: 1px solid #ddd;
                color: #ddd;
                font-size:.9em;
                padding: 10px 0;
                padding: 1.5em;
            }

            .utility-bar a {
                color: #FFF;
            }

            .utility-bar a:hover {
                text-decoration: underline;
            }

            .utility-bar-left,
            .utility-bar-right {
                width: 50%;
            }

            .utility-bar-left p,
            .utility-bar-right p {
                margin-bottom: 0;
            }

            .utility-bar-left {
                float: left;
            }

            .utility-bar-right {
                float: right;
                text-align: right;
            }

            .utility-bar input[type="search"] {
                background: inherit;
                padding: 10px 0 0;
                padding: 1.0em 0 0;
            }
        </style>
        <?php
    }
}

function adminbar_div()
{
    $app = \Liten\Liten::getInstance();
    if (isUserLoggedIn()) {

        ?>
        <div class="utility-bar">
            <div class="wrap">
                <div class="utility-bar-left"><a href="<?= url('/admin/'); ?>">Dashboard</a></div>
                <div class="utility-bar-right">Howdy, <a href="<?= url('/admin/profile/'); ?>"><?= get_userdata('fname'); ?></a></div>
            </div>
        </div>
        <?php
    }
}
//$app->hook->add_action('admin_head', 'adminbar_css_stylesheet', 10);
$app->hook->add_action('head', 'adminbar_css_stylesheet', 10);
//$app->hook->add_action('admin_footer', 'adminbar_div', 10);
$app->hook->add_action('footer', 'adminbar_div', 10);

