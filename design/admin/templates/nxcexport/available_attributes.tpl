{foreach $attributes as $attribute}
<li>
	<input type="checkbox" name="{$attribute.identifier}" value="1" /> {$attribute.name} ({$attribute.data_type_string})	
</li>
{/foreach}
<li>
	<input type="checkbox" name="page_url" value="1" /> Url address (String)	
</li>