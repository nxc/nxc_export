<?php
/**
 * @package nxcExport
 * @class   nxcExportAttributeConverterXML
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    18 May 2010
 **/

class nxcExportAttributeConverterXML extends nxcExportAttributeConverter {

	public function __construct() {
	}

	public static function export( eZContentObjectAttribute $attribute ) {
		return strip_tags( $attribute->attribute( 'content' )->attribute( 'output' )->attribute( 'output_text' ) );
	}
}
?>