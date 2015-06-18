<?php
/**
 * @package nxcExport
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    06 Apr 2010
 **/

$response = new nxcMootoolsAJAXResponse();
$response->setStatus( nxcMootoolsAJAXResponse::STATUS_SUCCESS );

$class = eZContentClass::fetch( $Params['classID'] );
if( !( $class instanceof eZContentClass ) ) {
	$response->setStatus( nxcMootoolsAJAXResponse::STATUS_ERROR );
	$response->addError( ezi18n( 'extension/nxc_export', 'Can not fetch the class' ), 'class_attributes' );
}

if( $response->getStatus() === nxcMootoolsAJAXResponse::STATUS_SUCCESS ) {
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
		$response->setStatus( nxcMootoolsAJAXResponse::STATUS_ERROR );
		$response->addError(
			ezi18n( 'extension/nxc_export', 'There are no available attributes for export in selected class' ),
			'class_attributes'
		);
	} else {
		include_once( 'kernel/common/template.php' );
		$tpl = templateInit();
		$tpl->setVariable( 'attributes', $availableAttributes );

		$response->availableAttributes = $tpl->fetch( 'design:nxcexport/available_attributes.tpl' );
	}
}

$response->output();
?>