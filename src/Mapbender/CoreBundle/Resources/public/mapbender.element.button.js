(function($) {

$.widget("mapbender.mb_button", {
	options: {
            target: undefined,
            click: undefined,
            icon: undefined,
            label: true
        },
        elementUrl: null,
	_create: function() {
		var self = this;
		var me = $(this.element);
                this.elementUrl = Mapbender.configuration.elementPath + me.attr('id') + '/';
                
                var o = {};
                if(this.options.icon){
                    $.extend(o,{
                        icons: {
                            primary:this.options.icon
                        },
                        text: this.options.label
                    });
                }
		me.button(o);
                me.click(function(){
                    self._onClick.call(self);
                });
	},
        
        _onClick: function(){
            if(this.options.target && this.options.click) {
                var target = $('#' + this.options.target);
                var widget = Mapbender.configuration.elements[this.options.target].init;
                target[widget](self.options.click);
            }
        },

	_setOption: function(key, value) {
	},

	destroy: function() {
		$.Widget.prototype.destroy.call(this);
	}
});

})(jQuery);
