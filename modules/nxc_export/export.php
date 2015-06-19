<?php
/**
 * @package nxcExport
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    06 Apr 2010
 **/

ini_set( 'display_errors', 'On' );
ini_set( 'memory_limit', '2048M' );

$module = $Params['Module'];
$http   = eZHTTPTool::instance();
global $type;
global $activeSheetIndex;
$activeSheetIndex = 0;



if(
	$http->hasPostVariable( 'nxc_export_class_array' ) === false ||
	$http->hasPostVariable( 'nxc_export_attributes_array' ) === false
) {
	return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

$objPHPExcel = false;
$fp = false;
$type = ( $http->postVariable( 'nxc_export_type' ) === 'excel' ) ? 'excel' : 'csv';
$attributesArray = $http->postVariable('nxc_export_attributes_array');

if( $type === 'excel' ) {
	eZDir::mkdir( 'var/storage/nxc_export' , false, true);	
	$filename = 'var/storage/nxc_export/export.xls';
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setCreator( 'eZ Publish' );	
} else {
	$filename   = 'var/storage/nxc_export/export.csv';
	eZDir::mkdir( 'var/storage/nxc_export' , false, true);	
	$fp         = fopen( $filename, 'w' );
}


foreach( $http->postVariable( 'nxc_export_class_array' ) as $classId) {	
	if ( $attributesArray && isset($attributesArray[$classId]) ) {
		$result = processClass( $classId, $attributesArray[$classId], $fp, $objPHPExcel );
		if ( !$result ) {
			return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
		}
	}
	else {
		return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
	}
}

if( $type === 'excel' ) {
	$writer = new PHPExcel_Writer_Excel5( $objPHPExcel );
	$writer->save( $filename );
} else {
	fclose( $fp );
}

$file = new eZFile();
$file->download( $filename );
eZExecution::cleanExit();

function processClass( $classId, $attributes, $fp = false, $objPHPExcel = false ) {

	global $type, $activeSheetIndex;
	$ini = eZINI::instance( 'nxcexport.ini' );
	$datatypeHandlers       = $ini->variable( 'General', 'DatatypeHandlers' );
	$classAttributeHandlers = $ini->variable( 'General', 'ClassAttributeHandlers' );
	$availableDatatypes     = $ini->variable( 'General', 'AvailableDatatypes' );
	$enclosure = $ini->variable( 'General', 'Enclosure' );
	$delimiter = $ini->variable( 'General', 'Delimiter' );
	if( $delimiter == 'tab' ) {
		$delimiter = "\t";
	}
	
	$class = eZContentClass::fetch( $classId );
	if( !( $class instanceof eZContentClass ) ) {
		return false;
	}
	
	if( count( $attributes ) === 0 ) {
		return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
	}
	
	if( $type === 'excel' ) {
		$objPHPExcelSheet = new PHPExcel_Worksheet($objPHPExcel);
		$objPHPExcel->addSheet($objPHPExcelSheet);
		$objPHPExcelSheet->setTitle($class->attribute( 'name' ));		
	}

	$params = array(
		'Depth'            => false,
		'ClassFilterType'  => 'include',
		'ClassFilterArray' => array( $class->attribute( 'identifier' ) ),
		'LoadDataMap'      => false,
		'Limitation'       => array()	
	);
	$nodes = eZContentObjectTreeNode::subTreeByNodeID( $params, 1 );	

	$converters = array();
	$i = 0;

	foreach( $nodes as $node ) {
		$memoryUsage = memory_get_usage( true );
		$dataMap   = $node->attribute( 'data_map' );
		$exportRow = array();

		foreach( $attributes as $attributeIdentifier ) {
			if( isset( $dataMap[ $attributeIdentifier ] ) ) {
				$attribute = $dataMap[ $attributeIdentifier ];

				if( in_array( $attribute->attribute( 'data_type_string' ), $availableDatatypes ) ) {
					if( isset( $converters[ $attributeIdentifier ] ) === false ) {
						$converterClass = 'nxcExportAttributeConverter';
						if( isset( $classAttributeHandlers[ $class->attribute( 'identifier' ) . '/' . $attributeIdentifier ] ) ) {
							$converterClass = $classAttributeHandlers[ $class->attribute( 'identifier' ) . '/' . $attributeIdentifier ];
						} elseif( isset( $datatypeHandlers[ $attribute->attribute( 'data_type_string' ) ] ) ) {
							$converterClass = $datatypeHandlers[ $attribute->attribute( 'data_type_string' ) ];
						}

						$converters[ $attributeIdentifier ] = $converterClass;
					}

					$attributeData = call_user_func(
						array(
							$converters[ $attributeIdentifier ],
							'export'
						),
						$dataMap[ $attributeIdentifier ]
					);
					if( is_array( $attributeData ) ) {
						foreach( $attributeData as $key => $value ) {
							$exportRow[ $attributeIdentifier . '_' . $key ] = $value;
						}
					} else {
						$exportRow[ $attributeIdentifier ] = $attributeData;
					}
				}
			}
			else if ( $attributeIdentifier == 'page_url' ) {
				$url = $node->attribute('url_alias');
				eZURI::transformURI($url, true, "full");
				$exportRow[ $attributeIdentifier ] = $url;
			}
		}
		eZContentObject::clearCache();

		if( $type === 'excel' ) {
			$dataIndex = 0;
			foreach( $exportRow as $data ) {
				$objPHPExcelSheet->setCellValueByColumnAndRow( $dataIndex, $i, $data );
				$dataIndex++;
			}
		} else {
			fputcsv( $fp, $exportRow, $delimiter, $enclosure );
		}
		$i++;	
	}
	return true;
}
?>