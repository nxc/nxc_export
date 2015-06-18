<?php
/**
 * @package nxcExport
 * @class   nxcExportAttributeConverterCountry
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    23 Apr 2010
 **/

class nxcExportAttributeConverterCountry extends nxcExportAttributeConverter {

	public function __construct() {
	}

	public static function export( eZContentObjectAttribute $attribute ) {
		$country = eZCountryType::fetchCountry( $attribute->attribute( 'data_text' ), 'Alpha2' );
		return is_array( $country ) ? $country['Name'] : null;
	}
}
?>