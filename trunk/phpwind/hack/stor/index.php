<!--********************************
���޸���Ŀ�����ֱ���70��117�У������޸�stor\template\index.htm���ǰ̨��ʾ��
(147��153��162)��3����ͬ���޸ģ������޸�stor\template\index.htm���ǰ̨��ʾ��3000�����޸�ʱ�ο�ע��
**********************************-->
<?php
!function_exists('readover') && exit('Forbidden');
?>
<div id="breadCrumb">
		<img src="<?php echo $imgpath?>/<?php echo $stylepath?>/thread/home-old.gif" align="absmiddle" />&nbsp;<a href="<?php echo $db_bfn?>" title="<?php echo $db_bbsname?>"><?php echo $db_bbsname?></a> &raquo; <a href="hack.php?H_name=stor">���Խ���ҵ�֡�</a>
</div>
<div class="t"><table width="100%" align="center" cellspacing="0" cellpadding="0">
<tr><td class="h"><b>�Խ𵼺��� <a href="hack.php?H_name=gold">���ƽ�󳡡�</a>
<a href="hack.php?H_name=stor">���Խ���ҵ�֡�</a>
</b></td></tr>
<tr class="tr3 f_one trbor"><td valign="top">
<?php
require_once(D_P.'require/msg.php');
$groupid == 'guest' && Showmsg('not_login');
InitGP(array('repair'));
$tname=$windid;
$tgid=$winduid;
session_start();
$gss=$_SESSION['count'];
$ss=addslashes($_POST['sjss']);
empty($ss) && $ss=rand(a,z);
unset($_SESSION['count']);
$gettool=addslashes($_POST['tool']);
$getchangegold=addslashes($_POST['dhgold']);
$getmsg=substr(htmlspecialchars(addslashes($_POST['msg'])),0,50);
$repairid=addslashes($_POST['repairid']);
$help=addslashes($_POST['help']);
class tool	{
	private $tname;
	private $tgid;
	private $gettool;
	private $getchangegold;
	private $ss;
	private $tcid;
	private $tool;
	private $food;
	private $money;
	private $row;
	private $row2;
	//private $repairname;
	private $value;
	private $getmsg;
	private $repairid;
	private $help;
	private $row4;
	public $showvalue;
	public $showallscore;
	public $gtool;
	public $showtool;
	public $showfood;
	public $showhealth;
	public $oc;

	public function __construct($tname,$tgid,$gettool,$getchangegold,$getmsg,$repairid,$help,$db){
		$this->tgid=$tgid;
		$this->tname=$tname;
		$this->gettool=$gettool;
		$this->getmsg=$getmsg;
		$this->repairid=$repairid;
		$this->help=$help;
		$this->db=$db;
		$this->getchangegold=$getchangegold;
	}

	function repair()
		{
		$query = "SELECT name,health,tool,toolname FROM pw_gold where id='$this->repairid'";
		$result = $this->db->query($query);
		$this->row4=$this->db->fetch_array($result);
		//$this->repairname=$this->row4['toolname'];
		//$this->repairhp=$row4['health'];
		}

	function updatamsg(){
		 $time=date("YmdHis");
		 $ntime=date("Y-m-d");
		if($this->help=="eat"){
		   $this->things;
		   if($this->row4['health']>50)
			{showmsg('�������Ŀǰ��������лл��ĺ��⣡');}
		   elseif($this->row['foodname']=='')
			{showmsg('��û��ʳ����Ծȼ�������ѣ��Ͻ�ȥ��ɣ�');}
		   else
			{
			$msg="$this->tname ���ҳ���һ��$this->showfood ��̫��л���ˣ� �� $ntime";
			$health=$this->food;
			$mexecl="update pw_gold set msg='$msg',health='$health',time='$time' where id='$this->repairid' ";
			$this->db->query($mexecl);
			$mexecl="update pw_gold set foodname='' where id='$this->tgid' ";
			$this->db->query($mexecl);
			pwSendMsg(array('toUser'=>$this->row4[name],'fromUid'=>$this->tgid,'fromUser'=>$this->tname,'subject'=>'�Խ���Ϣ','content'=>$msg)) ;
			}
		}elseif($this->help=="mend") {
			$this->things();
			 if($this->row4['tool']>10)
			{showmsg('������ѹ���Ŀǰ����Ҫ����лл��ĺ��⣡');}
			 elseif($this->row4['toolname']=='')
			{showmsg('�������û�й��ߣ��޷�����');}
		   else
			{
			   $tool=$this->tool-$this->row4['tool'];
			   //$jjhealth=$tool*2;
			   $jhealth=$this->row['health']-$tool;
			   if($jhealth>0)
				{
			   $xltoolname=$this->row4['toolname'];
			$msg="$this->tname ������$tool ����������������$xltoolname ��̫��л���ˣ� �� $ntime";
			$mexecl="update pw_gold set msg='$msg',tool='$tool',time='$time' where id='$this->repairid' ";
			$this->db->query($mexecl);
			$mexecl="update pw_gold set health='$jhealth' where id='$this->tgid' ";
			$this->db->query($mexecl);
			pwSendMsg(array('toUser'=>$this->row4[name],'fromUid'=>$this->tgid,'fromUser'=>$this->tname,'subject'=>'�Խ���Ϣ','content'=>$msg)) ;
				}
				else
				{showmsg('������������԰���������');}
			}
		}else{
			$thetime=$time-$this->row['time'];
			$stime=60-$thetime;
			if($thetime>60)	{
				$this->getmsg();
				$health=$this->row['health']-1;
				$thismsg=$this->getmsg.' �� '.$ntime;
				$mexecl="update pw_gold set msg='$thismsg',health='$health',time='$time' where id='$this->tgid'";
				$this->db->query($mexecl);
			}else{
				echo"<div class=\"nomoney\"><span>��ʾ��Ϣ��</span><span class=\"buy\">����Ϣ{$stime}������ź���</span></div>";
			}
		}
	}

