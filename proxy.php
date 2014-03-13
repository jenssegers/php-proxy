<?php

/**
 * @name        PHP Proxy
 * @author      Jens Segers
 * @link        http://www.jenssegers.be
 * @license     MIT License Copyright (c) 2013 Jens Segers
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
class Proxy {
    
    // curl handle
    protected $ch;
    
    // configuration
    protected $config = array();

    /**
     * New proxy instance
     */
    function __construct()
    {
        // load the config
        $config = array();
        require "config.php";
        
        // check config
        if (!count($config)) die("Please provide a valid configuration");
        
        $this->config = $config;
        
        // initialize curl
        $this->ch = curl_init();
        @curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->ch, CURLOPT_HEADER, true);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->config["timeout"]);
        curl_setopt($this->ch, CURLOPT_USERAGENT, "PHP Proxy"); 
        if (!empty($this->config['user']) && !empty($this->config['pass'])){
            curl_setopt($this->ch, CURLOPT_USERPWD, "{$this->config['user']}:{$this->config['pass']}");
            curl_setopt($this->ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        }
    }
    
    /**
     * Forward the current request to this url
     *
     * @param string $url
     */
    public function forward($url = '')
    {
        // build the correct url
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
        {
            $url = "https://" . $this->config["server"] . ":" . $this->config["https_port"] . "/" . ltrim($url, "/");
        } 
        else 
        {
            $url = "http://" . $this->config["server"] . ":" . $this->config["http_port"] . "/" . ltrim($url, "/");
        }
        
        // set url
        curl_setopt($this->ch, CURLOPT_URL, $url);
        
        // forward request headers
        $headers = $this->get_request_headers();
        $this->set_request_headers($headers);
        
        // forward post
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            if(in_array($this->get_content_type($headers), array('application/x-www-form-urlencoded','multipart/form-data')))
            {
                $this->set_post($_POST);
            }
            else
            {
                 // just grab the raw post data
                $fp = fopen('php://input','r');
                $post = stream_get_contents($fp);
                fclose($fp);
                $this->set_post($post);
            }
            
        } elseif ($_SERVER["REQUEST_METHOD"] == "HEAD") {
          curl_setopt($this->ch, CURLOPT_NOBODY, true);
        }
        
        // execute
        $data = curl_exec($this->ch);
        $info = curl_getinfo($this->ch);
        
        // extract response from headers
        $body = $info["size_download"] ? substr($data, $info["header_size"], $info["size_download"]) : "";
        
        // forward response headers
        $headers = substr($data, 0, $info["header_size"]);
        $this->set_response_headers($headers);
        
        // close connection
        curl_close($this->ch);
        
        // output html
        echo $body;
    }
    
    /**
     *  Get the content-type of the request
     */
    protected function get_content_type( $headers )
    {
        foreach( $headers as $name => $value ){
            if( 'content-type' == strtolower($name) ){
                $parts = explode(';', $value);
                return strtolower($parts[0]);
            }
        }
        return null;
    }
    
    /**
     * Get the headers of the current request
     */
    protected function get_request_headers()
    {
        // use native getallheaders function
        if (function_exists('getallheaders')) return getallheaders();

        // fallback
        $headers = '';
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }

        return $headers;
    }

    /**
     * Pass the request headers to cURL
     *
     * @param array $request
     */
    protected function set_request_headers($request)
    {
        // headers to strip
        $strip = array("Content-Length", "Host");
        
        $headers = array();
        foreach ($request as $key => $value)
        {
            if ($key && !in_array($key, $strip))
            {
                $headers[] = "$key: $value";
            }
        }
        
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
    }
    
    /**
     * Pass the cURL response headers to the user
     *
     * @param array $response
     */
    protected function set_response_headers($response)
    {
        // headers to strip
        $strip = array("Transfer-Encoding");
        
        // split headers into an array
        $headers = explode("\n", $response);
        
        // process response headers
        foreach ($headers as &$header)
        {
            // skip empty headers
            if (!$header) continue;
            
            // get header key
            $pos = strpos($header, ":");
            $key = substr($header, 0, $pos);
            
            // modify redirects
            if (strtolower($key) == "location")
            {
                $base_url = $_SERVER["HTTP_HOST"];
                $base_url .= rtrim(str_replace(basename($_SERVER["SCRIPT_NAME"]), "", $_SERVER["SCRIPT_NAME"]), "/");
                
                // replace ports and forward url
                $header = str_replace(":" . $this->config["http_port"], "", $header);
                $header = str_replace(":" . $this->config["https_port"], "", $header);
                $header = str_replace($this->config["server"], $base_url, $header);
            }
            
            // set headers
            if (!in_array($key, $strip))
            {
                header($header, FALSE);
            }
        }
    }
    
    /**
     * Set POST values including FILES support
     *
     * @param array $post
     */
    protected function set_post($post)
    {
        // file upload support
        if (count($_FILES))
        {
            foreach ($_FILES as $key => $file)
            {
                $parts = pathinfo($file["tmp_name"]);
                $name = $parts["dirname"] . "/" . $file["name"];
                rename($file["tmp_name"], $name);
                $post[$key] = "@" . $name;
            }
        } 
        else if( is_array( $post ) )
        {
            $post = http_build_query($post);
        }
        
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post);
    }

}
