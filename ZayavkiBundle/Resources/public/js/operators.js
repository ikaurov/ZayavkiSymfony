function open_operator(id, list) {

	// list = list to refresh after 
	if (!$('div').is('#operator_card')) {
		$('#cardholder').after('<div id="operator_card"></div>');
	}
	
    $('#operator_card').dialog({
		title: 'Карточка пользователя',
        width: 600,
        height: 400,
        closed: false,
        cache: false,
        href: 'operators_id/'+id,
        modal: true,
        buttons:[  {text:'Сохранить',
   		              handler:function(){
                        var name = document.getElementById("operator_name").value;
                        var log  = document.getElementById("operator_login").value;
                        var pass = document.getElementById("operator_password").value;
                        var ak   = 0; 

                        var tsgs = $('#u_tsgs').datagrid('getData');
                        var tt = ';';
                        tsgs.rows.forEach(function(entry) {
							tt = tt + entry.id+';';
						});
						$('#operator_rem').val(tt);
 	                    $('#formcard_oper').form('submit', {
        		            url:'operators_id/'+id+'/'+tt+'',
		                    onSubmit: function(){
		                       	if (name.length == 0) {
		                       		$.messager.alert('Внимание !', 'Имя пользователя не может быть пустым', 'info');
		                       		return false;
		                       	}

		                       	if (log.length == 0) {
		                       		$.messager.alert('Внимание !', 'Логин не может быть пустым', 'info');
		                       		return false;
		                       	}

		                       	if (pass.length == 0) {
		                       		$.messager.alert('Внимание !', 'Пароль не может быть пустым', 'info');
		                       		return false;
		                       	}

								var filter  = /^[a-zA-Z0-9 \u0430-\u044F\u0410-\u042F]+$/;

								if (!filter.test(log)) {
								   	$.messager.alert('Внимание !', 'Логин содержит недопустимые символы', 'info');
								   	return false;
								}

						        if (!filter.test(pass)) {
						        	$.messager.alert('Внимание !', 'Пароль содержит недопустимые символы', 'info');
						        	return false;
						        }

   		                        return true;
          		            },
                        	success:function(data){
	                          	var data = eval('(' + data + ')'); 
		                        if (data.success){
									if (list) {
										list.treegrid('reload');
									}
    			                    $('#operator_card').dialog('close');
            		            } else {
                    		        $.messager.alert('Внимание', data.message, 'info');
                        		}
                            }
                       	});
   		    		}
				},
   	     		{text:'Отменить',  handler:function(){ $('#operator_card').dialog('close'); } }
   	     ],
		onBeforeOpen:function(){
			$('#operator_card').dialog('dialog').attr('tabIndex','-1').bind('keydown',function(e){
				if (e.keyCode == 27){
					$('#operator_card').dialog('close');
				}
			});		
		}		 	 
    });
}