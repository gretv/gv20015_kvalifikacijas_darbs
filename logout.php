<?php
require 'config/constants.php';

// destoy all sessions and redirect user to login page
session_destroy();
header('location: ' . ROOT_URL);
die();
