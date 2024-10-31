<h3><?php echo __('Search Friend(Multi-shipping address)', 'searchfriend');?></h3>
<?php
$this->add_custom_javascript("
	(function($){
		  jQuery(document).ready(function(){
			  jQuery(\"#searchfriend-config-tabs\").tabs();
		  });				
	})(jQuery);
", 'jquery-ui-tabs')->burn_media();
?>
<div id="searchfriend-config-tabs">
  <ul>
    <li><a href="#tabs-1"><?php echo __('Dashboard', 'searchfriend');?></a></li>
    <li><a href="#tabs-2"><?php echo __('User List', 'searchfriend');?></a></li>
    <li><a href="#tabs-3"><?php echo __('Address List', 'searchfriend');?></a></li>
    <li><a href="#tabs-4"><?php echo __('Order List', 'searchfriend');?></a></li>
    <li><a href="#tabs-5"><?php echo __('Activity logs', 'searchfriend');?></a></li>
    <li><a href="#tabs-6"><?php echo __('Email Templates', 'searchfriend');?></a></li>
    <li><a href="#tabs-7"><?php echo __('Configures', 'searchfriend');?></a></li>   
  </ul>
  <div id="tabs-1">
    <center><h1><?php echo __('Show statistic functionality', 'searchfriend');?></h1></center>
  </div>
  <div id="tabs-2">
    <center><h1><?php $this->loadView('user-list');?></h1></center>
  </div>
  <div id="tabs-3">
    <center><h1><?php $this->loadView('custom-friend-list');?></h1></center>
  </div>
  <div id="tabs-4">
    <center><h1><?php $this->loadView('order-list');?></h1></center>
  </div>
  <div id="tabs-5">
  	<center><h1><?php $this->loadView('activity-logs');?></h1></center>    
  </div>
  <div id="tabs-6">
    <center><h1><?php $this->loadView('template-list');?></h1></center>
  </div>
  <div id="tabs-7">
    <center><h1><?php $this->loadView('config-field-list');?></h1></center>
  </div>
</div>