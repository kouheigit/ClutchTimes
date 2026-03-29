$(function(){

$('#sec2_slide').slick({
	slidesToShow: 3,
	slidesToScroll: 3,
    responsive: [{
		breakpoint: 1080,
		settings: {
			slidesToShow: 1,
			slidesToScroll: 1,
			centerMode: true,
			centerPadding: '15vw',
		},
    }]
});
if($('#news_list li').length > 1){
	setInterval(function(){
		if($('#news_list .on').next().length){
			$('#news_list .on').removeClass('on').next().addClass('on');
		}else{
			$('#news_list li').removeClass('on');
			$('#news_list li').eq(0).addClass('on');
		}
	}, 5000);
}

});