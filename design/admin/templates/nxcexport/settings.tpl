<div class="context-block">

	<form method="post" action="{'/nxc_export/export'|ezurl( 'no' )}" name="nxc_export_form" id="nxc-export-form">

		<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
			<h1 class="context-title">{'Export settings'|i18n( 'extension/nxc_export' )}</h1>
			<div class="header-mainline"></div>
		</div></div></div></div></div></div>

		<div class="box-ml"><div class="box-mr"><div class="box-content">
			<div class="context-toolbar"><div class="break"></div></div>

				<div class="nxc-export-container">

					{*<div class="nxc-export-block">
						<div class="nxc-export-block-title">{'Date filter'|i18n( 'extension/nxc_export' )}</div>
						<div class="nxc-export-block-content">
							from <input readonly="readonly" type="text" value="{currentdate()|sub( 60|mul( 60 )|mul( 24 )|mul( 7 ) )}" name="nxc_export_start_date" id="nxc-export-start-date" /> <img alt="{'Pop-up calendar'|i18n( 'extension/datalist' )}" title="{'Pop-up calendar'|i18n( 'extension/datalist' )}" src="{'datepicker/calendar.gif'|ezimage( 'no' )}" id="nxc-export-start-date-toggler" class="datepicker-toggler" /> <img alt="{'Clear date'|i18n( 'extension/datalist' )}" title="{'Clear date'|i18n( 'extension/datalist' )}" src="{'datepicker/empty.png'|ezimage( 'no' )}" id="nxc-export-start-date-empty" /> to <input readonly="readonly" type="text" value="{currentdate()}" name="nxc_export_end_date" id="nxc-export-end-date" /> <img alt="{'Pop-up calendar'|i18n( 'extension/datalist' )}" title="{'Pop-up calendar'|i18n( 'extension/datalist' )}" src="{'datepicker/calendar.gif'|ezimage( 'no' )}" id="nxc-export-end-date-toggler" class="datepicker-toggler" /> <img alt="{'Clear date'|i18n( 'extension/datalist' )}" title="{'Clear date'|i18n( 'extension/datalist' )}" src="{'datepicker/empty.png'|ezimage( 'no' )}" id="nxc-export-end-date-empty" class="datepicker-empty" />
						</div>
					</div>*}
					<div class="break"></div>

					<div id="nxc-export-class-select-container" class="nxc-export-block">
						<div class="nxc-export-block-title">{'Select a class'|i18n( 'extension/nxc_export' )}</div>
						<div class="nxc-export-block-content">
							<select id="nxc-export-class" name="nxc_export_class">
								<option value="-1">{'- Select a class -'|i18n( 'extension/nxc_export' )}</option>
								{foreach $available_classes as $class}
								<option value="{$class.id}">{$class.name}</option>
								{/foreach}
							</select><br />
							<img style="display: none;" id="nxc-export-attributes-loader" src="{'nxcexport/loader.gif'|ezimage( 'no' )}" alt="{'Loading'|i18n( 'extension/nxc_export' )}" title="{'Loading'|i18n( 'extension/nxc_export' )}" />
						</div>
					</div>
					<div class="break"></div>

					<div class="nxc-export-block" style="display: none;">
						<div class="nxc-export-block-title">{'Select available attributes'|i18n( 'extension/nxc_export' )}</div>
						<div class="nxc-export-block-content">
							<ul id="nxc-export-available-attributes">
							</ul>
						</div>
					</div>
					<div class="break"></div>

				</div>

			<div class="context-toolbar"><div class="break"></div></div>
		</div></div></div>

		<div class="controlbar">
			<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
				<div class="block">
					<select name="nxc_export_type">
						<option value="excel">{'Excel'|i18n( 'extension/nxc_export' )}</option>
						<option value="csv">{'CSV'|i18n( 'extension/nxc_export' )}</option>
					</select>
					<input type="submit" name="nxc_export_save" value="{'Apply'|i18n( 'extension/nxc_export' )}/{'Save'|i18n( 'extension/nxc_export' )}" class="button nxc_export_save" />
					<input type="submit" name="nxc_export_execute" value="{'Export'|i18n( 'extension/nxc_export' )}" class="button nxc_export_execute" />
				</div>
			</div></div></div></div></div></div>
		</div>
		<div id="selected_values_block">
			<h2>Classes and attributes that will be exported</h2>
			<div id="selected_values"></div>
		</div>

	</form>

</div>

{literal}
<script type="text/javascript">
	var nxcExportGetAttributesBaseURL = '{/literal}{'nxc_export/ajax_get_class_attributes'|ezurl( 'no' )}{literal}/';	
</script>
{/literal}