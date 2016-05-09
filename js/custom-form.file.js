// custom upload field module
customForm.addModule({
	name: 'file',
	selector: 'input[type="file"]',
	defaultOptions: {
		buttonWidth: 30,
		bigFontSize: 200,
		buttonText:'Browse',
		wrapperClass:'file-area',
		focusClass:'file-focus',
		disabledClass:'file-disabled',
		opacityClass:'file-input-opacity',
		noFileClass:'no-file',
		extPrefixClass:'extension-',
		uploadStructure:'<div class="customForm-input-wrapper"><div class="customForm-wrap"></div><label class="customForm-fake-input"><span><em></em></span></label><a class="customForm-upload-button"><span></span></a></div>',
		uploadFileNameSelector:'label.customForm-fake-input span em',
		uploadButtonSelector:'a.customForm-upload-button span',
		inputWrapper: 'div.customForm-wrap'
	},
	setupWrapper: function(){
		customForm.lib.addClass(this.fakeElement, this.options.wrapperClass);
		this.fakeElement.innerHTML = this.options.uploadStructure;
		this.realElement.parentNode.insertBefore(this.fakeElement, this.realElement);
		this.fileNameInput = customForm.lib.queryBySelector(this.options.uploadFileNameSelector ,this.fakeElement)[0];
		this.uploadButton = customForm.lib.queryBySelector(this.options.uploadButtonSelector ,this.fakeElement)[0];
		this.inputWrapper = customForm.lib.queryBySelector(this.options.inputWrapper ,this.fakeElement)[0];

		this.origTitle = this.realElement.title;
		this.fileNameInput.innerHTML = this.realElement.title || '';
		this.uploadButton.innerHTML = /*this.options.buttonText*/'Enviar';
		this.realElement.removeAttribute('title');
		this.fakeElement.style.position = 'relative';
		this.realElement.style.position = 'absolute';
		this.realElement.style.zIndex = 100;
		this.inputWrapper.appendChild(this.realElement);
		this.oTop = this.oLeft = this.oWidth = this.oHeight = 0;

		customForm.lib.addClass(this.realElement, this.options.opacityClass);
		customForm.lib.removeClass(this.realElement, customForm.baseOptions.hiddenClass);
		this.inputWrapper.style.width = this.inputWrapper.parentNode.offsetWidth+'px';

		this.shakeInput();
		this.refreshState();
		this.addEvents();
	},
	addEvents: function(){
		customForm.lib.event.add(this.realElement, 'change', this.onChange, this);
		if(!customForm.isTouchDevice) {
			customForm.lib.event.add(this.fakeElement, 'mousemove', this.onMouseMove, this);
			customForm.lib.event.add(this.fakeElement, 'mouseover', this.recalcDimensions, this);
		}
	},
	onMouseMove: function(e){
		this.realElement.style.top = Math.round(e.pageY - this.oTop - this.oHeight/2) + 'px';
		this.realElement.style.left = (e.pageX - this.oLeft - this.oWidth + this.options.buttonWidth) + 'px';
	},
	onChange: function(){
		this.refreshState();
	},
	getFileName: function(){
		return this.realElement.value.replace(/^[\s\S]*(?:\\|\/)([\s\S^\\\/]*)$/g, "$1");
	},
	getFileExtension: function(){
		return this.realElement.value.lastIndexOf('.') < 0 ? false : this.realElement.value.substring(this.realElement.value.lastIndexOf('.')+1).toLowerCase();
	},
	updateExtensionClass: function(){
		var currentExtension = this.getFileExtension();
		if(currentExtension) {
			this.fakeElement.className = this.fakeElement.className.replace(new RegExp('(\\s|^)'+this.options.extPrefixClass+'[^ ]+','gi'),'')
			customForm.lib.addClass(this.fakeElement, this.options.extPrefixClass+currentExtension)
		}
	},
	shakeInput: function() {
		// make input bigger
		customForm.lib.setStyles(this.realElement, {
			fontSize: this.options.bigFontSize,
			lineHeight: this.options.bigFontSize,
			heigth: 'auto',
			top: 0,
			left: this.inputWrapper.offsetWidth - this.realElement.offsetWidth
		});
		// IE styling fix
		if((/(MSIE)/gi).test(navigator.userAgent)) {
			this.tmpElement = document.createElement('span');
			this.inputWrapper.insertBefore(this.tmpElement,this.realElement);
			this.inputWrapper.insertBefore(this.realElement,this.tmpElement);
			this.inputWrapper.removeChild(this.tmpElement);
		}
	},
	recalcDimensions: function() {
		var o = customForm.lib.getOffset(this.fakeElement);
		this.oTop = o.top;
		this.oLeft = o.left;
		this.oWidth = this.realElement.offsetWidth;
		this.oHeight = this.realElement.offsetHeight;
	},
	refreshState: function(){
		customForm.lib.setStyles(this.realElement, {opacity: 0});
		this.fileNameInput.innerHTML = this.getFileName() || this.origTitle || '';
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
		if(this.realElement.value.length) {
			customForm.lib.removeClass(this.fakeElement, this.options.noFileClass);
		} else {
			customForm.lib.addClass(this.fakeElement, this.options.noFileClass);
		}
		this.updateExtensionClass();
	}
});