<?php $app = \Liten\Liten::getInstance(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?= isset($title) ? _h($title) . ' :: ' . $app->hook->get_option('blog_title') : $app->hook->get_option('blog_title'); ?></title>

        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" user-scalable="no" />
        <meta name="description" content="<?= $app->hook->get_option('meta_description'); ?>" />

        <link rel="alternate" type="application/rss+xml" title="<?= $app->hook->get_option('blog_title'); ?>  Feed" href="<?= url('/'); ?>rss" />

        <link href="<?= url('/'); ?>static/assets/styles/style.css" rel="stylesheet" />
        <link href="http://fonts.googleapis.com/css?family=Open+Sans+Condensed:700&subset=latin,cyrillic-ext" rel="stylesheet" />

        <!--[if lt IE 9]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <?php $app->hook->do_action('head'); ?>

    </head>
    <body>

        <aside class="sidebar">

            <h1><a href="<?= url('/'); ?>"><?= $app->hook->get_option('blog_title'); ?></a></h1>

            <p class="description"><?= $app->hook->get_option('blog_authorbio'); ?></p>
            <h3>Pages</h3>
            <ul>
                <?php get_pages(); ?>
            </ul>
            
            <h3>Categories</h3>
            <ul>
                <?php get_categories(); ?>
            </ul>
            
            <h3>Archives</h3>
            <ul>
                <?php get_archives(); ?>
            </ul>

            <p class="author"><?= _escape($app->hook->get_option('blog_description')); ?></p>

        </aside>

        <section id="content">

        <?= $app->view->show('default'); ?>

        </section>

    <?php $app->hook->do_action('footer'); ?>
    </body>
</html>