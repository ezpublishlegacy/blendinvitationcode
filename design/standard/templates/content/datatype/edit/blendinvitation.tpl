{* DO NOT EDIT THIS FILE! Use an override template instead. *}
{default attribute_base='ContentObjectAttribute'
         html_class='full'}
{def $val=$attribute.data_text}
{if $val|not}
{set $val=ezhttp('code','get')}
{/if}
{if $val}
{set $val=concat($val|extract(0,4),'-',$val|extract(4,4),'-',$val|extract(8))}
{/if}
<input id="ezcoa-{if ne( $attribute_base, 'ContentObjectAttribute' )}{$attribute_base}-{/if}{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}" class="{eq( $html_class, 'half' )|choose( 'box', 'halfbox' )} ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}" type="text" size="70" name="{$attribute_base}_data_invitation_{$attribute.id}" value="{$val|wash( xhtml )}" />
{/default}

