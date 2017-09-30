var prefix = '#billing_';
var zip = 0;
var errors = new Array();
var list = '.shops-list ul';
var timer = '';
var shipping_element = ".add_info_wga";
var timer = '';
var limit = 5;
jQuery(document).ready(function($){
	timer = setInterval("checkMethod()", 2000);
	
	jQuery("input[name='shipping_type']").change(function(){
		var active_tab = jQuery(this).val();
		if(active_tab == 'shop_to_gls_shop'){
			jQuery(".gls_tab").show();
			if(jQuery("#ship-to-different-address-checkbox").is(":checked")){
				jQuery("#ship-to-different-address-checkbox").click();
			}
		} 
		if(active_tab == 'shop_to_company_address'){
				jQuery(".gls_tab").hide();
				if(!jQuery("#ship-to-different-address-checkbox").is(":checked")){
					//jQuery("#ship-to-different-address-checkbox").click();
				}
		}
	});
});
function checkMethod(){
	clearInterval(timer);
	if(jQuery("#shipping_method_wga_shipping_method").is(":checked")){
		showShippingDiv();
	}
}
function searchShop(){	
		if(jQuery("#shiptobilling-checkbox").attr("checked") != 'checked') prefix = '#shipping_';
		else prefix = '#billing_';
		zip = jQuery(prefix+"postcode").val();
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
	if(element_id == 'shipping_method_wga_shipping_method'){
		if(jQuery(el).is(":checked")){
			timer = setInterval("showShippingDiv()",2000);		
		} else {
			jQuery(shipping_element).slideDown("hide");
		}
	}
}