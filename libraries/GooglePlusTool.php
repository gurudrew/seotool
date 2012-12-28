<?php
    require_once 'CURLRequest.php';
    class GooglePlusTool {
        function tidy($str) {
            return rtrim($str, "&");
        }

        // Assemble login postdata
        static function login_data($email, $pass) {
            $curl = new CURLRequest("https://plus.google.com/app/basic/login");
            $curl->setOptions(
                array(
                    CURLOPT_USERAGENT => MOBILE_USERAGENT,
                    CURLOPT_COOKIEFILE => 'cookies.txt',
                    CURLOPT_COOKIEJAR => 'cookies.txt'
                )
            );

            $buf = utf8_decode(html_entity_decode($curl->execute()));
            $curl->close();

            // if XML is returned we're already logged in
            if(substr($buf, 0, 5) == '<?xml') {
                // already logged in
                return array(true, true);
            }

            if(empty($buf)) {
                throw new Exception($curl->error());
            }

            $buf = str_replace( '&amp;', '&', $buf ); // just in case any correctly encoded
            $buf = str_replace( '&', '&amp;', $buf ); // now encode them all again

            $toreturn = '';

            $doc = new DOMDocument;
            $doc->loadHTML($buf);
            $inputs = $doc->getElementsByTagName('input');
            foreach ($inputs as $input) {
                switch ($input->getAttribute('name')) {
                    case 'Email':
                        $toreturn .= 'Email=' . urlencode($email) . '&';
                        break;
                    case 'Passwd':
                        $toreturn .= 'Passwd=' . urlencode($pass) . '&';
                        break;
                    default:
                        $toreturn .= $input->getAttribute('name') . '=' . urlencode($input->getAttribute('value')) . '&';
                }
            }
            return array(self::tidy($toreturn), $doc->getElementById('gaia_loginform')->getAttribute('action'));
        }
        
        // Post login postdata
        static function login($postdata) {
            // We've already logged in
            if($postdata[0] === true && $postdata[1] === true) { return true; }

            $curl = new CURLRequest($postdata[1]);
            $curl->setOptions(
                array(
                    CURLOPT_USERAGENT => MOBILE_USERAGENT,
                    CURLOPT_COOKIEFILE => 'cookies.txt',
                    CURLOPT_COOKIEJAR => 'cookies.txt'
                )
            );
            $curl->setPostData($postdata[0]);
            $buf = $curl->execute();
            $curl->close();

            if(stristr($buf, 'username or password you entered is incorrect')) {
                throw new Exception("Unable to validate with the given credentials");
            } else {
                return true;
            }
        }

        static function update_profile_status($status) {

            $curl = new CURLRequest('https://m.google.com/app/plus/?v=compose&group=m1c&hideloc=1');
            $curl->setOptions(
                array(
                    CURLOPT_USERAGENT => MOBILE_USERAGENT,
                    CURLOPT_COOKIEFILE => 'cookies.txt',
                    CURLOPT_COOKIEJAR => 'cookies.txt'
                )
            );

            $buf = utf8_decode(html_entity_decode(str_replace('&', '', $curl->execute())));
            $header = $curl->info();
            $curl->close();

            $params = '';
            $doc = new DOMDocument;
            $doc->loadxml($buf);
            $inputs = $doc->getElementsByTagName('input');
            foreach ($inputs as $input) {
                if (($input->getAttribute('name') != 'editcircles')) {
                    $params .= $input->getAttribute('name') . '=' . urlencode($input->getAttribute('value')) . '&';
                }
            }
            $params .= 'newcontent=' . urlencode($status);
            //$baseurl = $doc->getElementsByTagName('base')->item(0)->getAttribute('href');
            $baseurl = 'https://m.google.com' . parse_url($header['url'], PHP_URL_PATH);

            $curl->reset($baseurl . '?v=compose&group=m1c&group=b0&hideloc=1&a=post');
            $curl->setOptions(
                array(
                    CURLOPT_USERAGENT => MOBILE_USERAGENT,
                    CURLOPT_COOKIEFILE => 'cookies.txt',
                    CURLOPT_COOKIEJAR => 'cookies.txt',
                    CURLOPT_REFERER => $baseurl . '?v=compose&group=m1c&group=b0&hideloc=1'
                )
            );
            $curl->setPostData($params);

            $buf = $curl->execute();
            $header = $curl->info();
            $curl->close();
        }


        static function logout() {
            $curl = new CURLRequest('https://accounts.google.com/Logout?service=profiles');
            $curl->setOptions(
                array(
                    CURLOPT_USERAGENT => MOBILE_USERAGENT,
                    CURLOPT_COOKIEFILE => 'cookies.txt',
                    CURLOPT_COOKIEJAR => 'cookies.txt'
                )
            );
            $buf = $curl->execute();
            $curl->close();
        }
    }