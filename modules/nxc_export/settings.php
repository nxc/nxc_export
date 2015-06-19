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
$Result['navigation_part'] = 'ezsetupnavigationpart';
$Result['path']            = array(
	array(
		'text' => ezpI18n::tr( 'settings', 'Setup' ),
		'url'  => 'setup/cache'
	),
	array(
		'text' => ezpI18n::tr( 'extension/nxc_export', 'NXC Export' ),
		'url'  => false
	)
);
?>