<?php
include('proxy.php');

$url = isset($_GET['url']) ? $_GET['url'] : "";

$proxy = new Proxy();
$proxy->forward($url);