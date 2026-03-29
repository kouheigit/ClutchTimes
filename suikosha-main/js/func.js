$(function(){
	
	$('a[href^="#"]').on('click',function(){
		let href= $(this).attr('href'),
			target = $(href == '#' || href == '' ? 'html' : href),
			position = target.offset().top,
			ww = $(window).width(),
			hh = 0;
		if(ww < 1081){
			hh = $('#header').outerHeight();
		}
		$('html,body').animate({scrollTop: position - hh},'slow','swing').promise();
		return false;
	});
	
	if($('.timepicker').length > 0){
		$('.timepicker').timepicker({
			timeFormat: 'HH:mm',
			dynamic: false,
			dropdown: true,
			scrollbar: true
		});
	}
	
	if($('.timepicker_rest').length > 0){
		$('.timepicker_rest').timepicker({
			interval: 5,
			timeFormat: 'HH:mm',
			dynamic: false,
			dropdown: true,
			scrollbar: true,
			startTime: '00:00',
			maxTime: '05:00',
		});
	}
	
	if($('.datepicker').length > 0){
		$('.datepicker').datepicker({
			inline: true,
			language: 'ja',
			format: "yyyy/mm/dd(D)"
		});
	}
	
	if($('.datepicker_w').length > 0){
		$('.datepicker_w').datepicker({
			inline: true,
			language: 'ja',
			dateFormat: "yy/mm/dd (D)"
		});
	}
	
	$('.btn_confirm').on('click',function(){
		let result = confirm($(this).data('confirm'));
		
		if(!result){
			return false;
		}
	});
	
	$('.modal_on').on('click',function(){
		let modal_class = $(this).data('modal');
		$('.modal, '+modal_class).addClass('on');
		return false;
	});
	
	$('.modal_close').on('click',function(){
		$('.modal, .modal_inner').removeClass('on');
		return false;
	});
	
	$('.data_linkage').on('keyup',function(){
		let linkage_text = $(this).val(),
				linkage_class = $(this).data('linkage'),
				linkage_list = $(this).data('list'),
				linkage_put = false;
		$('option', linkage_list).each(function(data){
			if($(this).text() === linkage_text){
				linkage_put = $(this).val();
			}
		});
		if(linkage_put){
			$(linkage_class).val(linkage_put);
		}else{
			$(linkage_class).val('');
		}
	});
	$('.authority_map_open').on('click',function(){
		$('.authority_map_open, .authority_map, .authority_map_overlay, .authority_map_tab').toggleClass('on');
		return false;
	});
	
	$('.func_form_copy_btn').on('click',function(){
		let copy_flag = true;
		$('.func_form_copy_to input').each(function(){
			if($(this).val() !== ''){
				copy_flag = false;
			}
		});
		if(!copy_flag){
			let result = confirm('データがすでに入っています。それでもコピーしますか？');
			if(!result){
				return false;
			}else{
				func_form_copy();
			}
		}else{
			func_form_copy();
		}
		
	});
	
	let modal_flag = 0;
	$('.modal_open1').on('click',function(){
		if(modal_flag == 0){
			$('.modal.t1').addClass('on');
		}
	});
});


$(window).on('load scroll', function(){
	let sct = $(this).scrollTop();
	if(sct > 250){
		$('#to_top').addClass('on');
	}else{
		$('#to_top').removeClass('on');
	}
});


function func_form_copy(){
	let input_val = [];
	$('.func_form_copy_base input').each(function(i){
		$('.func_form_copy_to input').eq(i).val($(this).val());
	});
}