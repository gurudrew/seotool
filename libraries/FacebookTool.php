<?php
    require_once("facebook/facebook.php");
    class FacebookTool {
        private $config, $handle, $user, $redirect;
        public $loggedIn = false;
        public function __construct($redirect=true) {
            $this->config = array(
                'appId' => FB_APPID,
                'secret' => FB_APPSECRET
            );
            $this->handle = new Facebook($this->config);
            $this->user = $this->handle->getUser();
            $this->token = $this->handle->getAccessToken();
            if($this->user) $this->loggedIn = true;
        }
        public function post($name, $message, $description, $link=false, $picture=false, $actions=false) {
            if(!$this->user && $this->redirect) {
                $login_url = $this->handle->getLoginUrl(
                    array(
                        'scope' => 'publish_stream'
                    )
                );
                header("Location: " . $login_url);
            }
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
        public function loginLinks() {
            if($this->user) {
                $request = array('access_token' => $this->token);
                $result = $this->handle->api('/me', 'GET', $request);
                $logout_url = $this->handle->getLogoutUrl(
                    array(
                        'next' => 'http://localhost/php-social-tool/seotool/?t=' . str_replace('.','',microtime(true))
                    )
                );
                return 'You are currently logged in to Facebook as ' . $result['name'] . '. <a href="' . $logout_url . '">Click here to log out</a>.';
            } else {
                $login_url = $this->handle->getLoginUrl(
                    array(
                        'scope' => 'publish_stream'
                    )
                );
                return 'You are not logged in to Facebook. <a href="' . $login_url . '">Click here to log in</a>.';
            }
        }
    }