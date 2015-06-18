<?php
/**
 * @package nxcExport
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    01 Apr 2010
 **/

class nxc_exportSettings extends nxcExtensionSettings {

	public $defaultOrder = 0;
	public $dependencies = array( 'nxc_mootools' );

	public function activate() {}

	public function deactivate() {}
}
?>