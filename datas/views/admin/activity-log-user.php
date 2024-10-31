<?php
$user_id = $_REQUEST['user_id'];
if (strpos($user_id, 'cus') !== false){
	$user_data = $this->get_custom_user_shipping_details(str_replace('cus-', '', $user_id));
}else{
	$user_data = $this->get_user_shipping_details($user_id);
}
?>
<table class="wp-list-table widefat fixed striped pages">
	<tbody>
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
	</tbody>
</table>