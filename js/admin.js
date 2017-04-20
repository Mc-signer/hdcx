$("#submit").click(function(){
	submitbutton();
});


function submitbutton(){
	$.post("php/adminController.php",$("#adminform").serialize(),function(data){
		data=eval("(" + data +")");
		if(data.success){
			window.location.href="admin-view.html";
		}else{
			alert(data.notice);
		}
	});
}

   function keyLogin() {
        if (event.keyCode == 13)
           submitbutton();
    }
