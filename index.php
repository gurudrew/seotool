<html>
    <head>
        <title>Drew's SEO Tool</title>
    </head>
    <body>
        <div id="container">
            <div id="content">
                <?php
                    if(get_request_var('submit')) {
                        $twitter = get_post_var('twitter');
                        $google = get_post_var('google');
                        $facebook = get_post_var('facebook');

                        $post = get_post_var('post');

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
                <h1>Post to multiple social networks</h1>
                <form action="index.php?submit" method="post">
                    <fieldset>
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
                            <label for="twitter">Post to Facebook</label>
                            <input type="checkbox" name="facebook" value="1" checked="true">
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
    return isset($_GET[$var]) ? $_GET[$var] : $default;
}

?>