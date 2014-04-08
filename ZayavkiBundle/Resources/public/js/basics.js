jQuery(function($){

			fx = {
				"head":0,
				"alertfile":"none",
				"notopen" : 0,
				"root" : "",
				"path" : "",
				"search" : {"predef":0, "period":0,"tsg":0,"status":-1,"categs":-1,"closed":0, "poisk":"", "d1":"","d2":"" },
				"init" : function(head, path, file, menus) {      // initialization
					this.head      = head;
					this.path      = path;
                    this.alertfile = file;

					try {
						var snd = new Audio(this.alertfile); // buffers automatically when created
					}
					catch(err) {
						console.log(err);
					}
					
		            this.align();
		            this.clearFilter();

                     if (this.head == 0) {
                    	 $('#acc_menus').accordion('add', {title: menus['menus.users'], href:"workers", selected: false});
                    	 $('#acc_menus').accordion('add', {title: menus['menus.categories'], href:"categories", selected: false});
	                 }
	                 else {
		                 $('#acc_menus').accordion('add', {title: menus['menus.companies'], href:"tsgs", selected: false});
		                 $('#acc_menus').accordion('add', {title: menus['menus.users'], href:"users", selected: false});
                    	 $('#acc_menus').accordion('add', {title: menus['menus.workers'], href:"workers", selected: false});
                    	 $('#acc_menus').accordion('add', {title: menus['menus.categories'], href:"categories", selected: false});
		                 $('#acc_menus').accordion('add', {title: menus['menus.profs'], href:"profs", selected: false});
	                 }

					var that = this;
					setInterval(function(){ that.getAlerts(1); },60000);

				},
				"align": function () {
            		var client_h=document.body.clientHeight - 85;

		            $('#acc_menus').accordion({
					    height: client_h
					});

     	  			$('#ptitle').panel({
			  			height:30,
		 	  			href : 'title',
					});

     	  			$('#ptickets').panel({
		 			   	href : 'tickets',
		 	  			height: client_h
					});

				},
				"playSound" : function() { // сигнал о неоткрытых заявках
					console.log('load audio');
					document.getElementById("sound").innerHTML='<audio autoplay="autoplay"><source src="' + this.alertfile +
					   											'.mp3" type="audio/mpeg" /><source src="' + this.alertfile +
					   											'.ogg" type="audio/ogg" /><embed hidden="true" autostart="true" loop="false" src="' + this.alertfile +'.mp3" /></audio>';
				},
				"filterInfo": function() {
				
					if (this.search["d1"].length < 10) {
						var cd = new Date();
						var mm = cd.getMonth()+1;
						var yy = cd.getFullYear();

						if(mm<10){mm="0"+mm};
						this.search["d1"]     = "01."+mm+"."+yy;
					}					
					
					if (this.search["d2"].length < 10) {
						var cd = new Date();
						var mm = cd.getMonth()+1;
						var dd = cd.getDate();
						var yy = cd.getFullYear();
						if(dd < 10){dd="0"+dd};
						if(mm<10){mm="0"+mm};
						this.search["d2"]     = dd+"."+mm+"."+yy;					
					}									
				
					$.ajax({
				       	type: "GET",
						url: 'filter_info/'+this.buildPar(),
					    dataType: "json",
				      	success: function(data) {
				          		var data = eval(data);  // change the JSON string to javascript object
								$("#f_dop").html(data.message);
			           	}
					});
				},
				"clearFilter": function() {
					this.search["predef"] = 0;
					this.search["tsg"]    = 0;
					this.search["period"] = 0;
					this.search["categs"] = 0;
					this.search["status"] = 0;
					this.search["closed"] = 0;
					this.search["poisk"]  = "";
					this.search["sort"]  = "";
					this.search["order"]  = "";
					

					var cd = new Date();
					var mm = cd.getMonth()+1;
					var yy = cd.getFullYear();

					if(mm<10){mm="0"+mm};
					this.search["d1"]     = "01."+mm+"."+yy;
			        var cd = new Date();
					var mm = cd.getMonth()+1;
					var dd = cd.getDate();
					var yy = cd.getFullYear();
					if(dd < 10){dd="0"+dd};
					if(mm<10){mm="0"+mm};
					this.search["d2"]     = dd+"."+mm+"."+yy;
				},
				"getAlerts" : function(reload) { // сигнал о неоткрытых заявках
					var that = this;
					$.ajax({
	            	type: "GET",
					url: 'alerts',
			        dataType: "json",
			        success: function(data) {
		            	var data = eval(data);  // change the JSON string to javascript object
		          		$("#n_new").html(data.znew);
		          		$("#n_total").html(data.total);
		          		$("#n_alert").html(data.alert);
		          		$("#n_urgent").html(data.urgent);
		          		$("#n_today").html(data.today);

						that.updateTitle(data.fopen);
						if ((that.head == 0)&&(data.ding > 0)) {   // it means dispatcher level
								that.playSound();
     					}
						if (reload == 1) {
							$('#tickets_list').datagrid('reload');
						}
    	            }
					});
				},
				"updateTitle" : function( val ) { // сигнал о неоткрытых заявках
					this.notopen = (this.head == 0 )? val : 0;
					document.title = ((this.notopen != 0) ? '('+this.notopen+') ':'') + 'Диспетчер';
				},
				"setCalendarDate" :	 function (obj, date) {
					//date = dd.mm.yyyy
					if (date.length < 10) {
						return;
					}
					var y = date.substr(6, 4);
					var m = date.substr(3, 2);
					var d = date.substr(0, 2);
									
					var el = obj.datebox('calendar');
					try {
						el.calendar('moveTo', new Date(y, m-1, d));
					}
					catch(err) {
						console.log(err);
					}				
				},				
				"setCalendar" :	 function (obj, y, m, d) {
					var el = obj.datebox('calendar');
					el.calendar('moveTo', new Date(y, m, d));
				},
				"buildPar":function(sort, order) {
					this.search["sort"]  = typeof sort !== 'undefined' ? sort : '';
					this.search["order"] = typeof order !== 'undefined' ? order : '';
					
					return '{"predef":'+this.search["predef"]+', "tsg": '+this.search["tsg"]+',"f_categs": '+this.search["categs"]+',"f_status":'+this.search["status"]+
							',"f_cbperiod":'+this.search["period"]+',"f_d1":"'+this.search["d1"]+'", "f_d2":"'+this.search["d2"]+'","f_closed":'+this.search["closed"]+
							',"f_poisk":"'+encodeURI(this.search["poisk"])+'","sort":"'+this.search["sort"]+'", "order":"'+this.search["order"]+'"}';
				},			
				"setCredit": function(key, value) {
					if(typeof(window.localStorage) != 'undefined'){
						window.localStorage.setItem(key,value);
					}
				},
				"getCredit": function(key) {
					var res = "";
					if(typeof(window.localStorage) != 'undefined'){
						res = window.localStorage.getItem(key);
						
						if (res == 'undefined') {
							res = '';
						}
					}
					return res;
				},
				"setVar": function(key, value) {
					if(typeof(window.sessionStorage) != 'undefined'){
						window.sessionStorage.setItem(key,value);
					}
				},
				"getVar": function(key) {
					var res = "";
					if(typeof(window.sessionStorage) != 'undefined'){
						res = window.sessionStorage.getItem(key);
					}
					return res;
				},
				"openDef": function(id, status) {
					var intervalID = setInterval(function(){
						fx.openTicket(id, status);
						clearInterval(intervalID); },1000);
				},
				"strCheck": function(str) {
                    //                     trim                               remove slash         both quotes as `
					return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '').replace(/[\/]/g,'').replace(/["']/g,'`')
				},
           }//fx
});
