<?php 
if (!$this->is_ajax()){
 $this->add_custom_javascript("
	function removeAddress(friend_id)
	{
		createScreenLoading();
		jQuery.get(baseUrl+'?searchfriend_task=admin_removeAddress', {friend_id: friend_id, mod: 'rawmode'}, function(data){
			jQuery('#data-custom-friend-list').html(data);
			clearScreenLoading();
		});
	}
	var custom_friend_dialog 
	function showAddressDetails(user_id)
	{
		createScreenLoading();
		jQuery.get(baseUrl+'?searchfriend_task=admin_addressDetails', {user_id: user_id, mod: 'rawmode'}, function(data){
			jQuery('#data-address-details').html(data);
			custom_friend_dialog.dialog(\"open\");
			clearScreenLoading();
		});
	}
	jQuery(document).ready(function(){
	    var custom_friend_dialog_form = jQuery('<div />');
		custom_friend_dialog_form.attr('id', 'address-details');
		custom_friend_dialog_form.attr('title', '".__('Address details', 'searchfriend')."');
		var custom_friend_dialog_form_data = jQuery('<div />');
		custom_friend_dialog_form_data.attr('id', 'data-address-details');
		custom_friend_dialog_form.append(custom_friend_dialog_form_data);
		jQuery('body').append(custom_friend_dialog_form);
		custom_friend_dialog = jQuery('#address-details').dialog({
	      	autoOpen: false,
	      	height: 450,
	      	width: 450,
	      	modal: true,
	      	draggable: true,
	      	buttons: {
	        	Cancel: function() {
	          		custom_friend_dialog.dialog(\"close\");
	        	}
	      	},
	      	open: function(event, ui) {
			},
	      	close: function() {
	      	}
	    });
	    jQuery('.table-custom-friend-list').DataTable({
            'searching'   : true,
            'lengthChange': false,
            \"order\"       : [[ 1, \"desc\" ]]
        });
	});
", "add-custom-friend")->burn_media();
}else{
	$this->add_custom_javascript("
		jQuery('.table-custom-friend-list').DataTable({
            'searching'   : true,
            'lengthChange': false,
            \"order\"       : [[ 1, \"desc\" ]]
        });
	")->burn_media();
}
?>
<?php if (!$this->is_ajax()):?>
<div id="data-custom-friend-list">
<?php endif;?>
<table class="wp-list-table widefat fixed striped pages table-custom-friend-list">
	<thead>
		<tr>
			<th><?php echo __('User', 'searchfriend');?></th>
			<th><?php echo __('User Email', 'searchfriend');?></th>
			<th><?php echo __('Action', 'searchfriend');?></th>
		</tr>
	</thead>
	<tbody>
	<?php $friends = $this->get_all_custom_friends();?>
	<?php if (count($friends)):?>
	<?php foreach ($friends as $user_data):?>
	<tr>
		<td><?php echo $user_data->first_name.' '.$user_data->last_name;?></td>
		<td><?php echo $user_data->email_address;?></td>
		<td>
			<i class="icon-show" title="<?php echo __('Shipping Details', 'searchfriend');?>" onClick="javascript:showAddressDetails(<?php echo $user_data->custom_id;?>);">
				<img src="<?php echo searchfriend_url;?>/datas/assets/images/show.png"/>
			</i>
			<i class="icon-remove" title="<?php echo __('Remove', 'searchfriend');?>" onClick="javascript:removeAddress(<?php echo $user_data->custom_id;?>);">
				<img src="<?php echo searchfriend_url;?>/datas/assets/images/remove-user.png"/>
			</i>
		</td>
	</tr>
	<?php endforeach;?>
	<?php endif;?>
	</tbody>
</table>
<?php if (!$this->is_ajax()):?>
</div>
<?php endif;?>
