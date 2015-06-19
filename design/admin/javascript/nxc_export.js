$(document).ready(function ($){
	window.messageStack = ( ( window.messageStack ) === false ) ? new NXC.MessageStack() : window.messageStack;
	window.exportFormArray = new Object();

	var classSelect         = $( '#nxc-export-class' );
	var attributesLoader    = $( '#nxc-export-attributes-loader' );
	var attributesList      = $( '#nxc-export-available-attributes' );
	var attributesContainer = attributesList.parent().parent();
	var form                = $( '#nxc-export-form' );
	var saveBtn             = $( '.nxc_export_save' );
	var exportBtn			= $('.nxc_export_execute');
	var selectedExportBlock = $('#selected_values_block');
	
	form.addClass('no-submit');
	exportBtn.css('display','none');
	selectedExportBlock.css('display','none');

	$(classSelect).change( function() {
		attributesContainer.css( 'display', 'none' );
		attributesList.empty();
		var classSelectValue = $(classSelect).val();

		if( classSelectValue != - 1 ) {
			attributesLoader.css( 'display', 'inline' );						
			$.getJSON( nxcExportGetAttributesBaseURL + classSelectValue, function( response ) {
				attributesLoader.css( 'display', 'none' );

				if( parseInt(response.status) === 200 ) {
					attributesList.html( response.data.availableAttributes );
					attributesContainer.css( 'display', 'block' );
					if ( window.exportFormArray.hasOwnProperty( classSelectValue ) ) {
						$(attributesList).find('input').each(function(k,v) {
							for ( var i=0;i<window.exportFormArray[ classSelectValue ].attributes.length; i++ ) {
								if ( $(v).attr('name') == window.exportFormArray[ classSelectValue ].attributes[i].value ) {
									$(v).attr('checked','checked');
								}
							}
						});
					}
					
					
				} else {
					attributesLoader.html( response.errors );
				}
			});
		}
	} );
	
	$(saveBtn).click(function(e) {
		e.preventDefault();
		var currentClassID = $(classSelect).val();
		if ( currentClassID !== -1 ) {
			if ( !window.exportFormArray.hasOwnProperty( currentClassID ) ) {
				window.exportFormArray[ currentClassID ] = new Object();
				window.exportFormArray[ currentClassID ].name = $(classSelect).children('option:selected').text();
			}			
			window.exportFormArray[ currentClassID ].attributes = new Array();
			$(attributesList).find('input:checked').each( function(k, v) {
				var tmpObj = new Object();
				tmpObj.name = $(v).parent('li').text();
				tmpObj.value = $(v).attr('name');
				window.exportFormArray[ currentClassID ].attributes.push(tmpObj);
			});
		}
		showSelectedValues();
	});
	
	$(form).submit(function(e) {
		var form = $(this);
		var formTimeout = false;
		$(saveBtn).trigger('click');
		if ( form.hasClass('no-submit') ) {
			e.preventDefault();
			formTimeout = setTimeout(function() {
				form.removeClass('no-submit').submit();
			}, 1000);
		}
		else {
			clearTimeout( formTimeout );
			form.addClass('no-submit')
		}
	});
	
	function showSelectedValues() {
		var selectedValues = $( '#selected_values' );
		$(selectedValues).empty();
		var content = "";
		if ( !$.isEmptyObject(window.exportFormArray) ) {
			$.each(window.exportFormArray,function(k,v) {				
				if ( v.attributes.length > 0 ) {
					content += "<div>";
					content += "<h2>"+v.name+"</h2>";
					content += "<input type='hidden' name='nxc_export_class_array[]' value='"+k+"'></h2>";
					content += "<ul>";
					$(v.attributes).each( function(k_a,v_a) {
						content += "<li>"+v_a.name+"</li>";
						content += "<input type='hidden' name='nxc_export_attributes_array["+k+"][]' value='"+v_a.value+"'></h2>";
					});
					content += "</ul>";
					content += "</div>";
				}
			});
			$(selectedValues).html(content);
			exportBtn.css('display','inline');
			selectedExportBlock.css('display','block');
		}
		else {
			exportBtn.css('display','none');
			selectedExportBlock.css('display','none');
		}
	}
	
} );