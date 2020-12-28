
$(window).bind('resize load',function(e) {


	/**
	 * set position-left and position-right warp in container
	 */
	$('.position-left').css('left',checkMarginContainer());
	$('.position-right').css('right',checkMarginContainer());


});

function checkMarginContainer(){
	return parseInt($('.container').css('margin-left'))+16;
}