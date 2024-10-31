<?php
 $this->add_custom_javascript("
 	var activity_log_user_dialog_form;
 	jQuery(document).ready(function(){
 		jQuery('.table-activity-logs-list').DataTable({
            'searching'   : true,
            'lengthChange': false,
            \"order\"       : [[ 1, \"desc\" ]]
        });
    });
    function showUserDetails(user_id)
	{
		createScreenLoading();
		jQuery.get(baseUrl+'?searchfriend_task=manage_show_shipping_details', {user_id: user_id, mod: 'rawmode'}, function(data){
			jQuery('#data-activity-user-details').html(data);
			activity_log_user_dialog_form.dialog(\"open\");
			clearScreenLoading();
		});
	}
	jQuery(document).ready(function(){
		var activity_log_user_form = jQuery('<div />');
		activity_log_user_form.attr('id', 'activity-user-details');
		activity_log_user_form.attr('title', '".__('User Shipping Details', 'searchfriend')."');
		var activity_log_user_form_data = jQuery('<div />');
		activity_log_user_form_data.attr('id', 'data-activity-user-details');
		activity_log_user_form.append(activity_log_user_form_data);
		jQuery('body').append(activity_log_user_form);
		activity_log_user_dialog_form = jQuery('#activity-user-details').dialog({
	      	autoOpen: false,
	      	height: 450,
	      	width: 400,
	      	modal: true,
	      	draggable: true,
	      	buttons: {
	        	Cancel: function() {
	          		activity_log_user_form.dialog(\"close\");
	        	}
	      	},
	      	open: function(event, ui) {
			},
	      	close: function() {
	      	}
	    });
	});
 ")->burn_media();
?>
<table class="wp-list-table widefat fixed striped pages table-activity-logs-list">
	<thead>
		<tr>
			<th width="20%"><?php echo __('Date', 'searchfriend');?></th>
			<th width="15%"><?php echo __('Event', 'searchfriend');?></th>
			<th width="11%"><?php echo __('Order ID', 'searchfriend');?></th>
			<th width="22%"><?php echo __('From', 'searchfriend');?></th>
			<th width="22%"><?php echo __('To', 'searchfriend');?></th>
			<th width="10%"><?php echo __('Status', 'searchfriend');?></th>
		</tr>
	</thead>
	<tbody>
	<?php $orders = $this->get_all_orders();?>
	<?php if (count($orders)):?>
	<?php $events = $this->get_event_logs();?>
	<?php $status = $this->get_status_logs();?>
	<?php foreach ($orders as $order):?>
	<?php $users_assign = (array)json_decode($order->cart_product_assign);?>
	<?php foreach ($users_assign as $user):?>
	<?php if (isset($user->user_id)):?>
	<?php $activity_logs = $this->get_user_order_shipping_details($order->order_id, $user->user_id);?>
	<?php if (is_object($activity_logs)):?>
	<tr>
		<td><?php echo $activity_logs->log_date;?></td>
		<td><?php echo $events[$activity_logs->event];?></td>
		<td><a href="<?php echo get_site_url();?>/wp-admin/post.php?post=<?php echo $order->order_id;?>&action=edit"><?php echo $order->order_id;?></a></td>
		<td>			
			<?php if ((int) $order->user_created > 0):?>
			<?php $user = $this->get_user_data($order->user_created);?>
			<label class="user-info-name"><?php echo $user->user_nicename;?></label>
			<i class="icon-show-log" title="<?php echo __('Show Details', 'searchfriend');?>" onClick="javascript:showUserDetails('<?php echo $order->user_created;?>');">
				<img src="<?php echo searchfriend_url;?>/datas/assets/images/show.png"/>
			</i>
			<?php else:?>
			<?php echo $order->user_created;?>
			<?php endif;?>
		</td>
		<td>
			<?php if (strpos($activity_logs->user_id, 'cus-') !== false):?>
			<?php $user = $this->get_custom_user_data(str_replace('cus-', '', $activity_logs->user_id));?>
			<label class="user-info-name"><?php echo $user->first_name.' '.$user->last_name;?></label>
			<i class="icon-show-log" title="<?php echo __('Show Details', 'searchfriend');?>" onClick="javascript:showUserDetails('cus-<?php echo $order->user_created;?>');">
				<img src="<?php echo searchfriend_url;?>/datas/assets/images/show.png"/>
			</i>
			<?php else:?>
			<?php $user = $this->get_user_data($activity_logs->user_id);?>
			<label class="user-info-name"><?php echo $user->user_nicename;?></label>
			<i class="icon-show-log" title="<?php echo __('Show Details', 'searchfriend');?>" onClick="javascript:showUserDetails('<?php echo $activity_logs->user_id;?>');">
				<img src="<?php echo searchfriend_url;?>/datas/assets/images/show.png"/>
			</i>
			<?php endif;?>
		</td>
		<td><i class="shipping-status-<?php echo $status[$activity_logs->status];?>">&nbsp;</i></td>
	</tr>
	<?php endif;?>
	<?php endif;?>
	<?php endforeach;?>
	<?php endforeach;?>
	<?php endif;?>
	</tbody>
</table>