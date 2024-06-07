<?php

if(!isset($_POST['email']) && !isset($_POST['name']) && !isset($_POST['password'])) return false;

require('../conf/function.inc.php');


register($_POST);