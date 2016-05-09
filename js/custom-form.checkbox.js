// custom checkbox module
customForm.addModule({
	name:'checkbox',
	selector:'input[type="checkbox"]',
	defaultOptions: {
		wrapperClass:'chk-area',
		focusClass:'chk-focus',
		checkedClass:'chk-checked',
		labelActiveClass:'chk-label-active',
		uncheckedClass:'chk-unchecked',
		disabledClass:'chk-disabled',
		chkStructure:'<span></span>'
	},
	setupWrapper: function(){
		customForm.lib.addClass(this.fakeElement, this.options.wrapperClass);
		this.fakeElement.innerHTML = this.options.chkStructure;
		this.realElement.parentNode.insertBefore(this.fakeElement, this.realElement);
		customForm.lib.event.add(this.realElement, 'click', this.onRealClick, this);
		this.refreshState();
	},
	onFakePressed: function() {
		customForm.modules[this.name].superclass.onFakePressed.apply(this, arguments);
		if(!this.realElement.disabled) {
			this.realElement.focus();
		}
	},
	onFakeClick: function(e) {
		customForm.modules[this.name].superclass.onFakeClick.apply(this, arguments);
		this.tmpTimer = setTimeout(customForm.lib.bind(function(){
			this.toggle();
		},this),10);
		return false;
	},
	onRealClick: function(e) {
		setTimeout(customForm.lib.bind(function(){
			this.refreshState();
		},this),10);
		e.stopPropagation();
	},
	toggle: function(e){
		if(!this.realElement.disabled) {
			if(this.realElement.checked) {
				this.realElement.checked = false;
			} else {
				this.realElement.checked = true;
			}
			customForm.lib.fireEvent(this.realElement, 'change');
		}
		this.refreshState();
		return false;
	},
	refreshState: function(){
		if(this.realElement.checked) {
			customForm.lib.addClass(this.fakeElement, this.options.checkedClass);
			customForm.lib.removeClass(this.fakeElement, this.options.uncheckedClass);
			if(this.labelFor) {
				customForm.lib.addClass(this.labelFor, this.options.labelActiveClass);
			}
		} else {
			customForm.lib.removeClass(this.fakeElement, this.options.checkedClass);
			customForm.lib.addClass(this.fakeElement, this.options.uncheckedClass);
			if(this.labelFor) {
				customForm.lib.removeClass(this.labelFor, this.options.labelActiveClass);
			}
		}
		if(this.realElement.disabled) {
			customForm.lib.addClass(this.fakeElement, this.options.disabledClass);
			if(this.labelFor) {
				customForm.lib.addClass(this.labelFor, this.options.labelDisabledClass);
			}
		} else {
			customForm.lib.removeClass(this.fakeElement, this.options.disabledClass);
			if(this.labelFor) {
				customForm.lib.removeClass(this.labelFor, this.options.labelDisabledClass);
			}
		}
	}
});