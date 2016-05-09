// custom radio module
customForm.addModule({
	name:'radio',
	selector: 'input[type="radio"]',
	defaultOptions: {
		wrapperClass:'rad-area',
		focusClass:'rad-focus',
		checkedClass:'rad-checked',
		uncheckedClass:'rad-unchecked',
		disabledClass:'rad-disabled',
		radStructure:'<span></span>'
	},
	getRadioGroup: function(item){
		var name = item.getAttribute('name');
		if(name) {
			return customForm.lib.queryBySelector('input[name="'+name+'"]', customForm.lib.getParent('form'));
		} else {
			return [item];
		}
	},
	setupWrapper: function(){
		customForm.lib.addClass(this.fakeElement, this.options.wrapperClass);
		this.fakeElement.innerHTML = this.options.radStructure;
		this.realElement.parentNode.insertBefore(this.fakeElement, this.realElement);
		this.refreshState();
		this.addEvents();
	},
	addEvents: function(){
		customForm.lib.event.add(this.fakeElement, 'click', this.toggleRadio, this);
		if(this.labelFor) {
			customForm.lib.event.add(this.labelFor, 'click', this.toggleRadio, this);
		}
	},
	onFocus: function(e) {
		customForm.modules[this.name].superclass.onFocus.apply(this, arguments);
		setTimeout(customForm.lib.bind(function(){
			this.refreshState();
		},this),10);
	},
	toggleRadio: function(){
		if(!this.realElement.disabled) {
			this.realElement.checked = true;
		}
		this.refreshState();
		customForm.lib.fireEvent(this.realElement, 'change');
	},
	refreshState: function(){
		var els = this.getRadioGroup(this.realElement);
		for(var i = 0; i < els.length; i++) {
			var curEl = els[i].customForm;
			if(curEl) {
				if(curEl.realElement.checked) {
					customForm.lib.addClass(curEl.fakeElement, curEl.options.checkedClass);
					customForm.lib.removeClass(curEl.fakeElement, curEl.options.uncheckedClass);
					if(curEl.labelFor) {
						customForm.lib.addClass(curEl.labelFor, curEl.options.labelActiveClass);
					}
				} else {
					customForm.lib.removeClass(curEl.fakeElement, curEl.options.checkedClass);
					customForm.lib.addClass(curEl.fakeElement, curEl.options.uncheckedClass);
					if(curEl.labelFor) {
						customForm.lib.removeClass(curEl.labelFor, curEl.options.labelActiveClass);
					}
				}
				if(curEl.realElement.disabled) {
					customForm.lib.addClass(curEl.fakeElement, curEl.options.disabledClass);
					if(curEl.labelFor) {
						customForm.lib.addClass(curEl.labelFor, curEl.options.labelDisabledClass);
					}
				} else {
					customForm.lib.removeClass(curEl.fakeElement, curEl.options.disabledClass);
					if(curEl.labelFor) {
						customForm.lib.removeClass(curEl.labelFor, curEl.options.labelDisabledClass);
					}
				}
			}
		}
	}
});