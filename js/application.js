$.fn.serializeObject = function()
{
    var o = {};
    // substituiÃ§Ã£o da linha
//    var a = this.serializeArray(); //para considerar campos desabilitados - ZED
    var a = $.map(this.find(':input').not(':button').not(':submit'), function(n, i) {
        if ($(n).attr('name') && (!$(n).is(':checkbox') && !$(n).is(':radio')) || $(n).is(':checked')) {
            var o = {};
            o["name"] = n.name;

            if ($(n).hasClass("real-currency")) {
                o["value"] = $(n).val().replace(/\./g, "").replace(",", ".").replace("R$", "").trim();
            } else if ($(n).hasClass("float")) {
                o["value"] = $(n).val().replace(",", "");
            } else {
                o["value"] = $(n).val();
            }

            return o;
        }
    });

    $.each(a, function() {
        if (this.name) {
            if (this.name.search("\\[\\]") != -1) {
                var name = this.name.replace('[]', '');
                if (!o[name]) {
                    o[name] = [];
                }
                o[name].push(this.value || '');
            } else {
                o[this.name] = $.trim(this.value).length > 0 ? this.value : '';
            }
        }
    });
    return o;
};

var Application = {
    dataset : {},
    openWindow : function(windowName){
        $('#container').load(windowName+".php");
    },
	callMethod : function(callerId, methodToCall){
		var form = $("#"+callerId).parents("form");
		var dataset = Application.getDataset(form);
		var className = form.attr('id').split('_')[0];

		Application.request(className, methodToCall, dataset);
	},
	getDataset : function(form){
		return form.serializeObject();
	},
	request : function(className, methodToCall, dataset, responseHandler, callback){
		var params = {
			dataset : dataset,
			methodToCall : methodToCall
		};

		$.post("_class/"+className+".php", params, function(response){
            responseHandler(response);
            if(callback)callback();
		});
	},
    defaultResponseHandler : function(response, callback){
        response = JSON.parse(response);
        alertMessage(response.type,response.message);
    },
    loadData: function(className, callback){
        Application.request(className, 'loadData', undefined, function(response){
            Application.dataset[className] = response;
            if (callback){
                callback();
            }
        });
    },
    saveRow: function(className, data, callback){
        Application.request(className, 'save', data, Application.defaultResponseHandler, callback);
    },
    deleteRow: function(className, data, callback){
        Application.request(className, 'delete', data, Application.defaultResponseHandler, callback);
    }

};
