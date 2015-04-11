<?php if (!defined('BASE_PATH')) exit('No direct script access allowed'); ?>

<?php $app = \Liten\Liten::getInstance(); ?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?= 'Login :: ' . $app->hook->get_option('blog_title'); ?></title>
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

    <body>

        <!--Content-->
        <div id="content">
            <div id="login" class="container"> 
                <!--Login-->
                <div class="box">
                    <div class="header">
                        <h2>Login</h2>
                    </div>
                    <div class="content">
                        <div class="tabs"> 
                            <!--tab1-->
                            <div class="tab" id="tab1">
                                <?= (isset($_COOKIE['login_error']) ? $app->cookies->get('login_error') : ''); ?>
                                <?php $app->cookies->remove('login_error'); ?>
                                <form action="<?= url('/'); ?>login/" method="post" class="form">
                                    <p class="field">
                                        <label>Username </label>
                                        <input name="uname" type="text" class="large">
                                    </p>
                                    <p class="field">
                                        <label for="password">Password </label>
                                        <input name="password" type="password" class="large">
                                    </p>
                                    <p class="field">
                                        <button type="submit">Submit</button>
                                        <button type="reset" class="secondary">Reset</button>
                                    </p>
                                </form>
                            </div>

                            <!--End .tabs-->
                        </div>    	
                        <!--End .content-->	
                    </div>
                    <!--End .box-->
                </div>
                <!--End .container-->
            </div>
            <!--End #content-->
        </div>
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
</html>
