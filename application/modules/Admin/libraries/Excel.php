<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once('SimpleExcel/SimpleExcel.php');
use SimpleExcel\SimpleExcel;

class Excel extends SimpleExcel {

    public function __construct() {
        parent::__construct();
    }

}

?>
