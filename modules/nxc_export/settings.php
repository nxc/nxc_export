<?php
/**
 * @package nxcExport
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    01 Apr 2010
 **/

$ini              = eZINI::instance( 'nxcexport.ini' );
$availableClasses = array_unique( $ini->variable( 'General', 'AvailableClasses' ) );

$classes = array();
foreach( $availableClasses as $identifier ) {
	$class = eZContentClass::fetchByIdentifier( $identifier );
	if( $class instanceof eZContentClass ) {
		$classes[] = $class;
	}
}

include_once( 'kernel/common/template.php' );
$tpl = templateInit();
$tpl->setVariable( 'available_classes', $classes );

$Result = array();
$Result['content']         = $tpl->fetch( 'design:nxcexport/settings.tpl' );
$Result['navigation_part'] = 'nxceventmanagernavigationpart';
$Result['left_menu']       = 'design:parts/datalist/menu.tpl';
$Result['path']            = array(
	array(
		'text' => ezi18n( 'extension/nxc_export', 'Event managment' ),
		'url'  => 'eventmanager/dashboard'
	),
	array(
		'text' => ezi18n( 'extension/nxc_export', 'Reports' ),
		'url'  => false
	)
);
?>