$(document).ready(function(){	

	// Maskedinput 
    $.mask.definitions['~'] = "[+-]";
    $(".data").mask("99/99/9999");
    $(".fone").mask("(99) 9999-9999");
    $(".cpf").mask("999.999.999-99");
    $(".cep").mask("99999-999");
    $(".uf").mask("aa");
    $(".placa").mask("aaa-9999");

    /* Botões Adicionar e Listar */
    $('div.listar').hide();
    $('a.btn_add').click(function(){
    	$('div.listar').hide();
    	$('div.adicionar').hide();
    });
    $('a.btn_list').click(function(){
    	$('div.adicionar').hide();
    	$('div.listar').hide();
    });

});	

$(function() {		
		
	  //Close_windows
	  $('.butAcc').live('click',function(e){				   
			  if(buttonActions[this.id]){
				  buttonActions[this.id].call(this);
			  }
			  e.preventDefault();
	  });
	  // Hide All  Alert Message Before
	  $('.alertMessage').live('click',function(){
		  alertHide();
	  });

 function Processgraph(){
	var 	bar = $('.bar'), bw = bar.width(), percent = bar.find('.percent'), circle = bar.find('.circle'), ps =  percent.find('span'),
		cs = circle.find('span'), name = 'rotate';
			var t = $('#pct'), val = t.val();
			if(val){ 
				val = t.val().replace("%", "");
			if (val >=0 && val <= 100){
				var w = 100-val, pw = (bw*w)/100,
					pa = {  	width: w+'%' },
					cw = (bw-pw), ca = {	"left": cw }
				ps.animate(pa);
				cs.text(val+'%');
				circle.animate(ca, function(){
					circle.removeClass(name)
				}).addClass(name);	
			} else {
				alert('range: 0 - 100');
				t.val('');
			}
		}
	}
	
	// Shoutcut   Hover
	$("#shortcut li").hover(function() {
		 var e = this;
		$(e).find("a").stop().animate({ marginTop: "-7px" }, 200, function() {
				$(e).find("a").animate({ marginTop: "-5px" }, 200);
		});
	  },function(){
		 var e = this;
		$(e).find("a").stop().animate({ marginTop: "2px" }, 200, function() {
				 $(e).find("a").animate({ marginTop: "0px" }, 200);
		});
	  });

	// Hide notify  Message with click
	$('#alertMessage').live('click',function(){
	  	$(this).stop(true,true).animate({ opacity: 0,right: '-20'}, 500,function(){ $(this).hide(); });						 
	});
	
	//Bar Chart
	$("table.chart-bar").each(function() {
        var colors = [];
        $("table.chart-bars thead th:not(:first)").each(function() {
            colors.push($(this).css("color"));
        });
        $(this).graphTable({
            series: 'columns',
            position: 'replace',
			width : '100%',
            height: '350px',
            colors: colors
        }, {
			xaxis: { tickSize: 1,  tickSize: 2, },
			series: {
				bars: {
					show: true,
					lineWidth: 1,
					barWidth: 0.7,
					fill: true,
					fillColor: null,
					align: "center",
					horizontal: false
				},
				lines: {
					show: false
				},
				points: {
					show: false
				}
			},
			yaxis: {
				max:22, min:0, tickSize: 2,
				autoscaleMargin: 0.02
            }
        });
    });
});		


	/***********************************************************************
	************************************************************************
	**                                Check browser fixbug                                         ** 
	************************************************************************
	***********************************************************************/  
	
	
	var mybrowser=navigator.userAgent;
	if(mybrowser.indexOf('MSIE')>0){$(function() {	// IE  Fixbug
			   $('.formEl_b fieldset').css('padding-top', '0');
				$('div.section label small').css('font-size', '10px');
				$('div.section  div .select_box').css({'margin-left':'-5px'});
				$('.iPhoneCheckContainer label').css({'padding-top':'6px'});
				$('.uibutton').css({'padding-top':'6px'});
				$('.uibutton.icon:before').css({'top':'1px'});
				$('.dataTables_wrapper .dataTables_length ').css({'margin-bottom':'10px'});
		});
	}
	if(mybrowser.indexOf('Firefox')>0){ $(function() {	 // Firefox  Fixbug
			   $('.formEl_b fieldset  legend').css('margin-bottom', '0px');	
			   $('table .custom-checkbox label').css('left', '3px');
		  });
	}	
	if(mybrowser.indexOf('Presto')>0){ // Opera  Fixbug
		$('select').css('padding-top', '8px');
	}
	if(mybrowser.indexOf('Chrome')>0){$(function() {	 // Chrome  Fixbug
				 $('div.tab_content  ul.uibutton-group').css('margin-top', '-40px');
				  $('div.section  div .select_box').css({'margin-top':'0px','margin-left':'-2px'});
				  $('select').css('padding', '6px');
				  $('table .custom-checkbox label').css('left', '3px');
		});
	}		
	if(mybrowser.indexOf('Safari')>0){}	// Safari  Fixbug
	
	
	/***********************************************************************
	************************************************************************
	**                                Fuction In This Template                                     ** 
	************************************************************************
	***********************************************************************/  


/* Hidden All  Alert Message Before */
function alertHide(){
	$('#alertMessage').each(function(index) {	 
		$(this).attr("id","alertMessage"+index).animate({ opacity: 0,right: '30'}, 500,function(){ $(this).remove(); });	
	});	
}
/* Create Alert Message Box */
function alertMessage(type,str){
	alertHide();
	// type is a success ,info, warning ,error
	$('body').append('<div id="alertMessage" class="alertMessage '+type+'">');
	$.alertbox=$('#alertMessage').html(str);
	$.alertbox.show().animate({ opacity:1,right: '10' },500);
	setInterval(function(){
		$.alertbox.animate({ opacity: 0,right: '30'}, 500,function(){ $(this).remove(); });
	}, 3000);
}

//busca o cep
function buscaCEP(){
    var cep = $('#cep').val();
    var url = 'http://xtends.com.br/webservices/cep/json/'+cep+'/';    
    $.post(url,{cep:cep},
        function (rs) {
            rs = $.parseJSON(rs);
            if(rs.result == 1){
                address = rs.logradouro + ', ' + rs.bairro + ', ' + rs.cidade + ', ' + ', ' + rs.uf;
                $('#logradouro').val(rs.logradouro);
                $('#bairro').val(rs.bairro);
                $('#cidade').val(rs.cidade);
                $('#uf').val(rs.uf);
                $('#cep').removeClass('invalid');
                last_cep = cep;
            }
            else{
                $('#cep').addClass('invalid');    
                $('#cep').focus();  
                last_cep = 0;
            }
        })    
}