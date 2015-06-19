<?php
/**
 * @package nxcExport
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    06 Apr 2010
 **/

$response = array();
$response['status'] = 200;
$response['errors'] = array();

$class = eZContentClass::fetch( $Params['classID'] );
if( !( $class instanceof eZContentClass ) ) {
	$response['status'] = 500;
	$response['errors'][] = ezpI18n::tr( 'extension/nxc_export', 'Can not fetch the class' );
}

if( $response['status'] == 200 ) {
	$ini                = eZINI::instance( 'nxcexport.ini' );
	$availableDatatypes = array_unique( $ini->variable( 'General', 'AvailableDatatypes' ) );

	$availableAttributes = array();
	$classAttributes     = $class->attribute( 'data_map' );
	foreach( $classAttributes as $attribute ) {
		if( in_array( $attribute->attribute( 'data_type_string' ), $availableDatatypes ) ) {
			$availableAttributes[] = $attribute;
		}
	}

	if( count( $availableAttributes ) === 0 ) {
		$response['status'] = 500;
		$response['errors'][] =	ezpI18n::tr( 'extension/nxc_export', 'There are no available attributes for export in selected class' );
	} else {
		include_once( 'kernel/common/template.php' );
		$tpl = templateInit();
		$tpl->setVariable( 'attributes', $availableAttributes );
		
		$response['data'] = array();
		$response['data']['availableAttributes'] = $tpl->fetch( 'design:nxcexport/available_attributes.tpl' );
	}
}

echo json_encode($response);
eZExecution::cleanExit();

?>