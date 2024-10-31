<?php
function woocommerce_searchfriend_shipping_init(){
	if(!class_exists('WC_Searchfriend_Shipping_Method')){
		class WC_Searchfriend_Shipping_Method extends WC_Shipping_Method{
			/**
			 * Constructor for your shipping class
			 *
			 * @access public
			 * @return void
			 */
			private $_searchfriend = null;
			public function __construct(){
				$this->id                 = 'searchfriend_shipping_address';
				$this->method_title       = __( 'Searchfriend Multi-Shipping Address' );
				$this->method_description = __( 'Of an multi-shipping address. User understand make more address for any your friends, it will notice to those users choose by email...' ); // 
				$this->enabled            = "yes"; // This can be added as an setting but for this example its forced enabled
				$this->init();
			}
	
			/**
			 * Init your settings
			 *
			 * @access public
			 * @return void
			 */
			function init(){
				global $wpdb;
				// Load the settings API
				$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
				$this->init_settings(); // This is part of the settings API. Loads settings you previously init.
				// Define user set variables
				$this->enabled = $this->get_option('enabled');
				require_once(searchfriend_path.'/classes/core_controller.php');
				$this->_searchfriend = Core_Controller::getInstance('global', $wpdb);
				$this->get_instance_form_fields();
				// Save settings in admin if you have any defined
				add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
			}
			/**
			 * Process and redirect if disabled.
			 */
			public function process_admin_options(){
				parent::process_admin_options();
		
				if ('no' === $this->settings['enabled']){
					wp_redirect( admin_url( 'admin.php?page=wc-settings&tab=shipping&section=options' ) );
					exit;
				}
			}
			/**
			 * Return the name of the option in the WP DB.
			 * @since 2.6.0
			 * @return string
			 */
			public function get_option_key(){
				return $this->plugin_id . 'searchfriend_shipping' . '_settings';
			}
			/**
			 * Init form fields.
			 */
			public function init_form_fields(){
				$this->form_fields = array(
					'enabled' => array(
						'title' 		=> __( 'Enable/Disable', 'woocommerce' ),
						'type' 			=> 'checkbox',
						'label' 		=> __( 'Once disabled, this legacy method will no longer be available.', 'woocommerce' ),
						'default' 		=> 'no',
					)
				);
			}
			/**
			 * calculate_shipping function.
			 *
			 * @access public
			 * @param mixed $package
			 * @return void
			 */
			public function calculate_shipping($package){
				// This is where you'll add your rates
				$args = array(
					'id' 	  => $this->id,
					'label'   => $this->method_title,
					'cost' 	  => 0,
					'taxes'   => false,
					'package' => $package,
				);
				$this->add_rate($args);
			}
			/**
			 * is_available function.
			 * @param array $package
			 * @return bool
			 */
			public function is_available($package){
				if ( 'no' == $this->enabled ) {
					return false;
				}
				if (count(WC()->cart)){
					$_scripts = Array();
					$_htmls   = Array();
					$_scripts[] = "var cart_products = new Array();";					
					foreach (WC()->cart->get_cart() as $cart){
						$_product   = $cart['data']->post;
						$_htmls[]   = '<option value="'.$cart['product_id'].'">'.$_product->post_title.'</option>'; 
						$_scripts[] = "cart_products[".$cart['product_id']."] = {'product_id':'".$cart['product_id']."', 'product_name':'".$_product->post_title."','quantity':".$cart['quantity'].", 'used':".$cart['quantity']."};";
					}
					$this->_searchfriend->add_custom_javascript(implode(PHP_EOL, $_scripts), 'cart_products');
					$this->_searchfriend->add_custom_javascript("
						function make_assigment_html()
						{
							jQuery('.product-assignment').each(function(){
								jQuery(this).html('<select class=\"product-assign\" style=\"width: 60px;float: left;\">".implode('', $_htmls)."</select><input type=\"text\" class=\"quantity-assigned\" onKeyup=\"javascript:checkValidAssign(this);\" style=\"width: 30px;height: 21px;float: left;\" value=\"\"/>');
							});
						}						
						function addNewAssign(_this)
						{
							var index = jQuery('.product-assignment').length; 
							var current_user = jQuery(_this).prev().attr('data-user-id');
							jQuery(_this).before('<div class=\"product-assignment\" data-user-id=\"'+current_user+'\" index-id=\"'+index+'\"><select class=\"product-assign\" style=\"width: 60px;float: left;\">".implode('', $_htmls)."</select><input type=\"text\" class=\"quantity-assigned\" onKeyup=\"javascript:checkValidAssign(this);\" style=\"width: 30px;height: 21px;float: left;\" value=\"\"/></div>');
						}
						function load_assigments()
						{
							createScreenLoading();
							jQuery.get(baseUrl+'?searchfriend_task=get_dropdownQuantity', {mod: 'rawmode'}, function(data){
								data = jQuery.parseJSON(data);
								for(item in data){
									var element = jQuery('.product-assignment[index-id=\"'+item+'\"]')
									if (element.length > 0){
										if (cart_products[data[item].product_id] != undefined){
											element.find('select.product-assign').val(data[item].product_id);
											element.find('input.quantity-assigned').val(data[item].quantity);
										}
									}
								}
								clearScreenLoading();
							});
						}
						function create_product_dropdown()
						{
							make_assigment_html();
							load_assigments();
						}						
					", "make-html");
					$this->_searchfriend->add_custom_javascript("
						function IsNumeric(input)
						{
						    return (input - 0) == input && (''+input).trim().length > 0;
						}
						function checkValidAssign(el)
						{
							if (!IsNumeric(jQuery(el).val())){
								jQuery(el).val('');
								return;
							}
							var total = 0;
							var product_id = jQuery(el).prev('select').val();
							jQuery('.product-assign').each(function(){
								if (jQuery(this).val() == product_id){
									var selected = parseInt(jQuery(this).next('input').val());
									if (selected > 0){
										total = total + selected;
									}
								}
							});
							if (total > cart_products[product_id].quantity){
								alert('".__('Your select out quantity of Cart.')."');
								jQuery(el).val('');
								return;
							}
							createScreenLoading();
							var product_assign = new Array(), count_data = 0;
							jQuery('.product-assignment').each(function(){
								if (parseInt(jQuery(this).find('input.quantity-assigned').val()) > 0){
									product_assign[count_data++] = {'user_id':jQuery(this).attr('data-user-id'), 'product_id':jQuery(this).find('select.product-assign').val(), 'quantity':parseInt(jQuery(this).find('input.quantity-assigned').val())};
								}
							});
							jQuery.get(baseUrl+'?searchfriend_task=save_quantityAssigned', {product_assign: product_assign, mod: 'rawmode'}, function(data){
								clearScreenLoading();
							});
						}
					", "check-quantity-assign");
				}
				return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', true, $package );
			}
			/**
			 * Get setting form fields for instances of this shipping method within zones.
			 *
			 * @return array
			 */
			public function get_instance_form_fields(){
				if(!is_admin()){
					$this->_searchfriend->add_custom_javascript("
						var succgess_type = '';
						function addAddress()
						{
							if (jQuery('#friend_id').attr('data-id') != ''){
								createScreenLoading();
								jQuery.get(baseUrl+'?searchfriend_task=addAddress', {friend_id: jQuery('#friend_id').attr('data-id'), mod: 'rawmode'}, function(data){
									data = jQuery.parseJSON(data);
									if (data.status == 'success'){
										jQuery('#data-address-list').html(data.html);
										jQuery('#friend_id').friend_suggestion();
									}
									clearScreenLoading();
								});
							}
						}
						var dialog_custom_friend; 
						function addNewCustomFriend()
						{
							createScreenLoading();
							jQuery.get(baseUrl+'?searchfriend_task=add_custom_friend', {mod: 'rawmode'}, function(data){
								jQuery('#data-dialog-custom-friend').html(data);
								dialog_custom_friend.dialog(\"open\");
								clearScreenLoading();
							});
						}
						function removeAddress(friend_id)
						{
							createScreenLoading();
							jQuery.get(baseUrl+'?searchfriend_task=removeAddress', {friend_id: friend_id, mod: 'rawmode'}, function(data){
								data = jQuery.parseJSON(data);
								if (data.status == 'success'){
									jQuery('#data-address-list').html(data.html);
									jQuery('#friend_id').friend_suggestion();
								}
								clearScreenLoading();
							});
						}
						function loadFriendList()
						{
							createScreenLoading();
							jQuery.get(baseUrl+'?searchfriend_task=manage-address', {mod: 'rawmode'}, function(data){
								data = jQuery.parseJSON(data);
								if (data.status == 'success'){
									jQuery('#data-address-list').html(data.html);
									jQuery('#friend_id').friend_suggestion();
								}
								clearScreenLoading();
							});
						}
						function showShippingDetails(user_id)
						{
							createScreenLoading();
							jQuery.get(baseUrl+'?searchfriend_task=showorder_shipping', {user_id: user_id, mod: 'rawmode'}, function(data){
								jQuery('#data-shipping-order-details').html(data);
								shipping_dialog.dialog(\"open\");
								clearScreenLoading();
							});
						}
						function isEmailAddress(str) {
						   var pattern =/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
						   return pattern.test(str);  // returns a boolean 
						}
						var dialog, shipping_dialog_form;
						jQuery(document).ready(function(){
							var dialog_form = jQuery('<div />');
							dialog_form.attr('id', 'manage-address');
							dialog_form.attr('title', '".__('Manage Address', 'searchfriend')."');
							var dialog_form_data = jQuery('<div />');
							dialog_form_data.attr('id', 'data-address-list');
							dialog_form.append(dialog_form_data);
							jQuery('body').append(dialog_form);
							dialog = jQuery('#manage-address').dialog({
						      	autoOpen: false,
						      	height: 550,
						      	width: 800,
						      	modal: true,
						      	draggable: true,
						      	buttons: {
						        	Cancel: function() {
						          		dialog.dialog(\"close\");
						        	}
						      	},
						      	open: function(event, ui) {
						      		loadFriendList();
								},
						      	close: function() {
						      	}
						    });
						    var shipping_dialog_form = jQuery('<div />');
							shipping_dialog_form.attr('id', 'order-shipping-details');
							shipping_dialog_form.attr('title', '".__('User Shipping Details', 'searchfriend')."');
							var shipping_dialog_form_data = jQuery('<div />');
							shipping_dialog_form_data.attr('id', 'data-shipping-order-details');
							shipping_dialog_form.append(shipping_dialog_form_data);
							jQuery('body').append(shipping_dialog_form);
							shipping_dialog = jQuery('#order-shipping-details').dialog({
						      	autoOpen: false,
						      	height: 400,
						      	width: 400,
						      	modal: true,
						      	draggable: true,
						      	buttons: {
						        	Cancel: function() {
						          		shipping_dialog.dialog(\"close\");
						        	}
						      	},
						      	open: function(event, ui) {
								},
						      	close: function() {
						      	}
						    });
						    var dialog_addcustom_form = jQuery('<div />');
							dialog_addcustom_form.attr('id', 'dialog-custom-friend');
							dialog_addcustom_form.attr('title', '".__('Add Custom Friend', 'searchfriend')."');
							var dialog_addcustom_form_data = jQuery('<div />');
							dialog_addcustom_form_data.attr('id', 'data-dialog-custom-friend');
							dialog_addcustom_form.append(dialog_addcustom_form_data);
							jQuery('body').append(dialog_addcustom_form);
							dialog_custom_friend = jQuery('#dialog-custom-friend').dialog({
						      	autoOpen: false,
						      	height: 550,
						      	width: 650,
						      	modal: true,
						      	draggable: true,
						      	buttons: {
						      		'Save': function(){
						      			if (jQuery('#first_name').val() == ''){
						      				alert('Please input first name');
						      				jQuery('#first_name').focus();
						      				return;
						      			}
						      			if (jQuery('#last_name').val() == ''){
						      				alert('Please input last name');
						      				jQuery('#last_name').focus();
						      				return;
						      			}
						      			if (jQuery('#company_name').val() == ''){
						      				alert('Please input company');
						      				jQuery('#company_name').focus();
						      				return;
						      			}
						      			if (jQuery('#country').val() == ''){
						      				alert('Please input country');
						      				jQuery('#country').focus();
						      				return;
						      			}
						      			if (jQuery('#street_address').val() == ''){
						      				alert('Please input street address');
						      				jQuery('#street_address').focus();
						      				return;
						      			}
						      			if (jQuery('#postcode_zip').val() == ''){
						      				alert('Please input postcode/zip');
						      				jQuery('#postcode_zip').focus();
						      				return;
						      			}
						      			if (jQuery('#town_city').val() == ''){
						      				alert('Please input town/city');
						      				jQuery('#town_city').focus();
						      				return;
						      			}
						      			if (jQuery('#phone').val() == ''){
						      				alert('Please input phone');
						      				jQuery('#phone').focus();
						      				return;
						      			}
						      			if (jQuery('#email_address').val() == '' || !isEmailAddress(jQuery('#email_address').val())){
						      				alert('Please input correct email address');
						      				jQuery('#email_address').focus();
						      				return;
						      			}
						      			createScreenLoading();
										jQuery.get(baseUrl+'?searchfriend_task=friendlist_save_add_custom_friend', 
										{
											mod: 'rawmode',
											load_type: 'address-list',
											first_name: jQuery('#first_name').val(),
											last_name: jQuery('#last_name').val(),
											company_name: jQuery('#company_name').val(),
											country: jQuery('#country').val(),
											street_address: jQuery('#street_address').val(),
											postcode_zip: jQuery('#postcode_zip').val(),
											town_city: jQuery('#town_city').val(),
											phone: jQuery('#phone').val(),
											email_address: jQuery('#email_address').val()
										}, 
										function(data){
											data = jQuery.parseJSON(data);
											if (data.status == 'success'){
												jQuery('#data-address-list').html(data.html);
												jQuery('#friend_id').friend_suggestion();
											}
											dialog_custom_friend.dialog(\"close\");
											clearScreenLoading();
										});
						      		},
						        	Cancel: function() {
						          		dialog_custom_friend.dialog(\"close\");
						        	}
						      	},
						      	open: function(event, ui) {
								},
						      	close: function() {
						      	}
						      });
							  jQuery(document.body).on('updated_checkout', function(){							
									jQuery('input[value=\"searchfriend_shipping_address\"]').parent().append('<button type=\"button\" id=\"searchfriend-add-address\" style=\"display: none;\">".__('Manage Address', 'searchfriend')."</button>');
								  	if (jQuery('input[value=\"searchfriend_shipping_address\"]').is(':checked')){
										jQuery('#searchfriend-add-address').css('display', 'inline-block');
									}else{
										jQuery('#searchfriend-add-address').css('display', 'none');
									}
									jQuery('#searchfriend-add-address').on(\"click\", function(){
								      dialog.dialog(\"open\");
								    });
								    loadFriendList();
								});
						});
					", 'createbutton')->add_custom_style("
					 	table.user-shipping-detail tr td{
					 		height: 30px;
					 		line-height: 30px;
					 	}
				    ", "user-shipping-detail");			
				}
			}
		}
		function add_searchfriend_shipping_method($methods){
			$methods['searchfriend_shipping_method'] = 'WC_Searchfriend_Shipping_Method';
			return $methods;
		}
		add_filter( 'woocommerce_shipping_methods', 'add_searchfriend_shipping_method' );
	}
}
add_action('plugins_loaded', 'woocommerce_searchfriend_shipping_init', 100);