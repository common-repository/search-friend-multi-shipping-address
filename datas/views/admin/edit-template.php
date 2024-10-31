<?php
?>
<table class="wp-list-table widefat fixed striped pages">
	<tbody>
	<?php $template = $this->get_template_data($_REQUEST['template_id']);?>
	<?php if (is_object($template)):?>
	<tr>
		<td width="70"><?php echo __('Subject', 'searchfriend');?></td>
		<td><input type="text" name="subject" id="template_subject" value="<?php echo $template->subject;?>"/></td>
	</tr>
	<tr  class="row<?php echo $i % 2; ?>">
		<td width="70"><?php echo __('Message', 'searchfriend');?></td>
		<td><textarea type="text" cols="40" rows="8" name="message" id="template_message"><?php echo $template->message;?></textarea></td>
	</tr>
	<input type="hidden" name="id" id="template_id" value="<?php echo $template->id;?>"/></td>
	<?php endif;?>
	</tbody>
</table>