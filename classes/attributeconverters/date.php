<?php
/**
 * @package nxcExport
 * @class   nxcExportAttributeConverterDate
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    23 Apr 2010
 **/

class nxcExportAttributeConverterDate extends nxcExportAttributeConverter {

	public function __construct() {
	}

	public static function export( eZContentObjectAttribute $attribute ) {
		return date( 'd M Y H:i:s', $attribute->attribute( 'content' )->attribute( 'timestamp' ) );
	}
}
?>