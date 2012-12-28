<?php
    require_once("facebook/facebook.php");
    class FacebookTool {
        private $config, $handle, $user;
        public function __construct($redirect=true) {
            $this->config = array(
                'appId' => FB_APPID,
                'secret' => FB_APPSECRET
            );
            $this->handle = new Facebook($this->config);
            $this->user = $this->handle->getUser();
            $this->token = $this->handle->getAccessToken();
            if(!$this->user && $redirect) {
                $login_url = $this->handle->getLoginUrl(
                    array(
                        'scope' => 'publish_stream'
                    )
                );
                header("Location: " . $login_url);
            }
        }
        public function post($name, $message, $description, $link=false, $picture=false, $actions=false) {
            $post = array(
                'message' => $message,
                'name' => $name,
                'description' => $description,
            );
            if($picture) $post['picture'] = $picture;
            if($actions) $post['actions'] = json_encode($actions);
            if($link) $post['link'] = $link;
            try {
                return $this->handle->api('/' . $this->user . '/feed', 'post', $post);
            } catch(FacebookApiException $e) {
                var_dump($e);
            }
        }
    }