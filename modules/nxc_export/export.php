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

if(
	$http->hasPostVariable( 'nxc_export_class' ) === false ||
	$http->hasPostVariable( 'nxc_export_attributes' ) === false
) {
	return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

$class = eZContentClass::fetch( $http->postVariable( 'nxc_export_class' ) );
if( !( $class instanceof eZContentClass ) ) {
	return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

$attributeIdentifiers = array_keys( $http->postVariable( 'nxc_export_attributes' ) );
if( count( $attributeIdentifiers ) === 0 ) {
	return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

$startDate = $http->postVariable( 'nxc_export_start_date' );
$endDate   = $http->postVariable( 'nxc_export_end_date' );

if( is_numeric( $startDate ) && is_numeric( $endDate ) ) {
	$dateFilter = array( 'published', 'between', array( $startDate - 1, $endDate + 24 * 60 * 60 - 1 ) );
} elseif( is_numeric( $startDate ) ) {
	$dateFilter = array( 'published', '>=', $startDate );
} elseif( is_numeric( $endDate ) ) {
	$dateFilter = array( 'published', '<=', $endDate + 24 * 60 * 60 - 1 );
}

$params = array(
	'Depth'            => false,
	'ClassFilterType'  => 'include',
	'ClassFilterArray' => array( $class->attribute( 'identifier' ) ),
	'LoadDataMap'      => false,
	'Limitation'       => array(),
	'AttributeFilter'  => array( $dateFilter )
);
$nodes = eZContentObjectTreeNode::subTreeByNodeID( $params, 1 );

$type = ( $http->postVariable( 'nxc_export_type' ) === 'excel' ) ? 'excel' : 'csv';

$ini = eZINI::instance( 'nxcexport.ini' );

$datatypeHandlers       = $ini->variable( 'General', 'DatatypeHandlers' );
$classAttributeHandlers = $ini->variable( 'General', 'ClassAttributeHandlers' );
$availableDatatypes     = $ini->variable( 'General', 'AvailableDatatypes' );

$converters = array();

if( $type === 'excel' ) {
	$filename = 'var/storage/nxc_export/export.xls';
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setCreator( 'eZ Publish' );
	$objPHPExcel->setActiveSheetIndex( 0 );
	$objPHPExcelSheet = $objPHPExcel->getActiveSheet();
	$objPHPExcelSheet->setTitle( $class->attribute( 'name' ) );
} else {
	$filename   = 'var/storage/nxc_export/export.csv';
	$fp         = fopen( $filename, 'w' );
}

$delimiter = $ini->variable( 'General', 'Delimiter' );
if( $delimiter == 'tab' ) {
	$delimiter = "\t";
}
$enclosure = $ini->variable( 'General', 'Enclosure' );
//fputcsv( $fp, $attributeIdentifiers, $ini->variable( 'General', 'CSVSepartor' ) );
$i = 0;
foreach( $nodes as $node ) {
	$memoryUsage = memory_get_usage( true );

	$dataMap   = $node->attribute( 'data_map' );
	$exportRow = array();

	foreach( $attributeIdentifiers as $attributeIdentifier ) {
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
	//echo( memory_get_usage( true ) - $memoryUsage . ', ' . ( memory_get_usage( true ) / 1024 * 1024 ) . 'mb <br />' );
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
?>