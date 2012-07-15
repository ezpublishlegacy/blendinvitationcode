<?php

$Module = array(
    'name' => 'invitation',
    'variable_params' => true
);

$ViewList = array();

//Admin Interface
$ViewList['list'] = array(
    'functions' => array('admin'),
    "default_navigation_part" => 'ezusernavigationpart',    
    'script' => 'list.php'
);

$ViewList['add'] = array(
    'functions' => array('admin'),
    "default_navigation_part" => 'ezusernavigationpart',    
    'script' => 'add.php'
);

$ViewList['import'] = array(
    'functions' => array('admin'),
    'script' => 'import.php'
);

$ViewList['remove'] = array(
    'functions' => array('admin'),
    'script' => 'remove.php'
);

$FunctionList = array();
$FunctionList['admin'] = array();