(function($){
	$.fn.tmStickUp=function(options){ 
		
		var getOptions = {
			correctionSelector: $('.correctionSelector')
		,	active: false
		}
		$.extend(getOptions, options); 

		var
			_this = $(this)
		,	_window = $(window)
		,	_document = $(document)
		,	thisOffsetTop = 0
		,	thisOuterHeight = 0
		,	thisMarginTop = 0
		,	thisPaddingTop = 0
		,	documentScroll = 0
		,	pseudoBlock
		,	lastScrollValue = 0
		,	scrollDir = ''
		,	tmpScrolled
		;

		init();
		function init(){
			thisOffsetTop = parseInt(_this.offset().top);
			thisMarginTop = parseInt(_this.css("margin-top"));
			thisOuterHeight = parseInt(_this.outerHeight(true));

			$('<div class="pseudoStickyBlock"></div>').insertAfter(_this);
			pseudoBlock = $('.pseudoStickyBlock');
			pseudoBlock.css({"position":"relative", "display":"block"});

			if(getOptions.active){
				addEventsFunction();
			}
		}//end init

		function addEventsFunction(){
			_document.on('scroll', function() {
				tmpScrolled = $(this).scrollTop();
					if (tmpScrolled > lastScrollValue){
						scrollDir = 'down';
					} else {
						scrollDir = 'up';
					}
				lastScrollValue = tmpScrolled;

				correctionValue = getOptions.correctionSelector.outerHeight(true);
				documentScroll = parseInt(_window.scrollTop());

				if(thisOffsetTop - correctionValue < documentScroll){
					_this.addClass('isStuck');
					_this.css({position:"fixed", top:correctionValue});
					pseudoBlock.css({"height":thisOuterHeight});
				}else{
					_this.removeClass('isStuck');
					_this.css({position:"relative", top:0});
					pseudoBlock.css({"height":0});
				}
			}).trigger('scroll');
		}
	}//end tmStickUp function
})(jQuery)