<?php
if (!defined('BASE_PATH'))
    exit('No direct script access allowed');

$app = \Liten\Liten::getInstance();
$app->view->extend('_layouts/admin');
$app->view->block('admin');

$plugins_header = $app->hook->get_plugins_header();

?>

<div class="container clearfix"> 
    <div class="box"> 
        <!--Title-->
        <div class="header">
            <h2>Posts</h2>
        </div>
        <!--Content-->
        <div class="content padding">
            <form action="" method="post" name="">
                <table class="datatable">
                    <thead>
                        <tr>
                            <th class="sorting"> Plugin </th>
                            <th class="sorting"> Description </th>
                            <th class="sorting"> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($plugins_header as $plugin) :
                            if ($app->hook->is_plugin_activated($plugin['filename']) == true)
                                echo '<tr class="gradeA" style="background-color:#B0E0E6 !important;">';
                            else
                                echo '<tr class="gradeA">';
                            echo '<td>' . $plugin['Name'] . '</td>';
                            echo '<td>' . $plugin['Description'];
                            echo '<br /><br />';
                            echo ' By <a href="' . $plugin['AuthorURI'] . '">' . $plugin['Author'] . '</a> ';
                            echo ' | <a href="' . $plugin['PluginURI'] . '">Visit plugin site</a></td>';
                            if ($app->hook->is_plugin_activated($plugin['filename']) == true) {
                                echo '<td class="text-center"><a href="d/' . urlencode($plugin['filename']) . '">Deactivate</a></td>';
                            } else {
                                echo '<td class="text-center"><a href="a/' . urlencode($plugin['filename']) . '">Activate</a></td>';
                            }
                            echo '</tr>';
                        endforeach;

                        ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<?php $app->view->stop(); ?>