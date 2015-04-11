<?php if (!defined('BASE_PATH')) exit('No direct script access allowed'); ?>

<?php $app = \Liten\Liten::getInstance(); ?>
<?php $app->view->extend('_layouts/admin'); ?>
<?php $app->view->block('admin'); ?>

<div class="container clearfix">
    <form name="form" method="post" action="<?= url('/admin/profile/'); ?>">
        <div class="box"> 
            <!--Title-->
            <div class="header">
                <h2>User Profile</h2>
            </div>
            <!--Content-->
            <div class="content padding">
                <?= (isset($_COOKIE['profile']) ? '<div class="content padding clearfix">' . $app->cookies->get('profile') . '</div>' : ''); ?>
                <?php $app->cookies->remove('profile'); ?>
                <div class="accordion">
                    <div class="content">
                        <div class="field">
                            <label>Username:</label>
                            <input type="text" class="medium" value="<?= get_userdata('uname'); ?>" readonly="readonly"/>
                        </div>
                        <div class="field">
                            <label>First Name:</label>
                            <input name="fname" type="text" class="medium" value="<?= get_userdata('fname'); ?>" required/>
                        </div>
                        <div class="field">
                            <label>Last Name:</label>
                            <input name="lname" type="text" class="medium" value="<?= get_userdata('lname'); ?>" required/>
                        </div>
                        <div class="field">
                            <label>Email:</label>
                            <input name="email" type="text" class="medium" value="<?= get_userdata('email'); ?>" required/>
                        </div>
                        <div class="field">
                            <label>Current Password:</label>
                            <input name="curr_pass" type="password" class="medium" /> &nbsp; If you would like to change your password, enter your current password and the new one you want set.
                       </div>
                        <div class="field">
                            <label>New Password:</label>
                            <input name="password" type="password" class="medium" />
                       </div>
                        <button type="submit" name="Submit">Save Profile</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php $app->view->stop(); ?>