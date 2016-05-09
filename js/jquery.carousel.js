//gallery plugin
(function($) {
	$.fn.gallery = function(options) {
		var args = Array.prototype.slice.call(arguments);
		args.shift();
		this.each(function(){
			if(this.galControl && typeof options === 'string') {
				if(typeof this.galControl[options] === 'function') {
					this.galControl[options].apply(this.galControl, args);
				}
			} else {
				this.galControl = new Gallery(this, options);
			}
		});
		return this;
	};
	function Gallery(context, options) { this.init(context, options); };
	Gallery.prototype = {
		options:{},
		init: function (context, options){
			this.options = $.extend({
				duration: 700,
				slideElement:1,
				autoRotation: false,
				effect: false,
				listOfSlides: '.list > li',
				switcher: false,
				autoSwitcher: false,
				disableBtn: false,
				nextBtn: 'a.link-next, a.btn-next, a.next',
				prevBtn: 'a.link-prev, a.btn-prev, a.prev',
				circle: true,
				clone: false,
				direction: false,
				event: 'click'
			}, options || {});
			var self = this;
			this.context = $(context);
			this.els = this.context.find(this.options.listOfSlides);
			this.list = this.els.parent();
			this.count = this.els.length;
			this.autoRotation = this.options.autoRotation;
			this.direction = this.options.direction;
			this.duration = this.options.duration;
			if (this.options.clone) {
				this.list.append(this.els.clone());
				this.list.prepend(this.els.clone());
				this.els = this.context.find(this.options.listOfSlides);
			}
			this.wrap = this.list.parent();
			if (this.options.nextBtn) this.nextBtn = this.context.find(this.options.nextBtn);
			if (this.options.prevBtn) this.prevBtn = this.context.find(this.options.prevBtn);

			this.calcParams(this);
			
			if (this.options.autoSwitcher) {
				this.switcherHolder = this.context.find(this.options.switcher).empty();
				this.switchPattern = $('<ul class="'+ (this.options.autoSwitcher == true ? '' : this.options.autoSwitcher) +'"></ul>');
				for (var i=0;i<this.max+1;i++){
					$('<li><a href="#">'+i+'</a></li>').appendTo(this.switchPattern);
				}
				this.switchPattern.appendTo(this.switcherHolder);
				this.switcher = this.context.find(this.options.switcher).find('li');
				this.active = 0;
			} else {
				if (this.options.switcher) {
					this.switcher = this.context.find(this.options.switcher);
					this.active = this.switcher.index(this.switcher.filter('.active:eq(0)'));
				}
				else this.active = this.els.index(this.els.filter('.active:eq(0)'));
			}
			if (this.active < 0) this.active = 0;
			this.last = this.active;
			if (this.options.switcher) this.switcher.removeClass('active').eq(this.active).addClass('active');
			if (this.options.clone) this.active += this.count;
			
			if (this.options.effect) this.els.css({opacity: 0}).removeClass('active').eq(this.active).addClass('active').css({opacity: 1}).css('opacity', 'auto');
			else {
				if (this.direction) this.list.css({marginTop: -(this.mas[this.active])});
				else this.list.css({marginLeft: -(this.mas[this.active])});
			}
			
			
			if (this.options.nextBtn) this.initEvent(this, this.nextBtn,true);
			if (this.options.prevBtn) this.initEvent(this, this.prevBtn,false);
			
			this.initWindow(this,$(window));
			
			if (this.autoRotation) this.runTimer(this);
			
			if (this.options.switcher) this.initEventSwitcher(this, this.switcher);
			if (this.options.disableBtn && !this.options.circle && !this.options.clone) this.disableControls();
		},
		calcParams: function(self){
			this.mas = [];
			this.sum = 0;
			this.max = this.count-1;
			this.width = 0;
			this.els.each(function(){self.mas.push(self.width);self.width += self.direction?$(this).outerHeight(true):$(this).outerWidth(true);self.sum+=self.direction?$(this).outerHeight(true):$(this).outerWidth(true);});
			this.finish = this.direction?this.sum-this.wrap.outerHeight():this.sum-this.wrap.outerWidth();
			for (var i=0;i<this.count;i++){
				if (this.mas[i]>=this.finish) {
					this.max = i;
					break;
				}
			}
		},
		changeSettings: function(set,val){
			this[set] = val;
		},
		fadeElement: function(){
			this.els.eq(this.last).animate({opacity:0}, {queue:false, duration: this.duration});
			this.els.removeClass('active').eq(this.active).addClass('active').animate({
				opacity:1
			}, {queue:false, duration: this.duration, complete: function(){
				$(this).css('opacity','auto');
			}});
			if (this.options.switcher) this.switcher.removeClass('active').eq(this.active).addClass('active');
			this.last = this.active;
		},
		scrollElement: function(f){
			if (this.direction) this.list.animate({marginTop: f ? -this.finish : -(this.mas[this.active])}, {queue:false, duration: this.duration});
			else this.list.animate({marginLeft: f ? -this.finish : -(this.mas[this.active])}, {queue:false, duration: this.duration});
			if (this.options.switcher) this.switcher.removeClass('active').eq(this.options.clone ? this.active < this.count ? this.active/this.options.slideElement : this.active >= this.count*2 ? (this.active - this.count*2)/this.options.slideElement : (this.active - this.count)/this.options.slideElement : this.active/this.options.slideElement).addClass('active');
		},
		runTimer: function($this){
			if($this._t) clearTimeout($this._t);
			$this._t = setInterval(function(){
				$this.nextStep();
			}, this.autoRotation);
		},
		initEventSwitcher: function($this, el){
			el.bind($this.options.event, function(){
				if (!$(this).hasClass('active')){
					$this.active = $this.switcher.index($(this)) * $this.options.slideElement;
					if ($this.options.clone) $this.active += $this.count;
					$this.initMove();
				}
				return false;
			});
		},
		initEvent: function($this, addEventEl, dir){
			addEventEl.bind($this.options.event, function(){
				if (dir) $this.nextStep();
				else $this.prevStep();
				if($this._t) clearTimeout($this._t);
				if ($this.autoRotation) $this.runTimer($this);
				return false;
			});
		},
		disableControls: function(){
			this.prevBtn.removeClass(this.options.disableBtn);
			this.nextBtn.removeClass(this.options.disableBtn);
			if (this.active>=this.max) this.nextBtn.addClass(this.options.disableBtn);
			if (this.active<=0) this.prevBtn.addClass(this.options.disableBtn);
		},
		initMove: function(){
			var f = false;
			if (this.active >= this.max && !this.options.clone) {
				f = true;
				this.active = this.max;
			}
			if(this._t) clearTimeout(this._t);
			if (!this.options.effect) this.scrollElement(f);
			else this.fadeElement();
			if (this.autoRotation) this.runTimer(this);
			if (this.options.disableBtn && !this.options.circle && !this.options.clone) this.disableControls();
		},
		nextStep:function(){
			var f = false;
			this.active = this.active + this.options.slideElement;
			if (this.options.disableBtn && !this.options.circle && !this.options.clone) this.disableControls();
			if (this.options.clone){
				if (this.active > this.count*2) {
					if (this.direction) this.list.css({marginTop:-this.mas[this.count]});
					else this.list.css({marginLeft:-this.mas[this.count]});
					this.active = this.count+this.options.slideElement;
				}
			} else {
				if (this.active >= this.max) {
					if (this.options.circle) {
						if (this.active > this.max) this.active = 0;
						else {
							this.active = this.max;
							f = true
						}
					}
					else {
						this.active = this.max;
						f = true;
					}
				}
			}
			if (!this.options.effect) this.scrollElement(f);
			else this.fadeElement();
		},
		prevStep: function(){
			var f = false;
			this.active = this.active - this.options.slideElement;
			if (this.options.disableBtn && !this.options.circle && !this.options.clone) this.disableControls();
			if (this.options.clone){
				if (this.active < 0) {
					if (this.direction) this.list.css({marginTop:-this.mas[this.count]});
					else this.list.css({marginLeft:-this.mas[this.count]});
					this.active = this.count-1;
				}
			} else {
				if (this.active < 0) {
					if (this.options.circle) {
						this.active = this.max;
						f = true;
					}
					else this.active = 0;
				}
			}
			if (!this.options.effect) this.scrollElement(f);
			else this.fadeElement();
		},
		initWindow: function($this,$window){
			$window.focus($.proxy(this.play,this));
			$window.blur($.proxy(this.stop,this));
		},
		stop: function(){
			if (this._t) clearTimeout(this._t);
		},
		play: function(){
			if (this._t) clearTimeout(this._t);
			if (this.autoRotation) this.runTimer(this);
		}
	}
}(jQuery));