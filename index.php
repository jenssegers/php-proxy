<?php
include('proxy.php');

$proxy = new Proxy();
$proxy->forward($_SERVER['REQUEST_URI']);