<?php
/**
 * @package nxcExport
 * @class   nxcExportAttributeConverterUser
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    23 Apr 2010
 **/

class nxcExportAttributeConverterUser extends nxcExportAttributeConverter {

	public function __construct() {
	}

	public static function export( eZContentObjectAttribute $attribute ) {
		return $attribute->attribute( 'content' )->attribute( 'email' );
	}
}
?>