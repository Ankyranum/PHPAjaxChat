<?php 
	
	include ('veritabanibaglanti.php');

	session_start();

	$query = "
	SELECT * FROM login 
	WHERE user_id != '".$_SESSION['user_id']."' 
	";

	$statement = $connect->prepare($query);

	$statement->execute();

	$result = $statement->fetchAll();

	$output = '
	<table class="table table-bordered table-striped">
	 <tr>
	  <td><b>Kullanıcı</b></td>
	  <td><b>Durum</b></td>
	  <td><b>Eylem</b></td>
	 </tr>
	';

	foreach($result as $row)
	{
	 $status = '';
	 $current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 10 second');
	 $current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
	 $user_last_activity = fetch_user_last_activity($row['user_id'], $connect);
	 if($user_last_activity > $current_timestamp)
	 {
	  $status = '<span class="label label-success"><i class="fas fa-battery-full"></i> Aktif</span>';
	 }
	 else
	 {
	  $status = '<span class="label label-danger"><i class="fas fa-battery-empty"></i> Pasif</span>';
	 }
	 $output .= '
	 <tr>
	  <td>'.$row['username'].' '.count_unseen_message($row['user_id'], $_SESSION['user_id'], $connect).'</td>
	  <td>'.$status.'</td>
	  <td><button type="button" class="btn btn-info btn-xs start_chat" data-touserid="'.$row['user_id'].'" data-tousername="'.$row['username'].'"><i class="fas fa-envelope"></i> Mesajlaş</button></td>
	 </tr>
	 ';
	}

	$output .= '</table>';

	echo $output;

?>