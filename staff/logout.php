<?php

use \utility\Utility;

require_once('../includes/initialize.php');

# Log the user out.
$session->logout();

Utility::redirectTo(Utility::urlFor('/staff/login.php?do=logout'));

