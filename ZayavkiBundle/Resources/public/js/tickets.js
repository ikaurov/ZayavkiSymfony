
function open_ticket(id, status, list, translate) {

    if (status == 2) {
		if (!$('div').is('#closed_card')) {
			$('#cardholder').after('<div id="closed_card"></div>');
		}

		$('#closed_card').dialog({ title: translate['dialog.title.ticket'],  
						width: 600, height: 700, closed: false, cache: false, 
						href: 'ticket_closed/'+id, modal: true,
			            buttons:[ {text: translate['tickets.delete'], id:"btc_delete", handler:function(){ ticket_delete(id, list); } },
								  {text: translate['tickets.print'], handler:function(){ window.open('ticket_print/'+id, "_blank"); } },
								  {text: translate['tickets.closed'],id:"btc_close", handler:function(){  $('#closed_card').dialog('close'); } }],
						onBeforeOpen:function(){
							if(this.head == 0) {
								$('#btc_delete').css("display","none");
							}
							else {
								$('#btc_delete').css("float","left");
							}
							$('#closed_card').dialog('dialog').attr('tabIndex','-1').bind('keydown',function(e){
								if (e.keyCode == 27){
									$('#closed_card').dialog('close');
								}
							});					
							return true;
						}								  
		});
    }
    else {
		if (!$('div').is('#ticket_card')) {
			$('#cardholder').after('<div id="ticket_card"></div>');
		}	
	
		var client_h = ((document.body.clientHeight > 720) ? 720 : document.body.clientHeight - 200);		
		// старое значение: 760
		$('#ticket_card').dialog({
			title: translate['dialog.title.ticket'],
			left: 100,
			top: 10,
			width: 680,
			height: client_h,
			closed: false,
			resizable: true,
			cache: false,
			href: 'ticket_id/'+id,
			modal: true,
			buttons:[ {text:translate['tickets.delete'],id:"b_dlg_delete",
				handler:function(){ 
					ticket_delete(id, list);
             	}
            },
            {text:translate['tickets.ok'],id:"b_dlg_save",
   		        handler:function(){
                    var tsgid    = $('#ticket_tsgid').val();
                    var categ    = $('#ticket_categoryid').val();
					var msg      = $('#ticket_message').val();
          	        $('#formcard_ticket').form('submit', {
        		        url: 'ticket_save/'+id,
		                onSubmit: function(){
	                  		if (tsgid == 0) {
	                   			$.messager.alert( translate['tickets.warning'], translate['tickets.message.selectcompany'], "info");
	                   			return false;
	                   		}
	                   		if (categ == 0) {
	                   			$.messager.alert(translate['tickets.warning'], translate['tickets.message.category'], "info");
	                   			return false;
	                   		}
	                   		if (msg.length == 0) {
	                  			$.messager.alert(translate['tickets.warning'], translate['tickets.message.selecttext'], "info");
	                  			return false;
	                   		}
        		            return true;
                	    },
                        success:function(data){
	                      	var data = eval('(' + data + ')');  // change the JSON string to javascript object
		                    if (data.success){
		                          	if (data.id > 0) {
			                            $.messager.alert(translate['tickets.warning'], data.message, 'info');
			                        }
									if (list) {
										list.datagrid('reload');
									}
    			                    $('#ticket_card').dialog('close');
            		        }   else {
                    		        $.messager.alert(translate['tickets.warning'], data.message, 'info');
                        	}
                        }
                    });
   		    	}
   			},
	   	    {text:translate['tickets.cancel'],id:"b_dlg_cancel",  handler:function(){ $('#ticket_card').dialog('close'); } }
	   		],
		   	onBeforeOpen:function(){
				$('#b_dlg_save').linkbutton({text: ((id == 0)?translate['tickets.create']:translate['tickets.ok'])});

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

function ticket_delete(id, list) 
{
	$.messager.defaults.ok =  translate['tickets.yes'];
    $.messager.defaults.cancel = translate['tickets.no'];
    $.messager.confirm( translate['tickets.warning'], translate['tickets.message.delete'], function(r){
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
