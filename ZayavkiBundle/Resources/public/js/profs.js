//
// @param id   - prof id
// @param list - link obj to parent list
// @param translate - array of messages
function open_prof(id, list, translate) {
	// list = list to refresh after 
	if (!$('div').is('#prof_card')) {
		$('#cardholder').after('<div id="prof_card"></div>');
	}

	$('#prof_card').dialog({
		title: translate['dialog.title.profs'],
        height: 170,			
        width: 600,
        closed: false,
        cache: false,
        href: 'profs_id/'+id,
        modal: true,			
        buttons:[  {text: translate['basic.save'],
					handler:function(){
                        var name = fx.strCheck($('#profs_name').val());					
 	                    $('#formcard_prof').form('submit', {
        		            url:'profs_save/'+id,
		                    onSubmit: function(){
                                if (name.length == 0) {
	                      			$.messager.alert(translate['basic.warning'], translate['message.nameisempty'], "info");
	                       			return false;
                                }
        		                return true;
                		    },
                            success:function(data){
	                          	var data = eval('(' + data + ')');  
		                        if (data.success){
									try {
										list.profs_add(data.id); // interface with outer string lit
									}
									catch(err) {
										console.log('must have interface profs_add(id);');
									}
    			                    $('#prof_card').dialog('close');
            		            }  else {
                    		        $.messager.alert( translate['basic.warning'], data.message, 'info');
                        		}
							}
                    	});
   		    		}
   			       },
			{text: translate['basic.cancel'], 
				handler:function(){ $('#prof_card').dialog('close'); } } ],
			onBeforeOpen:function(){
				$('#prof_card').dialog('dialog').attr('tabIndex','-1').bind('keydown',function(e){
					if (e.keyCode == 27){
						$('#cat_card').dialog('close');
					}
				});		
			}
		});
}


