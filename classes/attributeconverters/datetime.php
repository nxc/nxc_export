<?php
/**
 * @package nxcExport
 * @class   nxcExportAttributeConverterDatetime
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    23 Apr 2010
 **/

class nxcExportAttributeConverterDatetime extends nxcExportAttributeConverter {

	public function __construct() {
	}

	public static function export( eZContentObjectAttribute $attribute ) {
		return date( 'd M Y', $attribute->attribute( 'content' )->attribute( 'timestamp' ) );
	}
}
?>