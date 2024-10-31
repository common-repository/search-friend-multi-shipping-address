<?php
$this->add_custom_javascript("
	function saveConfigs()
	{
		createScreenLoading();
		var config_fields = new Array(), i=0;
		jQuery('table.table-config-field-list').find('input').each(function(){
			config_fields[i++] = {'name': jQuery(this).attr('name'), 'value': jQuery(this).val()};			
		});
		jQuery.get(baseUrl+'?searchfriend_task=save_configs', {config_fields: config_fields}, function(){
			clearScreenLoading();
		});
	}
")->burn_media();
$this->add_custom_style("
	table.table-config-field-list input{
		width: 400px;
	}
	button.btn-save{
		margin-left: 270px!important; 
		height: 45px!important; 
		line-height: 45px!important; 
	}
")->burn_media();
?>
<table class="wp-list-table widefat fixed striped pages table-config-field-list">
	<thead>
		<tr>
			<th width="250px"><?php echo __('Field Name', 'searchfriend');?></th>
			<th width="80%"><?php echo __('Value', 'searchfriend');?></th>
		</tr>
	</thead>
	<tbody>
	<tr>
		<td><?php echo __('Time clear old order session', 'searchfriend');?></td>
		<td>
			<input type="text" name="searchfriend_time_clear_old_session" value="<?php echo get_option('searchfriend_time_clear_old_session', 86400);?>" />
		</td>
	</tr>
	<tr>
		<td><?php echo __('Shipping get product URL', 'searchfriend');?></td>
		<td>
			<input type="text" name="searchfriend_config_shipping_get_product_url" value="<?php echo get_option('searchfriend_config_shipping_get_product_url', get_site_url().'/shipping-product-getting/');?>" />
		</td>
	</tr>
	<tr>
		<td><?php echo __('Barcode link of product', 'searchfriend');?></td>
		<td>
			<input type="text" name="searchfriend_barcode_link_of_product" value="<?php echo get_option('searchfriend_barcode_link_of_product', get_site_url().'?searchfriend_task=get-barcode&text={order_id}');?>" />
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<button class="button btn-save" type="button" onClick="javascript:saveConfigs();">
				<i class="icon-save" style="float: left;" title="<?php echo __('Save', 'searchfriend');?>">
					<img src="<?php echo searchfriend_url;?>/datas/assets/images/save.png"/>
				</i>
				<?php echo __('Save configures', 'searchfriend');?>
			</button>
		</td>
	</tr>
	</tbody>
</table>
