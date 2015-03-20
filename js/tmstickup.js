(function($){
	$.fn.tmStickUp=function(options){

		var getOptions = {
			correctionSelector: $('.correctionSelector')
		,	listenSelector: $('.listenSelector')
		,	active: false
		,	pseudo: true
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

		if (_this.length != 0) {
			init();
		}

		function init(){
			thisOffsetTop = parseInt(_this.offset().top);
			thisMarginTop = parseInt(_this.css("margin-top"));
			thisOuterHeight = parseInt(_this.outerHeight(true));

			if(getOptions.pseudo){
				$('<div class="pseudoStickyBlock"></div>').insertAfter(_this);
				pseudoBlock = $('.pseudoStickyBlock');
				pseudoBlock.css({"position":"relative", "display":"block"});
			}

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

				if(getOptions.correctionSelector.length != 0){
					correctionValue = getOptions.correctionSelector.outerHeight(true);
				}else{
					correctionValue = 0;
				}

				documentScroll = parseInt(_window.scrollTop());
				if(thisOffsetTop - correctionValue < documentScroll){
					_this.addClass('isStuck');
					getOptions.listenSelector.addClass('isStuck');
					if(getOptions.pseudo){
						_this.css({position:"fixed", top:correctionValue});
						pseudoBlock.css({"height":thisOuterHeight});
					}else{
						_this.css({position:"fixed", top:correctionValue});
					}
				}else{
					_this.removeClass('isStuck');
					getOptions.listenSelector.removeClass('isStuck');
					if(getOptions.pseudo){
						_this.css({position:"relative", top:0});
						pseudoBlock.css({"height":0});
					}else{
						_this.css({position:"absolute", top:0});
					}
				}
			}).trigger('scroll');

			_document.on("resize", function() {
				if(_this.hasClass('isStuck')){
					if( thisOffsetTop != parseInt(pseudoBlock.offset().top) ) thisOffsetTop = parseInt(pseudoBlock.offset().top);
				} else {
					if( thisOffsetTop != parseInt(_this.offset().top) ) thisOffsetTop = parseInt(_this.offset().top);
				}
			})
		}
	}//end tmStickUp function
})(jQuery)