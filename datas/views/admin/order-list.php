<?php 
if (!$this->is_ajax()){
 $this->add_custom_javascript("
 	var order_dialog, shipping_dialog;
	function showOrder(order_id)
	{
		createScreenLoading();
		jQuery.get(baseUrl+'?searchfriend_task=manage_showorder', {order_id: order_id, mod: 'rawmode'}, function(data){
			jQuery('#data-order-details').html(data);
			order_dialog.dialog(\"open\");
			clearScreenLoading();
		});
	}
	function showShippingDetails(user_id, order_id)
	{
		createScreenLoading();
		jQuery.get(baseUrl+'?searchfriend_task=manage_showorder_shipping', {user_id: user_id, order_id: order_id, mod: 'rawmode'}, function(data){
			jQuery('#data-shipping-order-details').html(data);
			shipping_dialog.dialog(\"open\");
			clearScreenLoading();
		});
	}
	jQuery(document).ready(function(){
		var dialog_form = jQuery('<div />');
		dialog_form.attr('id', 'order-details');
		dialog_form.attr('title', '".__('Order Details', 'searchfriend')."');
		var dialog_form_data = jQuery('<div />');
		dialog_form_data.attr('id', 'data-order-details');
		dialog_form.append(dialog_form_data);
		jQuery('body').append(dialog_form);
		order_dialog = jQuery('#order-details').dialog({
	      	autoOpen: false,
	      	height: 600,
	      	width: 750,
	      	modal: true,
	      	draggable: true,
	      	buttons: {
	        	Cancel: function() {
	          		order_dialog.dialog(\"close\");
	        	}
	      	},
	      	open: function(event, ui) {
			},
	      	close: function() {
	      	}
	    });	    
	    var shipping_dialog_form = jQuery('<div />');
		shipping_dialog_form.attr('id', 'order-shipping-details');
		shipping_dialog_form.attr('title', '".__('User Shipping Details', 'searchfriend')."');
		var shipping_dialog_form_data = jQuery('<div />');
		shipping_dialog_form_data.attr('id', 'data-shipping-order-details');
		shipping_dialog_form.append(shipping_dialog_form_data);
		jQuery('body').append(shipping_dialog_form);
		shipping_dialog = jQuery('#order-shipping-details').dialog({
	      	autoOpen: false,
	      	height: 550,
	      	width: 500,
	      	modal: true,
	      	draggable: true,
	      	buttons: {
	      		'Save': function(){
	      			if (jQuery('#key_save').length > 0){
		      			createScreenLoading();
		      			jQuery.get(baseUrl+'?searchfriend_task=shipping_order_save_log', 
		      				{key_save: jQuery('#key_save').val(), event_log: jQuery('#event_log').val(), status_log: jQuery('#status_log').val()}, function(){
		      				shipping_dialog.dialog(\"close\");
		      				clearScreenLoading();
		      			});
	      			}
	      		},
	        	Cancel: function() {
	          		shipping_dialog.dialog(\"close\");
	        	}
	      	},
	      	open: function(event, ui) {
			},
	      	close: function() {
	      	}
	    });
	    jQuery('.table-order-list').DataTable({
            'searching'   : true,
            'lengthChange': false,
            \"order\"       : [[ 1, \"desc\" ]]
        });
	});
", 'order-details')->burn_media();
}
?>
<div id="data-order-list">
<table class="wp-list-table widefat fixed striped pages table-order-list">
	<thead>
		<tr>
			<th width="30%"><?php echo __('Order ID', 'searchfriend');?></th>
			<th width="50%"><?php echo __('Order Status', 'searchfriend');?></th>
			<th width="20%"><?php echo __('Action', 'searchfriend');?></th>
		</tr>
	</thead>
	<tbody>
	<?php $orders = $this->get_all_orders();?>
	<?php if (count($orders)):?>
	<?php foreach ($orders as $order):?>
	<?php $order_data = $this->get_order_details($order->order_id);?>
	<tr>
		<td><a href="<?php echo get_site_url();?>/wp-admin/post.php?post=<?php echo $order_data->order_id;?>&action=edit"><?php echo $order_data->order_id;?></a></td>
		<td><?php echo $order_data->post_status;?></td>
		<td>
			<i class="icon-show" title="<?php echo __('Show Order', 'searchfriend');?>" onClick="javascript:showOrder(<?php echo $order_data->order_id;?>);">
				<img src="<?php echo searchfriend_url;?>/datas/assets/images/show.png"/>
			</i>
		</td>
	</tr>
	<?php endforeach;?>
	<?php endif;?>
	</tbody>
</table>
</div>
