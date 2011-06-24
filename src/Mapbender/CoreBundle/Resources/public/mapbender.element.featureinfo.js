(function($) {
    $.widget("mapbender.mb_featureinfo", $.mapbender.mb_button, {
        options: {
            target: undefined
        },
        
        map: null,
        elementUrl: null,

        _create: function() {
            this._super("_create");
            this.map = $("#" + this.options.target);
        },
        
        _onClick: function(){
            var self = this;
            this.map.click(function(e){
                self._set.call(self,e);
            });
        },
        
        _set: function(e){
            var self = this;
            var map = $(e.currentTarget);
            var offset = map.offset();
            var x = e.pageX - offset.left;
            var y = e.pageY - offset.top;
            var width = map.width();
            var height = map.height();
            var mapQuery = map.data("mapQuery");
            console.log(mapQuery);
            var data = mapQuery.goto();
            var layers = mapQuery.layers();
            //console.log(layers)
            var extent = data.box.join(",");
            
            this.dlg = $('<div></div>')
            .attr('id', 'mb-featureinfo')
            .html('Loading...')
            .appendTo($('body'))
            .dialog({
                title: 'FeatureInfo',
                autoOpen: false,
                modal: true,
                position:[x,y]
            });
            
                
                
            this.dlg.dialog('open').dialog("widget").effect("size",{from:{width:0,height:0},to:{width:300,height:300}, scale:"box"},1000);
            
            console.log(this.dlg);
            
            $.ajax({
                url: this.elementUrl + 'get',
                dataType: 'json',
                context: self,
                success: self._onAjaxSuccess,
                error: self._onAjaxError
            });
                
        },
        
        _onAjaxSuccess: function(data) {
            var html = '<ul>';
            $.each(data, function(key, val) {
                html += '<li>' + key +  ': ' + val + '</li>';
            });
            html += '</ul>';
            this.dlg.html(html);
        },

        _onAjaxError: function(XHR, d, f) {
            console.log(arguments);
        }
        
    })
})(jQuery);
