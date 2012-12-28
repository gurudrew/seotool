<?php
    class CURLRequest {
        private $handle, $buffer, $options, $headers, $url, $postdata;
        public $debug = false;
        function __construct($url) {
            $this->url = $url;
            $this->reset();
        }
        function reset($url=false) {
            if($url) $this->url = $url;
            $this->handle = curl_init();
            curl_setopt($this->handle, CURLOPT_URL, $this->url);
            curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($this->handle, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($this->handle, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($this->handle, CURLOPT_FOLLOWLOCATION, 1);
            $this->options = array();
            $this->headers = array();
            $this->buffer = '';
        }
        function setOptions($array) {
            foreach($array as $var => $val) {
                curl_setopt($this->handle, $var, $val);
            }
        }
        function setOption($option, $value) {
            curl_setopt($this->handle, $option, $value);
        }
        function setHeader($option, $value) {
            $this->headers[$option] = $value;
        }
        function setHeaders($array) {
            $this->headers = array_merge($this->headers, $array);
        }
        function setPostData($data) {
            $this->postdata = $data;
        }
        function execute() {
            if(!empty($this->headers)) { curl_setopt($this->handle, CURLOPT_HTTPHEADER, $this->headers); }
            if(!empty($this->postdata)) {
                curl_setopt($this->handle, CURLOPT_POST, 1);
                curl_setopt($this->handle, CURLOPT_POSTFIELDS, $this->postdata);
            }
            $this->buffer = curl_exec($this->handle);
            if($this->debug) var_dump($this->url, $this->options, $this->postdata, $this->buffer);
            return $this->buffer;
        }
        function error() {
            return curl_error($this->handle);
        }
        function info() {
            return curl_getinfo($this->handle);
        }
        function close() {
            return curl_close($this->handle);
        }
    }