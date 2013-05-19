/*
Zice admin  v1.7
Release Date: Jan 6, 2012
*/
$.fn.imgdata = function(key){
	return this.find('.dataImg li:eq('+key+')').text();
}
$.fn.hdata = function(key){
	return this.find('.dataSet li:eq('+key+')').text();
}
var buttonActions = {
	  'close_windows':function(){
		  $.fancybox.close(); 
		  ResetForm();
	}	
}
$(document).ready(function(){	
	// Main menu 
	$('ul#main_menu ul').hide();
	$('ul#main_menu li ').hover( function () {
		  var parent = $(this).parents('ul').attr('id');
		   var parents = $(this).find('ul');
		  $('#' + parent + ' ul:visible').hide();
		   $(parents).show();
		  $('#' + parent + ' ul:visible li:first').append('<div class="arr"><span></span></div>');
		  $('#' + parent + ' ul:visible ').live({  mouseleave: function(){  $(this).hide(); } });
	});
	// WYSIWYG Editor
	$("#editor,#editor2").cleditor();	
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

	// Maskedinput 
    $.mask.definitions['~'] = "[+-]";
    $(".data").mask("99/99/9999");
    $(".fone").mask("(99) 9999-9999");
    $(".cpf").mask("999.999.999-99");
    $(".cep").mask("99999-999");
    $(".uf").mask("aa");
    $(".placa").mask("aaa-9999");

    /* Dialog */
    $('.delete').click(function(){
    	bootbox.dialog("Deseja realmente excluir?", [{
			"label" : "Sim",
			"class" : "btn-success",
			"callback": function() {
				alertMessage("success","Excluído com sucesso!");
			}
		}, {
			"label" : "Cancelar"
		}]);
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
	
	// Spinner options 
	var itemListspinner = [
		{url: "http://ejohn.org", title: "John Resig"},
		{url: "http://bassistance.de/", title: "J&ouml;rn Zaefferer"},
		{url: "http://snook.ca/jonathan/", title: "Jonathan Snook"},
		{url: "http://rdworth.org/", title: "Richard Worth"},
		{url: "http://www.paulbakaus.com/", title: "Paul Bakaus"},
		{url: "http://www.yehudakatz.com/", title: "Yehuda Katz"},
		{url: "http://www.azarask.in/", title: "Aza Raskin"},
		{url: "http://www.karlswedberg.com/", title: "Karl Swedberg"},
		{url: "http://scottjehl.com/", title: "Scott Jehl"},
		{url: "http://jdsharp.us/", title: "Jonathan Sharp"},
		{url: "http://www.kevinhoyt.org/", title: "Kevin Hoyt"},
		{url: "http://www.codylindley.com/", title: "Cody Lindley"},
		{url: "http://malsup.com/jquery/", title: "Mike Alsup"}
	];
	var optionspinner = {
		'sDec': {decimals:2},
		'sMinMax':{min: -100, max: 100 },
		'sStep': {stepping: 0.25},
		'sStep': {stepping: 0.25},
		'sCur': {currency: 'R$ '},
		'sCur2': {currency: 'R$ '}, 
        'sCur3': {currency: 'R$ '},
		'sInline': {},
		'sLink': {
			init: function(e, ui) {
				for (var i=0; i<itemListspinner.length; i++) {
					ui.add('<a href="'+ itemListspinner[i].url +'" target="_blank">'+ itemListspinner[i].title +'</a>');
				}
			},
			format: '<a href="%(url)" target="_blank">%(title)</a>',
			items: itemListspinner
		}
	};	
	for (var n in optionspinner){
		$("#"+n).spinner(optionspinner[n]);
	}

	// Icon  gray Hover
	$('.iconBox.gray').hover(function(){
		  var name=$(this).find('img').attr('alt');
		  $(this).find('img').animate({ opacity: 0.5 }, 0, function(){
			    $(this).attr('src','images/icon/color_18/'+name+'.png').animate({ opacity: 1 }, 700);									 
		 });
	},function(){
		  var name=$(this).find('img').attr('alt');
		  $(this).find('img').attr('src','images/icon/gray_18/'+name+'.png');
	 })
	
	// ShoutcutBox   Hover
	$('.shoutcutBox').hover(function(){
		  $(this).animate({ left: '+=15'}, 200);
	},function(){
		  $(this).animate({ left: '0'}, 200);
	 });
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
	$.alertbox.show().animate({ opacity: 1,right: '10' },500);
}	  

/* Funções Adicionar e Listar */
function adicionar(){
	$('.listar').hide();
	$('.adicionar').show();
}
function listar(){
	$('.adicionar').hide();
	$('.listar').show();
}