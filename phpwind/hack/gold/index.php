<!--********************************
���޸���Ŀ�����ֱ���88,104,105���޸�ʱ�ο�ע��
**********************************-->
<?php
!function_exists('readover') && exit('Forbidden');
?>
<div id="breadCrumb">
		<img src="<?php echo $imgpath?>/<?php echo $stylepath?>/thread/home-old.gif" align="absmiddle" />&nbsp;<a href="<?php echo $db_bfn?>" title="<?php echo $db_bbsname?>"><?php echo $db_bbsname?></a> &raquo; <a href="hack.php?H_name=gold">���ƽ�󳡡�</a>
</div>
<div class="t"><table width="100%" align="center" cellspacing="0" cellpadding="0">
<tr><td class="h"><b>�Խ𵼺��� <a href="hack.php?H_name=gold">���ƽ�󳡡�</a>
<a href="hack.php?H_name=stor">���Խ���ҵ�֡�</a>
</b></td></tr>
<tr class="tr3 f_one trbor"><td valign="top">
<?php
$nname=$windid;
$ngid=$winduid;
$groupid == 'guest' && Showmsg('not_login');

//$db->free_result();
class gold{
    private $name1;
    private $gid;
	private $hrow;
	private $vrow;
	private $gcid;
	public $oc;


	public function __construct($ngid,$nname,$db){
		$this->gid=$ngid;
		$this->name1=$nname;
		$this->db=$db;
	}

	function getcid(){
		$query2 = "SELECT goldcid,oc FROM pw_goldadmin where id='0'";
	    $result2 = $this->db->query($query2);
	    $row2=$this->db->fetch_array($result2);
    	$this->gcid=$row2['goldcid'];
		$this->oc=$row2['oc'];
		return $this->gcid;
	}

	function getpoint(){
		$query = "SELECT * FROM pw_gold where id='$this->gid' ";
		if($result = $this->db->query($query)){
		$this->hrow=$this->db->fetch_array($result);
		}
		else{
		echo"ȡ�÷���ʧ��";
		}
	}

    function getvalue(){
		//$this->getcid();
		$vquery = "SELECT value FROM pw_membercredit where uid='$this->gid' and cid='$this->gcid'";
		if($vresult = $this->db->query($vquery)){
		$this->vrow=$this->db->fetch_array($vresult);
		}
		else{
		echo"ȡ�÷���ʧ��";
		}
	}

	function inset_first_value(){
		//$this->getcid();
		$this->getvalue();
		if($this->gcid=='')
	      {
			echo"<p class=\"undefine\">BBS��̨��δ�����Ϸ�Զ�����֣��޷�����������а�����ϵ����Ա</p>";
	      }
		elseif($this->vrow['value']=='' and $this->name1 != '')
		  {
			$value=1; //�����״ν����Խ�ĳ�ʼ�ƽ�������������PK���������PK����һ����
			$vsql = "INSERT INTO pw_membercredit (uid,cid,value) VALUES('$this->gid','$this->gcid','$value')";
			$this->db->query($vsql);
		  }
	}


	function inset_first_point(){
		//$this->getcid();
		$this->getpoint();

		if($this->hrow['name']=='' and $this->name1 !='' and $this->hrow['score']=='' and $this->hrow['allscore']=='' and $this->hrow['money']=='')
		  {
			$gscore=0;
			$gallscore=0;
			$gtool=10;  //�����״ν����Խ�ĳ�ʼ�����;�ֵ
			$ghealth=60; //�����״ν����Խ�ĳ�ʼ����ֵ
			$msg='�����Խ���~��';
			$time=date("YmdHis");
			$sql2 = "INSERT INTO pw_gold (id,name,toolname,tool,health,score,allscore,msg,time) VALUES('$this->gid','$this->name1','ľ��','$gtool','$ghealth','$gscore','$gallscore','$msg','$time')";
			if($this->db->query($sql2))
			  {
				//echo"��ӳɹ�";
			  }
			  else
			  {
				  echo"���ʧ��";
			  }
          }
	}


