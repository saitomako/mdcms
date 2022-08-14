<?php
require_once "spyc/Spyc.php";

class MyTemplate {
    var $conf;
    function __construct($config) {
       $this->conf = Spyc::YAMLLoad($config); 
    }
    function show($tpl_file) {
        $v = $this;
        $conf = $this->conf;
        include dirname(__DIR__)."/assets/templates/{$tpl_file}";
    }
}
