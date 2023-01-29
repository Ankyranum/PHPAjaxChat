
<?php 
include ('veritabanibaglanti.php');

session_start();

if(!isset($_SESSION['user_id']))
{
 header("location:login.php");
}

?>

<html>  
    <head>  
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    </head>  
    <body style="background-color: lightgray;">  
        <div class="container">
   <br />
   
   <div class="table-responsive">
   	<div class="jumbotron" style="width: 750px; height: 500px; margin: auto; margin-top: 50px; border-radius: 15px; 
   	box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
	-webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
	-moz-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
    <h2 align="center">Sohbet için aktif kullanıcılar</h2><br /><br /><br>
    <br>

    <div class="col-md-2 col-sm-3">
    <input type="hidden" id="is_active_group_chat_window" value="no"/>
	<button type="button" name="group_chat" id="group_chat" class="btn btn-warning btn-xs"><i class="fas fa-users"></i> Grup Sohbeti</button></div>

    <p align="right">Hoşgeldin  <?php echo $_SESSION['username'];  ?> <span class="label label-dark" style="padding: 4px;"> <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a></span></p><br>

    <div id="user_details"></div>
    <div id="user_model_details"></div>
   </div>
  </div>
	</div>
    </body>  
</html>  

<div id="group_chat_dialog" title="Gurup Sohbeti">
 <div id="group_chat_history" style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px;">

 </div>
 <div class="form-group">
  <textarea name="group_chat_message" id="group_chat_message" class="form-control"></textarea>
 </div>
 <div class="form-group" align="right">
  <button type="button" name="send_group_chat" id="send_group_chat" class="btn btn-info"><i class="far fa-paper-plane"></i> Gönder</button>
 </div>
</div>


<script>  
	$(document).ready(function(){

	 fetch_user();

	 setInterval(function(){
	  update_last_activity();
	  fetch_user();
	  update_chat_history_data();
	 }, 5000);

	 function fetch_user()
	 {
	  $.ajax({
	   url:"fetch_user.php",
	   method:"POST",
	   success:function(data){
	    $('#user_details').html(data);
	   }
	  })
	 }

	 function update_last_activity()
	 {
	  $.ajax({
	   url:"update_last_activity.php",
	   success:function()
	   {

	   }
	  })
	 }

	 function make_chat_dialog_box(to_user_id, to_user_name)
	 {
	  var modal_content = '<div id="user_dialog_'+to_user_id+'" class="user_dialog" title="'+to_user_name+' kişisiyle konuşuyorsun">';
	  modal_content += '<div style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px ;background-color:lightgray; padding:16px;" class="chat_history" data-touserid="'+to_user_id+'" id="chat_history_'+to_user_id+'">';
	  modal_content += fetch_user_chat_history(to_user_id);
	  modal_content += '</div>';
	  modal_content += '<div class="form-group">';
	  modal_content += '<textarea name="chat_message_'+to_user_id+'" id="chat_message_'+to_user_id+'" class="form-control"></textarea>';
	  modal_content += '</div><div class="form-group" align="right">';
	  modal_content+= '<button type="button" name="send_chat" id="'+to_user_id+'" class="btn btn-info send_chat"><i class="far fa-paper-plane"></i> Gönder</button></div></div>';
	  $('#user_model_details').html(modal_content);
	 }

	 $(document).on('click', '.start_chat', function(){
	  var to_user_id = $(this).data('touserid');
	  var to_user_name = $(this).data('tousername');
	  make_chat_dialog_box(to_user_id, to_user_name);
	  $("#user_dialog_"+to_user_id).dialog({
	   autoOpen:false,
	   width:400
	  });
	  $('#user_dialog_'+to_user_id).dialog('open');
	 });

	 $(document).on('click', '.send_chat', function(){
	  var to_user_id = $(this).attr('id');
	  var chat_message = $('#chat_message_'+to_user_id).val();
	  $.ajax({
	   url:"insert_chat.php",
	   method:"POST",
	   data:{to_user_id:to_user_id, chat_message:chat_message},
	   success:function(data)
	   {
	    $('#chat_message_'+to_user_id).val('');
	    $('#chat_history_'+to_user_id).html(data);
	   }
	  })
	 });
	 
	 function fetch_user_chat_history(to_user_id)
	 {
	  $.ajax({
	   url:"fetch_user_chat_history.php",
	   method:"POST",
	   data:{to_user_id:to_user_id},
	   success:function(data){
	    $('#chat_history_'+to_user_id).html(data);
	   }
	  })
	 }

	 function update_chat_history_data()
	 {
	  $('.chat_history').each(function(){
	   var to_user_id = $(this).data('touserid');
	   fetch_user_chat_history(to_user_id);
	  });
	 }

	$('#group_chat_dialog').dialog({
		autoOpen:false,
		width:400
	});

	$('#group_chat').click(function(){
		$('#group_chat_dialog').dialog('open');
		$('#is_active_group_chat_window').val('yes');
		fetch_group_chat_history();
	});

	$('#send_group_chat').click(function(){
	 var chat_message = $('#group_chat_message').val();
	 var action = 'insert_data';
	 if(chat_message != '')
	 {
	  $.ajax({
	   url:"group_chat.php",
	   method:"POST",
	   data:{chat_message:chat_message, action:action},
	   success:function(data){
	    $('#group_chat_message').val('');
	    $('#group_chat_history').html(data);
	   }
	  })
	 }
	});

	setInterval(function(){
 update_last_activity();
 fetch_user();
 update_chat_history_data();
 fetch_group_chat_history();
}, 5000);

	function fetch_group_chat_history()
	{
		var group_chat_dialog_active = $('#is_active_group_chat_window').val();
		var action = "fetch_data";
		if(group_chat_dialog_active == 'yes')
		{
			$.ajax({
				url:"group_chat.php",
				method:"POST",
				data:{action:action},
				success:function(data)
				{
					$('#group_chat_history').html(data);
				}
			})
		}
	}

	$(document).on('click', '.remove_chat', function(){
  var chat_message_id = $(this).attr('id');
  if(confirm("Mesajı silmek istediğinize emin misiniz?"))
  {
   $.ajax({
    url:"remove_chat.php",
    method:"POST",
    data:{chat_message_id:chat_message_id},
    success:function(data)
    {
     update_chat_history_data();
    }
   })
  }
 });

	});  
</script>
