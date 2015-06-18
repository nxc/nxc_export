<?php
/**
 * @package nxcExport
 * @class   nxcExportAttributeConverter
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    06 Apr 2010
 **/

class nxcExportAttributeConverter {

	public function __construct() {
	}

	public static function export( eZContentObjectAttribute $attribute ) {
		return $attribute->toString();
	}
}
?>