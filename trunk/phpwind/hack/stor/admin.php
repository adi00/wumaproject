<?php
!function_exists('adminmsg') && exit('Forbidden');
include(D_P.'data/bbscache/bk_config.php');
$admincode=addslashes($_POST['pkaction']);
if($admincode=="����Ա����")
{
	$pkopen=addslashes($_POST['pkopen']);
	$goldcid=addslashes($_POST['goldcid']);
	$pksearch = "SELECT * FROM pw_credits where cid='$goldcid'";
    $pkrow = $db->get_one($pksearch);
	if(empty($pkrow)) {
		adminmsg('����δ��Ӵ˻���');
	} else {
		$execl2="update pw_goldadmin set oc='$pkopen' ,goldcid='$goldcid'  where id='0'";
		$db->update($execl2);
		adminmsg('���óɹ�');
	}
}
$pksearch = "SELECT * FROM pw_goldadmin where id='0'";
$pkresult = $db->query($pksearch);
$pkrow=$db->fetch_array($pkresult);
$spkopen=$pkrow['oc'];
if($spkopen==0){
	$pkcheck_0="checked";
}elseif($spkopen==1){
	$pkcheck_1="checked";
}
$spknpcuid=$pkrow['uid'];
$goldcid=$pkrow['goldcid'];
include PrintHack('admin');
?>