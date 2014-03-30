//
// @param id   - worker id
// @param list - link obj to parent list

function open_worker(tsg, id, list) {
	// list = list to refresh after 
	if (!$('div').is('#worker_card')) {
		$('#cardholder').after('<div id="worker_card"></div>');
	}

  $('#worker_card').dialog({
		title: 'Карточка исполнителя',
        height: 200,			
        width: 600,
        closed: false,
        cache: false,
        href: 'workers_id/'+tsg+'/'+id,
        modal: true,			
        buttons:[  {text:'Сохранить',
					handler:function(){
                        var name = fx.strCheck($('#worker_name').val());					
 	                    $('#formcard_workers').form('submit', {
        		            url:'workers_save/'+tsg+'/'+id,
		                    onSubmit: function(){
                                if (name.length == 0) {
	                      			$.messager.alert("Внимание !", "Название не может быть пустым", "info");
	                       			return false;
                                }
        		                return true;
                		    },
                            success:function(data){
	                          	var data = eval('(' + data + ')');  
		                        if (data.success){
								
									try {
										list.worker_add(data.id); // interface with outer string lit
									}
									catch(err) {
										console.log('must have interface worker_add(id);');
									}
								
    			                    $('#worker_card').dialog('close');
            		            }  else {
                    		        $.messager.alert('Внимание !', data.message, 'info');
                        		}
							}
                    	});
   		    		}
   			       },
   	    {text:'Отменить',  handler:function(){ $('#worker_card').dialog('close'); } } ],
		onBeforeOpen:function(){
			$('#worker_card').dialog('dialog').attr('tabIndex','-1').bind('keydown',function(e){
				if (e.keyCode == 27){
					$('#worker_card').dialog('close');
				}
			});		
		}
    });
}

