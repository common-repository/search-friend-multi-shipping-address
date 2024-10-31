<?php
 if (!$this->is_ajax()){
 $this->add_custom_javascript("
 	var user_dialog, user_dialog_details;
	function showFriendList(user_id)
	{
		createScreenLoading();
		jQuery.get(baseUrl+'?searchfriend_task=manage_friendlist', {user_id: user_id, mod: 'rawmode'}, function(data){
			jQuery('#data-user-details').html(data);
			user_dialog.dialog(\"open\");
			clearScreenLoading();
		});
	}
	function userlistShowUserDetails(user_id)
	{
		createScreenLoading();
		jQuery.get(baseUrl+'?searchfriend_task=manage_showuserdetails', {user_id: user_id, mod: 'rawmode'}, function(data){
			jQuery('#data-friend-dialog-details').html(data);
			user_dialog_details.dialog(\"open\");
			clearScreenLoading();
		});
	}
	jQuery(document).ready(function(){
		var dialog_form = jQuery('<div />');
		dialog_form.attr('id', 'user-details');
		dialog_form.attr('title', '".__('User Infomations', 'searchfriend')."');
		var dialog_form_data = jQuery('<div />');
		dialog_form_data.attr('id', 'data-user-details');
		dialog_form.append(dialog_form_data);
		jQuery('body').append(dialog_form);
		user_dialog = jQuery('#user-details').dialog({
	      	autoOpen: false,
	      	height: 600,
	      	width: 800,
	      	modal: true,
	      	draggable: true,
	      	buttons: {
	        	Cancel: function() {
	          		user_dialog.dialog(\"close\");
	        	}
	      	},
	      	open: function(event, ui) {
			},
	      	close: function() {
	      		
	      	}
	    });
	    var friend_dialog_form = jQuery('<div />');
		friend_dialog_form.attr('id', 'friend-dialog-details');
		friend_dialog_form.attr('title', '".__('Friend Details', 'searchfriend')."');
		var friend_dialog_form_data = jQuery('<div />');
		friend_dialog_form_data.attr('id', 'data-friend-dialog-details');
		friend_dialog_form.append(friend_dialog_form_data);
		jQuery('body').append(friend_dialog_form);
		user_dialog_details = jQuery('#friend-dialog-details').dialog({
	      	autoOpen: false,
	      	height: 450,
	      	width: 400,
	      	modal: true,
	      	draggable: true,
	      	buttons: {
	        	Cancel: function() {
	          		user_dialog_details.dialog(\"close\");
	        	}
	      	},
	      	open: function(event, ui) {
			},
	      	close: function() {
	      	}
	    });
	    jQuery('.table-user-list').DataTable({
            'searching'   : true,
            'lengthChange': false,
            \"order\"       : [[ 1, \"desc\" ]]
        });
	});
", 'show-friend-ist')->burn_media();
} 
$this->add_custom_style("
	.btn-friend-list{
		cursor: pointer;
	}
	button.btn-user-select{
		text-decoration: none;
		cursor: pointer;
		background: none;
		border: none;
	}
")->burn_media();
?>
<table class="wp-list-table widefat fixed striped pages table-user-list">
	<thead>
		<tr>
			<th width="45%"><?php echo __('User', 'searchfriend');?></th>
			<th width="45%"><?php echo __('User Email', 'searchfriend');?></th>
			<th width="10%"><?php echo __('Action', 'searchfriend');?></th>
		</tr>
	</thead>
	<tbody>
	<?php $users = $this->get_users();?>
	<?php if (count($users)):?>
	<?php foreach ($users as $i => $user):?>
	<tr  class="row<?php echo $i % 2; ?>">
		<td><?php echo $user->display_name,'('.$user->user_nicename.')';?></td>
		<td><?php echo $user->user_email;?></td>
		<td>
			<i class="icon-show" title="<?php echo __('Friend List', 'searchfriend');?>" onClick="javascript:showFriendList(<?php echo $user->ID;?>);">
				<img src="<?php echo searchfriend_url;?>/datas/assets/images/show.png"/>
			</i>
		</td>
	</tr>
	<?php endforeach;?>
	<?php endif;?>
	</tbody>
</table>
