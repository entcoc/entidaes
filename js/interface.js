$(document).ready(function(){
	$('.slider_principal').bxSlider({
		speed:2000,
		auto:true,
		pause:4000
	});
	var w_window = $(window).width(), h_window = $(window).height();	
	$('.entidades_principal').css({'height':h_window+'px'})
});