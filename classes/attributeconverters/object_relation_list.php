<?php
/**
 * @package nxcExport
 * @class   nxcExportAttributeConverterObjectRelationList
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    18 May 2010
 **/

class nxcExportAttributeConverterObjectRelationList extends nxcExportAttributeConverter {

	public function __construct() {
	}

	public static function export( eZContentObjectAttribute $attribute ) {
		$relatedObjectNames = array();

		$content      = $attribute->attribute( 'content' );
		$relationList = $content['relation_list'];
		foreach( $relationList as $relation ) {
			$object = eZContentObject::fetch( $relation['contentobject_id'] );
			if( $object instanceof eZContentObject ) {
				$relatedObjectNames[] = $object->attribute( 'name' );
			}
		}

		return implode( ', ', $relatedObjectNames );
	}
}
?>