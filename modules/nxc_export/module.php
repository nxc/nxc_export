<?php
/**
 * @package nxcExport
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    01 Apr 2010
 **/

$Module = array(
	'name'            => 'NXC Export',
 	'variable_params' => true
);

$ViewList = array();
$ViewList['settings'] = array(
	'functions'        => array( 'export' ),
	'script'           => 'settings.php',
	'params'           => array()
);
$ViewList['export'] = array(
	'functions'        => array( 'export' ),
	'script'           => 'export.php',
	'params'           => array()
);
$ViewList['ajax_get_class_attributes'] = array(
	'functions'        => array( 'export' ),
	'script'           => 'ajax/get_class_attributes.php',
	'params'           => array( 'classID' )
);

$FunctionList           = array();
$FunctionList['export'] = array();
?>