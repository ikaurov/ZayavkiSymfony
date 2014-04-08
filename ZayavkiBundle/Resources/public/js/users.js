function open_user(id, list, translate) {

	// list = list to refresh after 
	if (!$('div').is('#user_card')) {
		$('#cardholder').after('<div id="user_card"></div>');
	}
	
    $('#user_card').dialog({
		title: '{{ translate['dialog.title.user'] }}',
        width: 600,
        height: 400,
        closed: false,
        cache: false,
        href: 'user_id/'+id,
        modal: true,
        buttons:[  {text:'{{ translate['basic.save'] }}',
   		              handler:function(){
                        var name = document.getElementById("user_name").value;
                        var log  = document.getElementById("user_username").value;
                        var pass = document.getElementById("user_password").value;
                        var ak   = 0; 

                        var tsgs = $('#u_tsgs').datagrid('getData');
                        var tt = ';';
                        tsgs.rows.forEach(function(entry) {
							tt = tt + entry.id+';';
						});
						$('#user_rem').val(tt);
 	                    $('#formcard_oper').form('submit', {
        		            url:'user_save/'+id+'/'+tt+'',
		                    onSubmit: function(){
		                       	if (name.length == 0) {
		                       		$.messager.alert('{{ translate['basic.warning'] }}', '{{ translate['message.usernameisempty'] }}', 'info');
		                       		return false;
		                       	}

		                       	if (log.length == 0) {
		                       		$.messager.alert('{{ translate['basic.warning'] }}', '{{ translate['message.loginisempty'] }}', 'info');
		                       		return false;
		                       	}

		                       	if (pass.length == 0) {
		                       		$.messager.alert('{{ translate['basic.warning'] }}', '{{ translate['message.passwordisempty'] }}', 'info');
		                       		return false;
		                       	}

								var filter  = /^[a-zA-Z0-9 \u0430-\u044F\u0410-\u042F]+$/;

								if (!filter.test(log)) {
								   	$.messager.alert('{{ translate['basic.warning'] }}', '{{ translate['message.loginbadsymbol'] }}', 'info');
								   	return false;
								}

						        if (!filter.test(pass)) {
						        	$.messager.alert('{{ translate['basic.warning'] }}', '{{ translate['message.passwordbadsymbol'] }}', 'info');
						        	return false;
						        }

   		                        return true;
          		            },
                        	success:function(data){
	                          	var data = eval('(' + data + ')'); 
		                        if (data.success){
									
									try {
										list.user_add(data.id); // interface with outer string lit
									}
									catch(err) {
										console.log('must have interface user_add(id);');
									}
    			                    $('#user_card').dialog('close');
            		            } else {
                    		        $.messager.alert('{{ translate['basic.warning'] }}', data.message, 'info');
                        		}
                            }
                       	});
   		    		}
				},
   	     		{text:'{{ translate['basic.cancel'] }}',  handler:function(){ $('#user_card').dialog('close'); } }
   	     ],
		onBeforeOpen:function(){
			$('#user_card').dialog('dialog').attr('tabIndex','-1').bind('keydown',function(e){
				if (e.keyCode == 27){
					$('#user_card').dialog('close');
				}
			});		
		}		 	 
    });
}