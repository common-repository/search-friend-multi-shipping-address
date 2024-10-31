<?php
$user_data = $this->get_custom_user_data($_REQUEST['user_id']);
?>
<table class="wp-list-table widefat fixed striped pages">
	<tbody>
		<tr>
			<td width="130"><?php echo __('First Name', 'searchfriend');?></td>
			<td><?php echo $user_data->first_name;?></td>
		</tr>
		<tr>
			<td width="130"><?php echo __('Last Name', 'searchfriend');?></td>
			<td><?php echo $user_data->last_name;?></td>
		</tr>
		<tr>
			<td width="130"><?php echo __('Company Name', 'searchfriend');?></td>
			<td><?php echo $user_data->company_name;?></td>
		</tr>
		<tr>
			<td width="130"><?php echo __('Country', 'searchfriend');?></td>
			<td><?php echo $user_data->country;?></td>
		</tr>
		<tr>
			<td width="130"><?php echo __('Address', 'searchfriend');?></td>
			<td><?php echo $user_data->street_address;?></td>
		</tr>
		<tr>
			<td width="130"><?php echo __('Postcode/Zip', 'searchfriend');?></td>
			<td><?php echo $user_data->postcode_zip;?></td>
		</tr>
		<tr>
			<td width="130"><?php echo __('Town/City', 'searchfriend');?></td>
			<td><?php echo $user_data->town_city;?></td>
		</tr>
		<tr>
			<td width="130"><?php echo __('Phone', 'searchfriend');?></td>
			<td><?php echo $user_data->phone;?></td>
		</tr>

		<tr>
			<td width="130"><?php echo __('Email address', 'searchfriend');?></td>
			<td><?php echo $user_data->email_address;?></td>
		</tr>
	</tbody>
</table>