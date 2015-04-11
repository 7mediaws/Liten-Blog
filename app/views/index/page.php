<?php if (!defined('BASE_PATH')) exit('No direct script access allowed'); ?>

<?php $app = \Liten\Liten::getInstance(); ?>
<?php $app->view->extend('_layouts/default'); ?>
<?php $app->view->block('default'); ?>

<?php foreach ($page as $d): ?>
    <div class="post">
        <h2><?= _h($d['page_title']); ?></h2>

        <?= _escape($d['page_content']); ?>

        <?php $app->hook->do_action('page_content_footer'); ?>

    </div>
<?php endforeach; ?>

<?php $app->view->stop(); ?>