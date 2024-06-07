<?php

if(!isset($_POST['email']) && !isset($_POST['password'])) return false;

require('../conf/function.inc.php');


login($_POST);