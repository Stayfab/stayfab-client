var prefix = '#billing_';
var zip = 0;
var errors = new Array();
var list = '.shops-list ul';
var timer = '';
var shipping_element = ".add_info_wga";
var timer = '';
var limit = 5;
jQuery(document).ready(function($){
	
	setInterval(function(){
		if(jQuery("select.country_select").length > 0){
//			jQuery("select.country_select").chosen();
		}
		
	} , 100);
	
	setInterval(function(){
		if(typeof wc_checkout_params != 'undefined'){
			if(jQuery("#shipping_method_0_wga_shipping_method").length > 0){
				if(	jQuery("#shipping_method_0_wga_shipping_method").is(":checked") && jQuery(".add_info_wga").length <= 0){
					triggerUpdate();
				}
			}
		}
	}, 2000);
	
	jQuery("body").on('change' , "input[name='shipping_type']" , function(){
		var active_tab = jQuery(this).val();
		if(active_tab == 'shop_to_gls_shop'){
			createCookie("wga_shop_method", "shop_to_gls_shop" , 1);
			jQuery("body").trigger("update_checkout");
			jQuery(".gls_tab").show();
			jQuery(".company_tab").hide();
		} 
		if(active_tab == 'shop_to_company_address'){
			createCookie("wga_shop_method", "shop_to_company_address" , 1);
			//jQuery("#ship-to-different-address-checkbox").click();
			jQuery("body").trigger("update_checkout")
			jQuery(".gls_tab").hide();
			if(jQuery("#ship-to-different-address-checkbox").is(":checked")){
					//jQuery("#ship-to-different-address-checkbox").click();
				}
			jQuery(".company_tab").show();
		}
	});
	
	if(jQuery("#ship-to-different-address-checkbox").is(":checked")){
			jQuery("#wga_shop_postcode").val(jQuery("#shipping_postcode").val());
		} else {
		jQuery("#wga_shop_postcode").val(jQuery("#billing_postcode").val());
	}
	
	jQuery("#shipping_postcode, #billing_postcode").on('blur', function(){
		jQuery("#wga_shop_postcode").val(jQuery(this).val());
	});
	
});
function checkMethod(){
	clearInterval(timer);
	if(jQuery("#shipping_method_0_wga_shipping_method").is(":checked")){
		showShippingDiv();
	}
}
function searchShop(){	
		if(jQuery("#shiptobilling-checkbox").is(":checked")) prefix = '#shipping_';
		else prefix = '#billing_';
		zip = jQuery("#wga_shop_postcode").val();
		limit = jQuery("#number_shops").val();
		errors = new Array();
		if(zip == '') errors.push('Please Enter Zip / Postcode.');
		if(errors.length > 0){
			jQuery(list).html('');
			for(var i=0; i < errors.length; i++){
				jQuery("<li>"+errors[i]+"</li>").appendTo(list);
			}
			return false;
		}
		show_loading();
		var data = {
			action: 'wga_get_shops_list',
			zipcode: zip,
			limit: limit
		};
		jQuery.post(admin_ajax.url , data, function(response){
			var shops = jQuery.parseJSON(response);
			jQuery(list).html('');
			for(var i=0; i < shops.length; i++){
				jQuery('<li><div class="wcol1"><input type="radio" name="shop" value="'+shops[i].Number+'" /></div><div class="wcol2"><strong>'+shops[i].CompanyName+'</strong><br  />'+shops[i].Streetname2+'<br />'+shops[i].Streetname+'<br />'+shops[i].ZipCode+', '+shops[i].CityName+'</div><div class="wcol3">'+shops[i].timing+'</div></li>').appendTo(list);
			}
		hide_loading();
		
		if(jQuery("#wga_shop_postcode").val() != ""){
				if(jQuery("#ship-to-different-address-checkbox").is(":checked")){
					jQuery("#wga_shop_postcode").val(jQuery("#shipping_postcode").val());
				} else {
					jQuery("#wga_shop_postcode").val(jQuery("#billing_postcode").val());
				}
		}
		});
		return false;
}
function show_loading(){
	jQuery(list).hide();
	jQuery("#loading-div").slideDown("fast");
}
function hide_loading(){
	jQuery(list).slideDown("slow");
	jQuery("#loading-div").slideUp("fast");
}
function showShippingDiv(){
	clearInterval(timer);
	jQuery(shipping_element).slideDown("slow");	
}
function changeMethod(el){
	var element_id = jQuery(el).attr("id");
	if(element_id == 'shipping_method_0_wga_shipping_method'){
		if(jQuery(el).is(":checked")){
			timer = setInterval("showShippingDiv()",2000);		
		} else {
			jQuery(shipping_element).hide();
		}
	}
}
function createCookie(name,value,days) {
if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
  }
  else var expires = "";
  document.cookie = name+"="+value+expires;
}

function triggerUpdate(){
	jQuery("body").trigger("update_checkout");	
}