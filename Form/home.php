<?php
$title = "Home Page";
if (isset($_COOKIE['logged'])) {
    session_start();
}
require_once 'base/header.html';