	function getcid(){
		$query3 = "SELECT goldcid,oc FROM pw_goldadmin where id='0'";
		$result3 = $this->db->query($query3);
		$row3=$this->db->fetch_array($result3);
		$this->tcid=$row3['goldcid'];
		$this->oc=$row3['oc'];
		return $this->tcid;
	}

	function things(){
		$this->getmsg();
		$this->repair();
		if($this->gettool!='') $thing = $this->gettool;
		elseif($this->help=="eat") $thing=$this->showfood;
		elseif($this->help=="mend") $thing=$this->row4['toolname'];
		switch ($thing){
			case  "ľ��":
			$this->tool = 20;  //����ľ���Ч����20��ʾ��20�����;ã�
			$this->money = 10; //����ľ��ļ۸�10��ʾ�ۼ�10���ƽ�
			break;

			case  "����":
			$this->tool = 20; //ͬ��
			$this->money = 20;//ͬ��
			break;

			case  "ͭ��":
			$this->tool = 20;//ͬ��
			$this->money = 250;//ͬ��
			break;

			case  "����":
			$this->tool = 20;//ͬ��
			$this->money = 1000;//ͬ��
			break;

			case  "���":
			$this->tool = 20;//ͬ��
			$this->money = 5000;//ͬ��
			break;

			case  "����":
			$this->food = 100;//�������ɵ�Ч����100��ʾ��100������
			$this->money = 10; //�������ɵļ۸�10��ʾ�ۼ�10���ƽ�
			break;

			case  "���׷�":
			$this->food = 200;//ͬ��
			$this->money = 20;//ͬ��
			break;

			case  "������":
			$this->food = 300;//ͬ��
			$this->money = 30;//ͬ��
			break;

			case  "�ǽ���":
			$this->food = 400;//ͬ��
			$this->money = 40;//ͬ��
			break;

			case  "��ԭ���":
			$this->food = 500;//ͬ��
			$this->money = 50;//ͬ��
			break;

			}
		}

	function getscore(){

		$this->getcid();
		$this->things();
		$query2 = "SELECT value FROM pw_membercredit where uid='$this->tgid' and cid='$this->tcid'";
		$result2 = $this->db->query($query2);
		$this->row2=$this->db->fetch_array($result2);
		$this->showvalue=$this->row2['value'];
	}

	function getmsg(){
		$query = "SELECT health,foodname,toolname,tool,allscore,time FROM pw_gold where id='$this->tgid' ";
		$result = $this->db->query($query);
		$this->row=$this->db->fetch_array($result);
		$this->showallscore=$this->row['allscore'];
		$this->showtool=$this->row['toolname'];
		$this->gtool=$this->row['tool'];
		$this->showhealth=$this->row['health'];
		$this->showfood=$this->row['foodname'];
	}

	function chggold($creditdb){
		$this->getscore();
		$this->getmsg();
		if($this->row['allscore']<3000)  //�˴�С��10000��ʾ���ܽ��С��10000���޷������ƽ����޸ģ�����2��10000Ҳͬ���޸�
		{
			echo"<div class=\"nomoney\">��ʾ��Ϣ����Ľ���������㣬�޷�������</div>";
		}
		elseif($this->getchangegold == "����" and $this->row['allscore'] != 0)
		{
			$jt = round($this->row['allscore'] / 3000,0);  //�˴���10000�Ǽ����������ƽ�ı���Ϊ1��10000
			$updatagold = $jt + $this->row2['value'] ;
			$mexecl="update pw_gold set money='$updatagold' where id='$this->tgid' ";
			$this->db->query($mexecl);

			$uexecl="update pw_membercredit set value='$updatagold' where uid='$this->tgid' and cid='$this->tcid' ";
			if($this->db->query($uexecl))
			{

				$reducegold = $this->row['allscore']-($jt * 3000); //�˴�10000�Ǹ��������Ļƽ���������Ӧ�۳��Ľ������
				$reexecl="update pw_gold set allscore='$reducegold' where id='$this->tgid'";
				if($this->db->query($reexecl))
				{
					echo"<div class=\"nomoney\">��ʾ��Ϣ���ɹ������� $jt $creditdb[name]</div>";
				}
				else
				{
					echo"$creditdb[name]����ʧ�ܣ�";
				}
			}
			else
			{
				echo"$creditdb[name]����ʧ�ܣ�";
			}
		}

	}


