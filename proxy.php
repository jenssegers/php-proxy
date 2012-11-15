<?php

class Proxy {
    
    // curl handle
    protected $ch;
    
    // configuration
    protected $config = array();
    
    // html result
    protected $body = "";
    
    function __construct() {
        // load the config
        $config = array();
        require ("config.php");
        
        // check config
        if (!count($config))
            die("Please provide a valid configuration");
        $this->config = $config;
        
        // initialize curl
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->ch, CURLOPT_HEADER, true);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->config["timeout"]);
    }
    
    function forward($url) {
        // build the correct url
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
            $url = "https://" . $this->config["server"] . ":" . $this->config["https_port"] . "/" . ltrim($url, "/");
        else
            $url = "http://" . $this->config["server"] . ":" . $this->config["http_port"] . "/" . ltrim($url, "/");
        
        // set url
        curl_setopt($this->ch, CURLOPT_URL, $url);
        
        // forward request headers
        $headers = getallheaders();
        $this->set_request_headers($headers);
        
        // forward post
        if ($_SERVER["REQUEST_METHOD"] == "POST")
            $this->set_post($_POST);
        
        // execute
        $data = curl_exec($this->ch);
        $info = curl_getinfo($this->ch);
        
        // extract response from headers
        if ($info["size_download"]) {
        	$this->body = substr($data, $info["header_size"], $info["size_download"]);
        } else {
        	$this->body = "";
        }
        
        // forward response headers
        $headers = substr($data, 0, $info["header_size"]);
        $this->set_response_headers($headers);
        
        // close connection
        curl_close($this->ch);
        
        // output html
        echo $this->body;
    }
    
    function set_request_headers($request) {
        // headers to strip
        $strip = array("Content-Length", "Host");
        
        $headers = array();
        foreach ($request as $key => $value) {
            if ($key && !in_array($key, $strip)) {
                $headers[] = "$key: $value";
            }
        }
        
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
    }
    
    function set_response_headers($response) {
    	// headers to strip
    	$strip = array("Transfer-Encoding");
    	
    	// prepare array
    	$headers = array();
    	foreach (explode("\n", $response) as $header) {
    		 $parts = explode(":", $header);
    		 $key = trim(array_shift($parts));
    		 $value = trim(implode(":", $parts));
    		 $headers[$key] = $value;
    	}
    	
    	// modify redirects
    	if (isset($headers["Location"])) {
    		$base_url = $_SERVER["HTTP_HOST"];
    		$base_url .= rtrim(str_replace(basename($_SERVER["SCRIPT_NAME"]), "", $_SERVER["SCRIPT_NAME"]), '/');
			
			$headers["Location"] = str_replace(":" . $this->config["http_port"], '', $headers["Location"]);
			$headers["Location"] = str_replace(":" . $this->config["https_port"], '', $headers["Location"]);
    		$headers["Location"] = str_replace($this->config["server"], $base_url, $headers["Location"]);
    	}
    	
    	// set headers
    	foreach ($headers as $key => $value) {
            if ($key && !in_array($key, $strip)) {
                header("$key: $value");
            }
        }
    }
    
    function set_post($post) {
        // file upload support
        if (count($_FILES)) {
            foreach ($_FILES as $key => $file) {
                $parts = pathinfo($file["tmp_name"]);
                $name = $parts["dirname"] . "/" . $file["name"];
                rename($file["tmp_name"], $name);
                $post[$key] = "@" . $name;
            }
        } else {
            $post = http_build_query($post);
        }
        
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post);
    }
}