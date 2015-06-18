{foreach $attributes as $attribute}
<li>
	<input type="checkbox" name="nxc_export_attributes[{$attribute.identifier}]" value="1" /> {$attribute.name} ({$attribute.data_type_string})
</li>
{/foreach}