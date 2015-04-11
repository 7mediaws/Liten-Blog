<?php $app = \Liten\Liten::getInstance(); ?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?= 'Dashboard :: ' . $app->hook->get_option('blog_title'); ?></title>
        <!--CSS-->
        <link rel="stylesheet" href="<?= url('/'); ?>static/assets/styles/reset.css" type="text/css" />
        <link rel="stylesheet" href="<?= url('/'); ?>static/assets/styles/styles.css" type="text/css" />

        <!-- Google Fonts -->
        <link href='http://fonts.googleapis.com/css?family=Droid+Serif:regular,italic' rel='stylesheet' type='text/css' />
        <link href='http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold' rel='stylesheet' type='text/css' />

        <!--jQuery Uniform-->
        <link href="<?= url('/'); ?>static/assets/scripts/plugins/jquery-uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
        <link href="<?= url('/'); ?>static/assets/styles/jquery.simple-dtpicker.css" rel="stylesheet" type="text/css">

    </head>

    <body class="">
        <!--Header-->
        <div id="header"> 
            <!--Container-->
            <div class="container clearfix"> 
                <!--Menu-->
                <ul id="menu" class="clearfix">
                    <li><a href="<?= url('/admin/profile/'); ?>">Profile</a></li>
                    <li class="dropdown"> <a href="<?= url('/admin/settings/'); ?>">Settings</a>
                        <ul>
                            <li><a href="<?= url('/admin/settings/'); ?>">General</a></li>
                            <?php $app->hook->list_plugin_admin_pages(url('/admin/options/')); ?>
                        </ul>
                    </li>
                    <li> <a href="<?= url('/admin/logout/'); ?>">Logout</a>
                </ul>
                <!--Navigation-->
                <ul class="clearfix" id="navigation">
                    <li><a href="<?= url('/admin/'); ?>"><img alt="dashboard" class="icon" src="<?= url('/'); ?>static/assets/images/dashboard.png"/>Dashboard</a></li>
                    <li><a href="#"><img alt="posts" class="icon" src="<?= url('/'); ?>static/assets/images/inbox.png"/>Posts</a>
                        <ul>
                            <li><a href="<?= url('/admin/posts/'); ?>">Posts</a></li>
                            <li><a href="<?= url('/admin/post/new/'); ?>">New Post</a></li>
                            <li><a href="<?= url('/admin/categories/'); ?>">Categories</a></li>
                        </ul>
                    </li>
                    <li><a href="#"><img alt="pages" class="icon" src="<?= url('/'); ?>static/assets/images/inbox.png"/>Pages</a>
                        <ul>
                            <li><a href="<?= url('/admin/pages/'); ?>">Pages</a></li>
                            <li><a href="<?= url('/admin/page/new/'); ?>">New Page</a></li>
                        </ul>
                    </li>
                    <li><a href="<?= url('/admin/plugins/'); ?>"><img alt="plugins" class="icon" src="<?= url('/'); ?>static/assets/images/layout.png"/>Plugins</a></li>
                    <li class="separator"></li>
                </ul>
                <!--end container--> 
            </div>
            <!--end #header--> 
        </div>
        <!--Content-->
        <div id="content">
            <?= $app->view->show('admin'); ?>
            <!--Footer-->
            <div id="footer" class="separator"> 
                <!-- Remove this notice or replace it with whatever you want --> 
                &#169; Copyright 2015 | Powered by <a href="http://www.litenframework.com/">Liten Framework</a> | <a href="#">Top</a>
            </div>
            <!--End .container--> 
        </div>
        <!--End #content-->

    </body>

    <!--Scripts-->
    
    <!--jQuery-->
    <script src="http://code.jquery.com/jquery-1.11.2.min.js" type="text/javascript"></script>
    <script src="<?= url('/'); ?>static/assets/scripts/plugins/jquery.simple-dtpicker.js" type="text/javascript"></script>
    <script src="<?= url('/'); ?>static/assets/scripts/plugins/superfish.js" type="text/javascript"></script>
    <!--jQuery Uniform-->
    <script src="<?= url('/'); ?>static/assets/scripts/plugins/jquery-uniform/jquery.uniform.min.js" type="text/javascript"></script>
    <!--jQuery Datatables-->
    <script src="http://cdn.datatables.net/1.10.6/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <!--Script Loader-->
    <script src="<?= url('/'); ?>static/assets/scripts/plugins/loader.js" type="text/javascript"></script>
    <script src="//tinymce.cachefly.net/4.1/tinymce.min.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(function () {
            $('*[name=post_date]').appendDtpicker();
            $('*[name=page_date]').appendDtpicker();
        });
        tinymce.init({
            plugins: 'image link',
            selector: 'textarea.tinymce'
        });
    </script>
</html>