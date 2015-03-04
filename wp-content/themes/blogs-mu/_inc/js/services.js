console.log('services.js loaded');

$=jQuery;
var last_service_index=0;

$(document).ready(function($){
	$('input[name="add_image"]').click(function(){
		last_service_index = $(this).parents('.one_service').first().attr('data-service-id');
		tb_show('Upload/select a services image', 'media-upload.php?referer=blogsmu-settings&type=image&TB_iframe=true&post_id=0', false);  
		return false;
	});


	$('.remove_service').click(function(){
		if( confirm('Delete this service?') ){
			$(this).parent('.one_service').remove();
			reindex_services();
			var form = $('form.services');
			var button = $("<input type='submit' style='display: none'/>"); form.append(button); button.trigger('click');
		}
		return false;
	});

	$('.add_service').click(function(){
		add_new_service();
		window.scrollTo(0, document.body.scrollHeight);
		return false;
	});

	$('input[name="remove_image"]').click(function(){
		remove_image($(this).parents('.one_service').first());
		return false;
	});

	$('.edit_image').click(function(){
		var attachment_post_id = $('[name*="attachment_post_id"]', $(this).parents('.one_service')).val();
		if(attachment_post_id.match(/^[1-9]\d*$/) ){
			window.open('media.php?attachment_id='+attachment_post_id+'&action=edit');
		}
		return false;
	});

	$('.save_all').click(function(){
		$('form.services').submit();
	});


});

window.send_to_editor = function(html){
	var img_cont = $('.services [data-service-id="'+last_service_index+'"] .service_image');

	var attachment_post_id=0;
	m = html.match(/wp-image-(\d+)/i);
	if (m != null) {
		attachment_post_id = m[1];
	}

	var img = $('img','<div>'+html+'</div>').first();

	img_cont.append(img);
	$('input[name*="attachment_post_id"]',img_cont).val(attachment_post_id);
	img_cont.removeClass('no_image').addClass('one_image');

	tb_remove();
}


/*
one form is used to save all services.
input names use arrays, e.g:
name="s[0][key]"
name="s[1][key]"

when one service is removed, reindex those arrays with sequential indexes: 0, 1, 2 ... 
*/
function reindex_services(){
	var index=0;
	$('.services .one_service').each(function(){
		change_index(index, $(this));
		index=index+1;
	});
}

/* change input indexes for one service */
function change_index(new_index, service){
	var num = new_index+1;

	// reset title
	service.attr('data-service-id', new_index);
	$('.stitle',service).html('Service '+num);

	// reset input indexes
	$('[name]', service).each(function(){

		// match: name="s[0][str_key]"
		var m = $(this).attr('name').match(/^s\[(\d+?)\]\[([^\]]+?)\]$/i);
		if (m != null){
			$(this).attr('name', 's['+new_index+']['+m[2]+']');
		}
	});
}

function add_new_service(){
	var new_index = parseInt($('.services .one_service[data-service-id]').last().attr('data-service-id')) + 1 || 0;
	var template = $('.one_service_template .one_service');
	change_index(new_index, template);
	template.clone(true).appendTo('form.services');
}

function remove_image(service){
	$('.service_image img', service).remove();
	$('input[name*="attachment_post_id"]', service).val(0);

	$('.service_image', service).removeClass('one_image').addClass('no_image');
}