function slideshow()
{
	$('div#slideshow > img:gt(0)').hide();
	setInterval(function(){
		// alert('called');
		$('div#slideshow > img:first')
			.fadeOut(1000)
			.next()
			.fadeIn(1000)
			.end()
			.appendTo('#slideshow');
	}, 4000);
}