	function updatatool(){
		$this->getmsg();
		$this->getscore();
		if($this->row2['value'] >= $this->money ){
			if($this->food!='')	{
				if($this->showfood!=''){
					echo"<div class=\"nomoney\"><span>��ʾ��Ϣ��</span><span class=\"buy\">���{$this->showfood}��û���꣬����ϧ��ʳ</span></div>";
				}else{
					$hexecl="update pw_gold set foodname='$this->gettool',health='$this->food' where id='$this->tgid'";
				}
			}elseif($this->tool!=''){
				if($this->showtool!='')	{
					echo"<div class=\"nomoney\"><span>��ʾ��Ϣ��</span><span class=\"buy\">���{$this->showtool}��û���꣬���Լ��Դ</span></div>";
				}else{
					$hexecl="update pw_gold set toolname='$this->gettool',tool='$this->tool' where id='$this->tgid'";
				}
			}
			if(!empty($hexecl) && $this->db->query($hexecl)){
				$this->updatascore();
				echo"<span>��Ʒ��Ϣ��</span><span class=\"buy\">���� $this->gettool �ɹ�</span></div>";
			}else{
				echo"<span class=\"buy\">���� $this->gettool ʧ��</span>";
			}
		}else{
			echo"<div class=\"nomoney\">��ʾ��Ϣ������ֽ��㣡</div>";
			//showmsg("�������");
		}
	}


	function updatascore($creditdb){
		global $creditdb;

		$this->row2['value'] = $this->row2['value'] - $this->money;
		$costmoney=$this->row2['value'];
		$sexecl="update pw_membercredit set value='$costmoney'  where uid='$this->tgid' and cid='$this->tcid'";
		$this->db->query($sexecl);
		$gexecl="update pw_gold set money='$costmoney'  where id='$this->tgid'";
			if($this->db->query($gexecl))
			{
				echo"<div class=\"costmsg\"><span>�ʽ���Ϣ��</span><span class=\"cost\">��֧���� $this->money $creditdb[name]</span>";
			}
			else
			{
				echo"�޷��ύ����";
			}
	}
}
$ch=new tool($tname,$tgid,$gettool,$getchangegold,$getmsg,$repairid,$help,$db);
$tcid=$ch->getcid();
$creditdb=$db->get_one("SELECT * FROM pw_credits WHERE cid=".pwEscape($tcid));
$ch->oc==0 && showmsg('����ѹر�');
if($gss == $ss){
	if($getchangegold!='')	{
		$ch->chggold($creditdb);
	}
	if($gettool!='')	{
		$ch->updatatool();
	}
}

$ch->getscore();
$ch->getmsg();
if($repair==$tgid){showmsg('�㲻�����Լ��Է�����Լ������ߣ�����������Ѱ�æ�ɣ�');}
elseif($getmsg!=''or$help!=''){$ch->updatamsg();}
//$ch->updatamsg();
 $showvalue=$ch->showvalue;
 $showallscore=$ch->showallscore;
 $showtool=$ch->showtool;
 $tool=$ch->gtool;
 $showfood=$ch->showfood;
 $showhealth=$ch->showhealth;
echo"<div class=\"msg\"><span>���ʳ��:$showfood </span><span>����:$showhealth </span><span>��Ĺ���:$showtool </span><span>�;�:$tool </span><span>���:$showallscore ����</span><span>$creditdb[name]:$showvalue $creditdb[unit]</span></div><div class=\"topmsg\">С�򲿹�����:����ϧ���������Լ����Ͷ��ɹ���������ȻҪȥ��̳����׬ȡ���ֻ�$creditdb[name]�ˡ�</div><div class=\"topmsg1\">����������Ҫ��������ֱ�ӵ��������֣�<br><marquee direction=up height=100 scrollamount=1 onmouseover='this.stop()' onmouseout='this.start()'>";
$query = "SELECT id,name,msg,health,tool FROM pw_gold ORDER BY time DESC LIMIT 0 , 30 ";
$result = $db->query($query);
while($row=$db->fetch_array($result)){
	echo"<a href=hack.php?H_name=stor&repair=$row[id]>$row[name]</a>���ź���{$row[msg]}������{$row[health]}�������;�{$row[tool]}��<br>";
}
echo"</marquee></div>";
require_once PrintHack('index');
echo('</td></tr></table></div>');
footer();
?>