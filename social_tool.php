<?php
define('MOBILE_USERAGENT', 'Mozilla/4.0 (compatible; MSIE 5.0; S60/3.0 NokiaN73-1/2.0(2.0617.0.0.7) Profile/MIDP-2.0 Configuration/CLDC-1.1)');
define('PC_USERAGENT', 'Mozilla/5.0 (X11; Linux x86_64; rv:7.0.1) Gecko/20100101 Firefox/7.0.1');

social_google_post('drew.zf@gmail.com', 'see102507', 'Testing 1 2 3');

function social_google_post($email, $password, $post) {
    require_once('libraries/GooglePlusTool.php');
    GooglePlusTool::login(GooglePlusTool::login_data($email, $password));
    GooglePlusTool::update_profile_status($post);
    GooglePlusTool::logout();
}

function social_facebook_post($email, $password, $post) {

}

?>