	function putout_user_msg(){
		$this->getvalue();
		$this->getpoint();
	$sscore=$this->hrow['score'];
	$allsscore=$this->hrow['allscore'];
	$health=$this->hrow['health'];
	$tool=$this->hrow['tool'];
	$toolname=$this->hrow['toolname'];
	$level=ceil($sscore/10000);
	switch($sscore){
			case  $sscore <=7000:
				$dj='Сʯ��';
			break;
			case  $sscore <=14000:
				$dj='����ʯ';
			break;
			case  $sscore <=21000:
				$dj='ʯӢʯ';
			break;
			case  $sscore <=28000:
				$dj='������';
			break;
			case  $sscore <=35000:
				$dj='����';
			break;
			case  $sscore <=42000:
				$dj='���';
			break;
			case  $sscore <=49000:
				$dj='���';
			break;
			case  $sscore <=56000:
				$dj='��ש';
			break;
			case  $sscore <=63000:
				$dj='����';
			break;
			case  $sscore <=170000:
				$dj='���ʯ';
			break;
		}

	echo"<div class=\"user123\">�Խ��ߣ�{$this->name1} &nbsp;&nbsp;��߽�������{$sscore}����&nbsp;&nbsp;��󴢱�����{$allsscore}����&nbsp;&nbsp;�ȼ���{$level}��{$dj}&nbsp;&nbsp;������{$health}ţ&nbsp;&nbsp;���ߣ�{$toolname}&nbsp;&nbsp;�;öȣ�{$tool}��</div>";
	}

	function tongbu(){
		$this->getvalue();
		$this->getpoint();
		if($this->hrow['money'] != $this->vrow['value']){
			$updatamoney=$this->vrow['value'];
			$uexecl2="update pw_gold set money='$updatamoney' where id='$this->gid'";
			$this->db->query($uexecl2);
		}
	}



	function toppoint($creditdb){
		$this->tongbu();
		$sqlscore = "SELECT name,score FROM pw_gold  ORDER BY score DESC  LIMIT 0 , 10 ";
		$resultscore = $this->db->query($sqlscore);
		echo"<div class=\"toparae\">";
		echo"<div class=\"topgold\"> <h3>�Խ�������а�</h3><br>";
		while($result=$this->db->fetch_array($resultscore)){
			$user1=$result['name'];
			$score=$result['score'];
			echo" <span>{$user1}</span>����� $score ����<img src=\".\hack\gold\image\gold.jpg\"><br>";
		}
		echo"</div>";


			$sqlallscore = "SELECT name,money FROM pw_gold  ORDER BY money DESC  LIMIT 0 , 10";
			$resultallscore = $this->db->query($sqlallscore);
			echo"<div class=\"topallgold\"> <h3>�Խ������а�</h3><br>";
			while($resultall=$this->db->fetch_array($resultallscore))
			{
				$user2=$resultall['name'];
				$gmoney=$resultall['money'];
				echo"<span>{$user2}</span> ��$creditdb[name] $gmoney $creditdb[unit]<img src=\".\hack\gold\image\gold.jpg\"><br>";
			}
			echo"</div></div>";
	}


}


$a=new gold($ngid,$nname,$db);
$tcid=$a->getcid();
$creditdb=$db->get_one("SELECT * FROM pw_credits WHERE cid=".pwEscape($tcid));
//$creditdb['name']
$a->oc==0 && showmsg('����ѹر�');
$a->inset_first_value();
$a->inset_first_point();
$wquery = "SELECT health,tool,toolname FROM pw_gold where id='$ngid' ";
$wresult = $db->query($wquery);
$wrow=$db->fetch_array($wresult);
if($wrow['health']<5 or $wrow['toolname']==''){
	showmsg("���Ѿ�������֧�򹤾��������𣬲������ڽ��ˣ�<a href=hack.php?H_name=stor >�Խ���ҵ��</a>תת�ɣ�");
}else{
	$a->putout_user_msg();
	$ntoolname=$wrow['toolname'];
	switch($ntoolname){
		case ľ�� : $swf='kg1.swf';break;
		case ���� : $swf='kg2.swf';break;
		case ͭ�� : $swf='kg3.swf';break;
		case ���� : $swf='kg4.swf';break;
		case ��� : $swf='kg5.swf';break;
		default : $swf='kg1.swf';
	}
?>

<div class="game">
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http:fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="500" height="365">
<param name="allowScriptAccess" value="sameDomain"><param name="movie" value=<?php echo"$swf";?>><param name="quality" value="high"><param name="bgcolor" value="#FFE5BC">
<param name="menu" value="false"><param name=wmode value="Window">
<embed src=<?php echo"$swf";?> wmode="opaque"  menu="false" bgcolor="#F0F0F0" quality="high" width="500" height="365" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http:www.macromedia.com/go/getflashplayer" />
</object>
</div>
<?php
	$a->toppoint($creditdb);
}
require_once PrintHack('index');
echo('</td></tr></table></div>');
footer();
?>
