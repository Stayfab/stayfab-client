jQuery(document).ready(function() {
var d=jQuery('#user_review').val('');

jQuery(".user_reply").focusout(function(){
    jQuery(this).css("background-color", "hsl(0, 0%, 97%)");
});

jQuery(".user_reply").focus(function(){
    jQuery(this).css("background-color", "white");
});

jQuery(".rate-now span img").prop("disabled",true); 

});
function ratebyuid(uid,score){
	var data = {
		action:'upr-ratebyuid',
		uid:uid,
		score:score
		}
	jQuery.ajax({
			url: ajaxurl,
			data:data,
			type:'POST',
		});
	
}

function delete_review(uid) {
	var is_delete = confirm("Are you sure you want to delete this review?");
	if(is_delete == true) {
		var data = {
			action: 'upr-review_delete',
			sender_uid: uid,
			user_id: jQuery('#userpro-reviews').data('user-id'),
		}
		jQuery.ajax({
			url: ajaxurl,
			data: data,
			type: 'POST' ,
			success: function(res) {
				jQuery('#comment_box_'+uid).hide();
				
				
			}
		});
	}
}

/*edit review start*/
function edit_review(sender_id,receiver_id) {
	var review = jQuery('#review_'+sender_id).text();
	jQuery('#review_sent_'+sender_id).html('<textarea name="review" id="user_review" data-sender_userid="'+sender_id+'" data-reciever_userid="'+receiver_id+'">'+review+'</textarea>');
	jQuery('#user_review').keypress(function(e){
		if(e.which == 13){
			e.preventDefault();
			var sender_userid = jQuery('#user_review').data('sender_userid');
			var review = jQuery('#user_review').val();
			var data = {
						action:'upr-review_submit',
						reciever_userid:jQuery('#user_review').data('reciever_userid') ,
						sender_userid: sender_userid,
						review: review 
					}

			jQuery.ajax({
				url: ajaxurl,
				data:data,
				type:'POST',
				success:function(data) {
					jQuery('#user_review').hide();
					jQuery('#review_sent_'+sender_id).html('<i id="review_'+sender_userid+'">'+review+'</i>');
				},
			});
		}
	});
}

jQuery(function(){
	jQuery('#user_review').keypress(function(e){
		if(e.which == 13){
			e.preventDefault();
			var data = {
						action:'upr-review_submit',
						reciever_userid:jQuery('#user_review').data('reciever_userid') ,
						sender_userid:jQuery('#user_review').data('sender_userid') ,
						review:jQuery('#user_review').val() ,  
					}

			jQuery.ajax({
				url: ajaxurl,
				data:data,
				type:'POST',
				success:function(data) {
					jQuery('#user_review').hide();
					jQuery('#review_msg').show();
				},
			});
		}
	});
});
/*edit review end*/

/* reply review start*/
jQuery(document).on("click",".reply_button",function() {
	var reply_giver_userid = jQuery(this).parents('.comment_box_t').find('.user_reply').data('reciever_userid');
	var reply_reciever_userid = jQuery(this).parents('.comment_box_t').find('.user_reply').data('sender_userid');
	var reply = jQuery(this).parents('.comment_box_t').find('.user_reply').val();

	if (jQuery(this).parents('.comment_box_t').find('.user_reply').val().trim().length == 0){
		jQuery(this).parents('.comment_box_t').find('.blank_reply_msg').css('display','block');
	}
	else{
		var data = {
				action:'upr-reply_submit',
				reply_reciever_userid:reply_reciever_userid ,
				reply_giver_userid: reply_giver_userid,
				reply: reply ,  
			   }

		jQuery.ajax({
			url: ajaxurl,
			data:data,
			type:'POST',
			success:function(data) {
				jQuery('#user_reply').hide();
				jQuery('.reply_button').hide();
				location.reload();
			},
		});
	}
});
/*reply review end*/
