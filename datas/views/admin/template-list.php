<?php
 if (!$this->is_ajax()){
 $this->add_custom_javascript("
 	var template_dialog;
	function editTemplate(template_id)
	{
		createScreenLoading();
		jQuery.get(baseUrl+'?searchfriend_task=manage_edittemplate', {template_id: template_id, mod: 'rawmode'}, function(data){
			jQuery('#data-template-details').html(data);
			template_dialog.dialog(\"open\");
			clearScreenLoading();
		});
	}
	jQuery(document).ready(function(){
		var dialog_form = jQuery('<div />');
		dialog_form.attr('id', 'template-details');
		dialog_form.attr('title', '".__('Edit template', 'searchfriend')."');
		var dialog_form_data = jQuery('<div />');
		dialog_form_data.attr('id', 'data-template-details');
		dialog_form.append(dialog_form_data);
		jQuery('body').append(dialog_form);
		template_dialog = jQuery('#template-details').dialog({
	      	autoOpen: false,
	      	height: 350,
	      	width: 500,
	      	modal: true,
	      	draggable: true,
	      	buttons: {
	      		'save': function(){
	      			createScreenLoading();
					jQuery.get(baseUrl+'?searchfriend_task=manage_savetemplate', {template_id: jQuery('#template_id').val(), template_subject: jQuery('#template_subject').val(), template_message:jQuery('#template_message').val(), mod: 'rawmode'}, function(data){
						template_dialog.dialog(\"close\");
						clearScreenLoading();
					});
	      		},
	        	Cancel: function() {
	          		template_dialog.dialog(\"close\");
	        	}
	      	},
	      	open: function(event, ui) {
			},
	      	close: function() {
	      		
	      	}
	    });
	});
", 'show-friend-ist')->burn_media();
} 
?>
<table class="wp-list-table widefat fixed striped pages">
	<thead>
		<tr>
			<th width="60%"><?php echo __('Subject', 'searchfriend');?></th>
			<th width="30%"><?php echo __('Type', 'searchfriend');?></th>
			<th width="10%"><?php echo __('Action', 'searchfriend');?></th>
		</tr>
	</thead>
	<tbody>
	<?php $templates = $this->get_templates();?>
	<?php if (count($templates)):?>
	<?php foreach ($templates as $i => $template):?>
	<tr  class="row<?php echo $i % 2; ?>">
		<td><?php echo $template->subject;?></td>
		<td><?php echo $template->type;?></td>
		<td>
			<i class="icon-edit" style="float: right;" title="<?php echo __('Edit', 'searchfriend');?>" onClick="javascript:editTemplate(<?php echo $template->id;?>);">
				<img src="<?php echo searchfriend_url;?>/datas/assets/images/edit.png"/>
			</i>
		</td>
	</tr>
	<?php endforeach;?>
	<?php endif;?>
	</tbody>
</table>
