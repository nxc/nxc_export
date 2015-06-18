<?php
/**
 * @package nxcExport
 * @class   nxcExportAttributeConverterImage
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    23 Apr 2010
 **/

class nxcExportAttributeConverterImage extends nxcExportAttributeConverter {

	public function __construct() {
	}

	public static function export( eZContentObjectAttribute $attribute ) {
		$image    = $attribute->content()->attribute( 'original' );
		$imageURL = $image['url'];
		if( strlen( $imageURL ) > 0 ) {
			eZURI::transformURI( $imageURL, true, 'full' );
		}
		return $imageURL;
	}
}
?>