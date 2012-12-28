<?php
    error_reporting(E_ERROR);
    ini_set('display_errors', 'On');
    require_once("social_tool.php");
?>
<html>
    <head>
        <title>Drew's SEO Tool</title>
    </head>
    <body>
        <div id="container">
            <div id="content">
                <h1>Post to multiple social networks</h1>
                <form action="index.php" method="post">
                    <fieldset>
                        <div id="status">
                            <?php
                            require_once('libraries/FacebookTool.php');
                            $fb = new FacebookTool();

                            if(get_post_var('submit')) {
                                $twitter = get_post_var('twitter');
                                $google = get_post_var('google');
                                $facebook = get_post_var('facebook');

                                $post = get_post_var('post');

                                echo '<h2>Submitted successfully</h2>';

                                if($google) {
                                    $email = get_post_var('gusername');
                                    $password = get_post_var('gpassword');
                                    social_google_post($email, $password, $post);
                                }

                                if($facebook) {
                                    social_facebook_post($post);
                                }
                            }
                            ?>
                        </div>
                        <input type="hidden" name="submit" value="1">
                        <div>
                            <label for="twitter">Post to Twitter</label>
                            <input type="checkbox" name="twitter" value="1" checked="true">

                            <label for="username">Twitter Username</label>
                            <input type="text" name="tusername">

                            <label for="password">Twitter Password</label>
                            <input type="password" name="tpassword">
                        </div>
                        <div>
                            <label for="twitter">Post to Google Plus</label>
                            <input type="checkbox" name="google" value="1" checked="true">

                            <label for="username">Google Plus Username</label>
                            <input type="text" name="gusername">

                            <label for="password">Google Plus Password</label>
                            <input type="password" name="gpassword">
                        </div>
                        <div>
                            <?php echo $fb->loginLinks(); if($fb->loggedIn): ?>
                            <label for="twitter">Post to Facebook</label>
                            <input type="checkbox" name="facebook" value="1" checked="true">
                            <?php endif; ?>
                        </div>
                        <label for="post">Post</label><br/>
                        <textarea name="post" cols="40" rows="9">Post Content</textarea><br/><br/>

                        <input type="submit" value="Post">
                    </fieldset>
                </form>
            </div>
        </div>
    </body>
</html>
<?php

function get_post_var($var, $default=false) {
    return isset($_POST[$var]) ? $_POST[$var] : $default;
}

function get_request_var($var, $default=false) {
    return isset($_REQUEST[$var]) ? $_REQUEST[$var] : $default;
}

?>