<?php
    require_once("facebook/facebook.php");
    class FacebookTool {
        private $config, $handle, $user;
        public function __construct() {
            $this->config = array(
                'appId' => FB_APPID,
                'secret' => FB_APPSECRET
            );
            $this->handle = new Facebook($this->config);
            $this->user = $this->handle->getUser();
            $this->token = $this->handle->getAccessToken();
        }
        public function post($name, $link, $message, $description, $picture=false, $actions=false) {
            $post = array(
                'message' => $message,
                'name' => $name,
                'link' => $link,
                'description' => $description,
            );
            if($picture) $post['picture'] = $picture;
            if($actions) $post['actions'] = json_encode($actions);
            try {
                return $this->handle->api('/' . $this->user . '/feed', 'post', $post);
            } catch(FacebookApiException $e) {
                $login_url = $this->handle->getLoginUrl(
                    array(
                        'scope' => 'publish_stream'
                    )
                );
                echo '<a href="'. $login_url . '">Log in to Facebook</a>';
            }
        }
    }