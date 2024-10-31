<?php 
$user_details  = $this->get_user_data($_REQUEST['user_id']);
$fiends        = $this->get_user_friends($_REQUEST['user_id']);
$custom_fiends = $this->get_user_custom_friends($_REQUEST['user_id']);
$this->add_custom_style("
	.header-item{
		height: 100px;
		border: 1px solid #CCC;
		text-align: center;
		font-size: 25px;
		font-weight: both;
		width: 33%;
		float: left;
		padding-top: 70px;
		background: #69A5D1;
		color: #FFF;
	}
	ul.user-listing{
		margin: 0px;
		padding: 0px;
		list-style: none;
		font-size: 12px;
	}
	ul.user-listing li{
		float: left;
		width: auto;
		-moz-border-radius-topleft: 6px;
		-webkit-box-shadow: 0px 0px 3px -1px #BF7130;
		-moz-box-shadow:    0px 0px 3px -1px #BF7130;
		box-shadow:         0px 0px 3px -1px #BF7130;
		-webkit-border-radius: 6px;
		-moz-border-radius: 6px;
		border-radius: 6px;
		border: 1px solid #ffb273;
		padding: 5px;
		margin-right: 10px;
		margin-top: 5px;
	}
	div.friend-listing{
		padding-top: 30px;
	}
	div.div-scroll{
		height: 260px;
		overflow: scroll;
		overflow-x: hidden;
	}
")->burn_media();
?>
<div class="col-md-12">
	<div class="col-md-4 header-item">
		<center><?php echo $user_details->display_name;?></center>		
	</div>
	<div class="col-md-4 header-item">
		<center><?php echo $user_details->user_email;?></center>
	</div>
	<div class="col-md-4 header-item" style="font-size: 16px;background:#FFFF99;color: #000;">
		<center><?php echo $user_details->user_registered;?></center>
	</div>
</div>
<div class="clear"></div>
<div class="col-md-12 friend-listing">
	<div>
		<?php echo __('Friends', 'searchfriend');?>
		<hr style="margin-top: -3px;color: #000;" />
	</div>
	<div class="div-scroll">
		<ul class="user-listing">
		<?php foreach ($fiends as $friend):?>
		<?php $user_data = $this->get_user_data($friend);?>
		<?php if(is_object($user_data)):?>
			<li>				
				<div class="btn-user-select">
					<?php echo $user_data->display_name;?>
					<i class="icon-show" style="float: right;" title="<?php echo __('Shipping Details', 'searchfriend');?>" onClick="javascript:userlistShowUserDetails('<?php echo $friend;?>');">
						<img src="<?php echo searchfriend_url;?>/datas/assets/images/show.png"/>
					</i>
				</div>
			</li>
		<?php endif;?>
		<?php endforeach;;?>
		<?php foreach ($custom_fiends as $friend):?>
		<?php $user_data = $this->get_custom_user_data($friend);?>
		<?php if(is_object($user_data)):?>
			<li>				
				<div class="btn-user-select">
					<?php echo $user_data->first_name.' '.$user_data->last_name;?>
					<i class="icon-show" style="float: right;" title="<?php echo __('Shipping Details', 'searchfriend');?>" onClick="javascript:userlistShowUserDetails('cus-<?php echo $friend;?>');">
						<img src="<?php echo searchfriend_url;?>/datas/assets/images/show.png"/>
					</i>
				</div>
			</li>
		<?php endif;?>
		<?php endforeach;;?>
		</ul>
	</div>
	<div class="clear"></div>
</div>