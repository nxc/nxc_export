<?php
/**
 * @package nxcExport
 * @class   nxcExportAttributeConverterObjectRelation
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    22 Apr 2010
 **/

class nxcExportAttributeConverterObjectRelation extends nxcExportAttributeConverter {

	public function __construct() {
	}

	public static function export( eZContentObjectAttribute $attribute ) {
		$relatedObject = $attribute->attribute( 'content' );
		return ( $relatedObject instanceof eZContentObject ) ? $relatedObject->attribute( 'name' ) : null;
	}
}
?>