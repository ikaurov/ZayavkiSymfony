//
// @param id   - category id
// @param list - link obj to parent list

function open_categ(id, list) {
	// list = list to refresh after 
	if (!$('div').is('#cat_card')) {
		$('#cardholder').after('<div id="cat_card"></div>');
	}
	
  	$('#cat_card').dialog({
		title: 'Карточка категории',
        height: 170,			
        width: 600,
        closed: false,
        cache: false,
        href: 'categories_id/'+id,
        modal: true,			
        buttons:[  {text:'Сохранить',
					handler:function(){
                        var gid  = $('#category_parentid').val();
                        var name = fx.strCheck($('#category_name').val());					
 	                    $('#formcard_cat').form('submit', {
        		            url:'categories_save/'+id,
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
									//$('#categs_list').treegrid('reload');
									if (list) {
										list.treegrid('reload');
									}
    			                    $('#cat_card').dialog('close');
            		            }  else {
                    		        $.messager.alert('Внимание !', data.message, 'info');
                        		}
							}
                    	});
   		    		}
   			       },
   	    {text:'Отменить',  handler:function(){ $('#cat_card').dialog('close'); } } ],
		onBeforeOpen:function(){
			$('#cat_card').dialog('dialog').attr('tabIndex','-1').bind('keydown',function(e){
				if (e.keyCode == 27){
					$('#cat_card').dialog('close');
				}
			});		
		}
    });
}


