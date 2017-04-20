function addTeam(){
	var json={
		"key":"addTeam",
		"gameId":"1",
		"teamName":"蚂蚁小分队",
		"proName":"蚂蚁聚宝",
		"proIntro":"蚂蚁聚宝",
		"priName":"负责人",
		"priContact":"12233333",
		"teachName":"教师",
		"teachContact":"12321313123",
		"members":[
			{
				"name":"码云",
				"gender":"男",
				"contact":"12345566",
				"insititute":"jisuanji",
				"class":"jike",
				"stunum":"131332",
				"idcard":"56465",
				"email":"asd@qq.com"
			},
			{
				"name":"码云",
				"gender":"男",
				"contact":"12345566",
				"insititute":"jisuanji",
				"class":"jike",
				"stunum":"131332",
				"idcard":"56465",
				"email":"asd@qq.com"
			}
		]
	}
	$.post("php/teamController.php",json,function(data){
		data=eval("("+data+")");
		console.log(data);
	});
}
//通过撤回按钮绑定事件，通过则pass值为1，撤回为-1
function pass(){
	var json={
		"key":"pass",
		"teamId":"6",
		"pass":1
	}
	$.post("php/adminController.php",json,function(data){
		data=eval("("+data+")");
		console.log(data);
	});
}
function editTeam(){
	var json={
		"key":"editTeam",
		"teamId":"4",
		"gameId":"1",
		"teamName":"蚂蚁小分队",
		"proName":"蚂蚁聚宝",
		"proIntro":"蚂蚁聚宝",
		"priName":"负责人",
		"priContact":"12233333",
		"teachName":"教师",
		"teachContact":"12321313123",
		"members":[
			{
				"name":"码云",
				"gender":"男",
				"contact":"12345566",
				"insititute":"jisuanji",
				"class":"jike",
				"stunum":"131332",
				"idcard":"56465",
				"email":"asd@qq.com"
			},
			{
				"name":"码云",
				"gender":"男",
				"contact":"12345566",
				"insititute":"jisuanji",
				"class":"jike",
				"stunum":"131332",
				"idcard":"56465",
				"email":"asd@qq.com"
			}
		]
	}
	$.post("php/teamController.php",json,function(data){
		data=eval("("+data+")");
		console.log(data);
	});
}
function sendGeamId(){
	var json={
		"key":"sendGameId",
		"gameId":"1"
	}
	$.post("php/teamController.php",json,function(data){
		data=eval("("+data+")");
		console.log(data);
	});
}
function sendTeamId(){
	var json={
		"key":"sendTeamId",
		"teamId":"1"
	}
	$.post("php/teamController.php",json,function(data){
		data=eval("("+data+")");
		console.log(data);
	});
}
function getTeams(){
	$.post("php/teamController.php",{"key":"getTeams"},function(data){
		data=eval("("+data+")");
		console.log(data);
	});
}
function getTeam(){
	$.post("php/teamController.php",{"key":"getTeam"},function(data){
		data=eval("("+data+")");
		console.log(data);
	});
}
sendTeamId();
$.post("php/teamController.php",{"key":"getTeam"},function(data){
	data=eval("("+data+")");
	console.log(data);
});