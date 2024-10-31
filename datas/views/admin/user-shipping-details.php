<?php
$user_id = $_REQUEST['user_id'];
if (strpos($user_id, 'cus') !== false){
	$user_data = $this->get_custom_user_shipping_details(str_replace('cus-', '', $user_id));
}else{
	$user_data = $this->get_user_shipping_details($user_id);
}
$product_assigned = $this->get_order_user_product_assigned($_REQUEST['order_id'], $user_id);
?>
<table class="wp-list-table widefat fixed striped pages">
	<tbody>
		<?php if (is_object($product_assigned)): ?>
		<tr>
			<td width="130"><?php echo __('Product Assinged', 'searchfriend');?></td>
			<td><?php echo '<a target="_blank" href="'.get_site_url().'/wp-admin/post.php?post='.$product_assigned->product_id.'&action=edit">ID:'.$product_assigned->product_id.'</a>, <b>Quantity: '.$product_assigned->quantity.'</b>';?></td>
		</tr>
		<?php endif;?>
		<tr>
			<td width="130"><?php echo __('First Name', 'searchfriend');?></td>
			<td><?php echo $user_data['shipping_first_name'];?></td>
		</tr>
		<tr>
			<td width="130"><?php echo __('Last Name', 'searchfriend');?></td>
			<td><?php echo $user_data['shipping_last_name'];?></td>
		</tr>
		<tr>
			<td width="130"><?php echo __('Company Name', 'searchfriend');?></td>
			<td><?php echo $user_data['shipping_company'];?></td>
		</tr>
		<tr>
			<td width="130"><?php echo __('Address', 'searchfriend');?></td>
			<td><?php echo $user_data['shipping_address_1'];?></td>
		</tr>
		<tr>
			<td width="130"><?php echo __('City', 'searchfriend');?></td>
			<td><?php echo $user_data['shipping_city'];?></td>
		</tr>
		<tr>
			<td width="130"><?php echo __('Postcode', 'searchfriend');?></td>
			<td><?php echo $user_data['shipping_postcode'];?></td>
		</tr>
		<tr>
			<td width="130"><?php echo __('Country', 'searchfriend');?></td>
			<td><?php echo $user_data['shipping_country'];?></td>
		</tr>
		<tr>
			<td width="130"><?php echo __('Email', 'searchfriend');?></td>
			<td><?php echo $user_data['shipping_email'];?></td>
		</tr>
		<?php if (is_object($product_assigned)):?>
		<?php $activity_logs = $this->get_user_order_shipping_details($_REQUEST['order_id'], $user_id);?>
		<tr>
			<td width="130"><?php echo __('Event', 'searchfriend');?></td>
			<td>
				<?php $events = $this->get_event_logs();?>
				<select name="event_log" id="event_log">
					<?php foreach ($events as $key => $event):?>
						<?php if ($activity_logs->event == $key):?>
						<option value="<?php echo $key;?>" selected="selected"><?php echo $event;?></option>
						<?php else:?>
						<option value="<?php echo $key;?>"><?php echo $event;?></option>
						<?php endif;?>
					<?php endforeach;?>
				</select>
			</td>
		</tr>
		<tr>
			<td width="130"><?php echo __('Status', 'searchfriend');?></td>
			<td>
				<?php $status = $this->get_status_logs();?>
				<select name="status_log" id="status_log">
					<?php foreach ($status as $key => $s):?>
						<?php if ($activity_logs->status == $key):?>
						<option value="<?php echo $key;?>" selected="selected"><?php echo $s;?></option>
						<?php else:?>
						<option value="<?php echo $key;?>"><?php echo $s;?></option>
						<?php endif;?>
					<?php endforeach;?>
				</select>
			</td>
		</tr>
		<input type="hidden" id="key_save" value="<?php echo $_REQUEST['order_id'].':'.$user_id;?>"/>
		<?php endif;?>
	</tbody>
</table>