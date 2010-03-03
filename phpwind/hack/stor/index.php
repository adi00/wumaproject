<!--********************************
可修改项目行数分别在70至117行，并且修改stor\template\index.htm里的前台显示；
(147，153，162)此3处须同步修改，并且修改stor\template\index.htm里的前台显示“3000”，修改时参考注释
**********************************-->
<?php
!function_exists('readover') && exit('Forbidden');
?>
<div id="breadCrumb">
		<img src="<?php echo $imgpath?>/<?php echo $stylepath?>/thread/home-old.gif" align="absmiddle" />&nbsp;<a href="<?php echo $db_bfn?>" title="<?php echo $db_bbsname?>"><?php echo $db_bbsname?></a> &raquo; <a href="hack.php?H_name=stor">『淘金商业街』</a>
</div>
<div class="t"><table width="100%" align="center" cellspacing="0" cellpadding="0">
<tr><td class="h"><b>淘金导航： <a href="hack.php?H_name=gold">『黄金矿场』</a>
<a href="hack.php?H_name=stor">『淘金商业街』</a>
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
			{showmsg('你的朋友目前并不饿，谢谢你的好意！');}
		   elseif($this->row['foodname']=='')
			{showmsg('你没有食物可以救济你的朋友，赶紧去买吧！');}
		   else
			{
			$msg="$this->tname 请我吃了一顿$this->showfood ，太感谢他了！ ━ $ntime";
			$health=$this->food;
			$mexecl="update pw_gold set msg='$msg',health='$health',time='$time' where id='$this->repairid' ";
			$this->db->query($mexecl);
			$mexecl="update pw_gold set foodname='' where id='$this->tgid' ";
			$this->db->query($mexecl);
			pwSendMsg(array('toUser'=>$this->row4[name],'fromUid'=>$this->tgid,'fromUser'=>$this->tname,'subject'=>'淘金消息','content'=>$msg)) ;
			}
		}elseif($this->help=="mend") {
			$this->things();
			 if($this->row4['tool']>10)
			{showmsg('你的朋友工具目前不需要修理，谢谢你的好意！');}
			 elseif($this->row4['toolname']=='')
			{showmsg('你的朋友没有工具，无法修理！');}
		   else
			{
			   $tool=$this->tool-$this->row4['tool'];
			   //$jjhealth=$tool*2;
			   $jhealth=$this->row['health']-$tool;
			   if($jhealth>0)
				{
			   $xltoolname=$this->row4['toolname'];
			$msg="$this->tname 消耗了$tool 点体力帮我修理了$xltoolname ，太感谢他了！ ━ $ntime";
			$mexecl="update pw_gold set msg='$msg',tool='$tool',time='$time' where id='$this->repairid' ";
			$this->db->query($mexecl);
			$mexecl="update pw_gold set health='$jhealth' where id='$this->tgid' ";
			$this->db->query($mexecl);
			pwSendMsg(array('toUser'=>$this->row4[name],'fromUid'=>$this->tgid,'fromUser'=>$this->tname,'subject'=>'淘金消息','content'=>$msg)) ;
				}
				else
				{showmsg('你的体力不足以帮他修理工具');}
			}
		}else{
			$thetime=$time-$this->row['time'];
			$stime=60-$thetime;
			if($thetime>60)	{
				$this->getmsg();
				$health=$this->row['health']-1;
				$thismsg=$this->getmsg.' ━ '.$ntime;
				$mexecl="update pw_gold set msg='$thismsg',health='$health',time='$time' where id='$this->tgid'";
				$this->db->query($mexecl);
			}else{
				echo"<div class=\"nomoney\"><span>提示信息：</span><span class=\"buy\">请休息{$stime}秒后再呐喊！</span></div>";
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
			case  "木镐":
			$this->tool = 20;  //设置木镐的效果（20表示加20工具耐久）
			$this->money = 10; //设置木镐的价格（10表示售价10两黄金）
			break;

			case  "铁镐":
			$this->tool = 20; //同上
			$this->money = 20;//同上
			break;

			case  "铜镐":
			$this->tool = 20;//同上
			$this->money = 250;//同上
			break;

			case  "银镐":
			$this->tool = 20;//同上
			$this->money = 1000;//同上
			break;

			case  "金镐":
			$this->tool = 20;//同上
			$this->money = 5000;//同上
			break;

			case  "馍馍":
			$this->food = 100;//设置馍馍的效果（100表示加100体力）
			$this->money = 10; //设置馍馍的价格（10表示售价10两黄金）
			break;

			case  "白米饭":
			$this->food = 200;//同上
			$this->money = 20;//同上
			break;

			case  "蛋炒饭":
			$this->food = 300;//同上
			$this->money = 30;//同上
			break;

			case  "盖浇饭":
			$this->food = 400;//同上
			$this->money = 40;//同上
			break;

			case  "胶原猪脚":
			$this->food = 500;//同上
			$this->money = 50;//同上
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
		if($this->row['allscore']<3000)  //此处小于10000表示，总金矿小于10000将无法提炼黄金，若修改，下面2处10000也同步修改
		{
			echo"<div class=\"nomoney\">提示信息：你的金矿余量不足，无法熔炼！</div>";
		}
		elseif($this->getchangegold == "炼金" and $this->row['allscore'] != 0)
		{
			$jt = round($this->row['allscore'] / 3000,0);  //此处除10000是计算金矿提炼黄金的比例为1：10000
			$updatagold = $jt + $this->row2['value'] ;
			$mexecl="update pw_gold set money='$updatagold' where id='$this->tgid' ";
			$this->db->query($mexecl);

			$uexecl="update pw_membercredit set value='$updatagold' where uid='$this->tgid' and cid='$this->tcid' ";
			if($this->db->query($uexecl))
			{

				$reducegold = $this->row['allscore']-($jt * 3000); //此处10000是根据提炼的黄金数量计算应扣除的金矿数量
				$reexecl="update pw_gold set allscore='$reducegold' where id='$this->tgid'";
				if($this->db->query($reexecl))
				{
					echo"<div class=\"nomoney\">提示信息：成功熔炼了 $jt $creditdb[name]</div>";
				}
				else
				{
					echo"$creditdb[name]熔炼失败！";
				}
			}
			else
			{
				echo"$creditdb[name]熔炼失败！";
			}
		}

	}


	function updatatool(){
		$this->getmsg();
		$this->getscore();
		if($this->row2['value'] >= $this->money ){
			if($this->food!='')	{
				if($this->showfood!=''){
					echo"<div class=\"nomoney\"><span>提示信息：</span><span class=\"buy\">你的{$this->showfood}还没吃完，请珍惜粮食</span></div>";
				}else{
					$hexecl="update pw_gold set foodname='$this->gettool',health='$this->food' where id='$this->tgid'";
				}
			}elseif($this->tool!=''){
				if($this->showtool!='')	{
					echo"<div class=\"nomoney\"><span>提示信息：</span><span class=\"buy\">你的{$this->showtool}还没用完，请节约资源</span></div>";
				}else{
					$hexecl="update pw_gold set toolname='$this->gettool',tool='$this->tool' where id='$this->tgid'";
				}
			}
			if(!empty($hexecl) && $this->db->query($hexecl)){
				$this->updatascore();
				echo"<span>物品信息：</span><span class=\"buy\">购买 $this->gettool 成功</span></div>";
			}else{
				echo"<span class=\"buy\">购买 $this->gettool 失败</span>";
			}
		}else{
			echo"<div class=\"nomoney\">提示信息：你的现金不足！</div>";
			//showmsg("你的余额不足");
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
				echo"<div class=\"costmsg\"><span>资金信息：</span><span class=\"cost\">你支出了 $this->money $creditdb[name]</span>";
			}
			else
			{
				echo"无法提交数据";
			}
	}
}
$ch=new tool($tname,$tgid,$gettool,$getchangegold,$getmsg,$repairid,$help,$db);
$tcid=$ch->getcid();
$creditdb=$db->get_one("SELECT * FROM pw_credits WHERE cid=".pwEscape($tcid));
$ch->oc==0 && showmsg('插件已关闭');
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
if($repair==$tgid){showmsg('你不能请自己吃饭或帮自己修理工具，快请你的朋友帮忙吧！');}
elseif($getmsg!=''or$help!=''){$ch->updatamsg();}
//$ch->updatamsg();
 $showvalue=$ch->showvalue;
 $showallscore=$ch->showallscore;
 $showtool=$ch->showtool;
 $tool=$ch->gtool;
 $showfood=$ch->showfood;
 $showhealth=$ch->showhealth;
echo"<div class=\"msg\"><span>你的食物:$showfood </span><span>体力:$showhealth </span><span>你的工具:$showtool </span><span>耐久:$tool </span><span>金矿:$showallscore 公斤</span><span>$creditdb[name]:$showvalue $creditdb[unit]</span></div><div class=\"topmsg\">小买部公益广告:请珍惜慎重消费自己的劳动成果！！！不然要去论坛发帖赚取积分换$creditdb[name]了。</div><div class=\"topmsg1\">公告栏：（要帮助人请直接点他的名字）<br><marquee direction=up height=100 scrollamount=1 onmouseover='this.stop()' onmouseout='this.start()'>";
$query = "SELECT id,name,msg,health,tool FROM pw_gold ORDER BY time DESC LIMIT 0 , 30 ";
$result = $db->query($query);
while($row=$db->fetch_array($result)){
	echo"<a href=hack.php?H_name=stor&repair=$row[id]>$row[name]</a>在呐喊：{$row[msg]}（体力{$row[health]}，工具耐久{$row[tool]}）<br>";
}
echo"</marquee></div>";
require_once PrintHack('index');
echo('</td></tr></table></div>');
footer();
?>