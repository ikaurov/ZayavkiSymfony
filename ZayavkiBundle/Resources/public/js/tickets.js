
function open_ticket(id, status, list) {

    if (status == 2) {
		if (!$('div').is('#closed_card')) {
			$('#cardholder').after('<div id="closed_card"></div>');
		}

		$('#closed_card').dialog({ title: 'Карточка заявки',  
						width: 600, height: 700, closed: false, cache: false, 
						href: 'ticket_closed/'+id, modal: true,
			            buttons:[ {text:'Удалить', id:"btc_delete", handler:function(){  } },
								  {text:'Печать', handler:function(){ window.open(fx.path+'/ticket/card/{"ticketid":'+id+',"status":'+status+'}/', "_blank"); } },
								  {text:'Закрыть',id:"btc_close", handler:function(){  $('#closed_card').dialog('close'); } }]
        });
    }
    else {
		if (!$('div').is('#ticket_card')) {
			$('#cardholder').after('<div id="ticket_card"></div>');
		}	
	
		var client_h = ((document.body.clientHeight > 720) ? 720 : document.body.clientHeight - 200);		
		// старое значение: 760
		$('#ticket_card').dialog({
			title: 'Карточка заявки',
			left: 100,
			top: 10,
			width: 680,
			height: client_h,
			closed: false,
			resizable: true,
			cache: false,
			href: 'ticket_id/'+id,
			modal: true,
			buttons:[ {text:'Удалить',id:"b_dlg_delete",
				handler:function(){
            	 	$.messager.defaults.ok = 'Да';
					$.messager.defaults.cancel = 'Нет';
            		$.messager.confirm('Внимание !','Вы уверены, что желаете удалить заявку ?',function(r){
					if (r){
						$.ajax({
					       	type: "GET",
							url: 'tickets_delete/'+id,
							dataType: "json",
							success: function(data) {
		                      	var data = eval(data);  // change the JSON string to javascript object

			                    if (data.success){
									if (list) {
										list.datagrid('reload');
									}
    				                $('#ticket_card').dialog('close');
						 		}
						 	}
						});
					}
					});
            	}
            },
            {text:'ОК',id:"b_dlg_save",
   		        handler:function(){
                    var tsgid    = $('#ticket_tsgid').val();
                    var categ    = $('#ticket_categoryid').val();
					var msg      = $('#ticket_message').val();
          	        $('#formcard_ticket').form('submit', {
        		        url: 'ticket_save/'+id,
		                onSubmit: function(){
	                  		if (tsgid == 0) {
	                   			$.messager.alert("Внимание !", "Выберите организацию", "info");
	                   			return false;
	                   		}
	                   		if (categ == 0) {
	                   			$.messager.alert("Внимание !", "Выберите категорию заявки", "info");
	                   			return false;
	                   		}
	                   		if (msg.length == 0) {
	                  			$.messager.alert("Внимание !", "Введите текст заявки", "info");
	                  			return false;
	                   		}
        		            return true;
                	    },
                        success:function(data){
						console.log(data);
	                      	var data = eval('(' + data + ')');  // change the JSON string to javascript object
		                    if (data.success){
		                          	if (data.id > 0) {
			                            $.messager.alert('Внимание !', data.message, 'info');
			                        }
									if (list) {
										list.datagrid('reload');
									}
    			                    $('#ticket_card').dialog('close');
            		        }   else {
                    		        $.messager.alert('Внимание !', data.message, 'info');
                        	}
                        }
                    });
   		    	}
   			},
	   	    {text:'Отмена',id:"b_dlg_cancel",  handler:function(){ $('#ticket_card').dialog('close'); } }
	   		],
		   	onBeforeOpen:function(){
				$('#b_dlg_save').linkbutton({text: ((id == 0)?'Создать':'ОК')});

				if(this.head == 0) {
		   			$('#b_dlg_delete').css("display","none");
		   		}
		   		else {
		   			$('#b_dlg_delete').css("float","left");
				}
				$('#ticket_card').dialog('dialog').attr('tabIndex','-1').bind('keydown',function(e){
					if (e.keyCode == 27){
						$('#ticket_card').dialog('close');
					}
				});					
		   	  	return true;
   			}				
        });
    }//else
}//function
