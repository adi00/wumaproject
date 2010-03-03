<!--********************************
可修改项目行数分别在88,104,105，修改时参考注释
**********************************-->
<?php
!function_exists('readover') && exit('Forbidden');
?>
<div id="breadCrumb">
		<img src="<?php echo $imgpath?>/<?php echo $stylepath?>/thread/home-old.gif" align="absmiddle" />&nbsp;<a href="<?php echo $db_bfn?>" title="<?php echo $db_bbsname?>"><?php echo $db_bbsname?></a> &raquo; <a href="hack.php?H_name=gold">『黄金矿场』</a>
</div>
<div class="t"><table width="100%" align="center" cellspacing="0" cellpadding="0">
<tr><td class="h"><b>淘金导航： <a href="hack.php?H_name=gold">『黄金矿场』</a>
<a href="hack.php?H_name=stor">『淘金商业街』</a>
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
		echo"取得分数失败";
		}
	}

    function getvalue(){
		//$this->getcid();
		$vquery = "SELECT value FROM pw_membercredit where uid='$this->gid' and cid='$this->gcid'";
		if($vresult = $this->db->query($vquery)){
		$this->vrow=$this->db->fetch_array($vresult);
		}
		else{
		echo"取得分数失败";
		}
	}

	function inset_first_value(){
		//$this->getcid();
		$this->getvalue();
		if($this->gcid=='')
	      {
			echo"<p class=\"undefine\">BBS后台还未添加游戏自定义积分，无法参与积分排行榜，请联系管理员</p>";
	      }
		elseif($this->vrow['value']=='' and $this->name1 != '')
		  {
			$value=1; //设置首次进入淘金的初始黄金数量，如整合PK插件，请与PK设置一样。
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
			$gtool=10;  //设置首次进入淘金的初始工具耐久值
			$ghealth=60; //设置首次进入淘金的初始体力值
			$msg='我来淘金啦~！';
			$time=date("YmdHis");
			$sql2 = "INSERT INTO pw_gold (id,name,toolname,tool,health,score,allscore,msg,time) VALUES('$this->gid','$this->name1','木镐','$gtool','$ghealth','$gscore','$gallscore','$msg','$time')";
			if($this->db->query($sql2))
			  {
				//echo"添加成功";
			  }
			  else
			  {
				  echo"添加失败";
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
				$dj='小石粒';
			break;
			case  $sscore <=14000:
				$dj='鹅卵石';
			break;
			case  $sscore <=21000:
				$dj='石英石';
			break;
			case  $sscore <=28000:
				$dj='汉白玉';
			break;
			case  $sscore <=35000:
				$dj='银块';
			break;
			case  $sscore <=42000:
				$dj='翡翠';
			break;
			case  $sscore <=49000:
				$dj='玛瑙';
			break;
			case  $sscore <=56000:
				$dj='金砖';
			break;
			case  $sscore <=63000:
				$dj='火钻';
			break;
			case  $sscore <=170000:
				$dj='宇光石';
			break;
		}

	echo"<div class=\"user123\">淘金者：{$this->name1} &nbsp;&nbsp;最高金矿产量：{$sscore}公斤&nbsp;&nbsp;金矿储备量：{$allsscore}公斤&nbsp;&nbsp;等级：{$level}级{$dj}&nbsp;&nbsp;体力：{$health}牛&nbsp;&nbsp;工具：{$toolname}&nbsp;&nbsp;耐久度：{$tool}度</div>";
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
		echo"<div class=\"topgold\"> <h3>淘金高手排行榜</h3><br>";
		while($result=$this->db->fetch_array($resultscore)){
			$user1=$result['name'];
			$score=$result['score'];
			echo" <span>{$user1}</span>：金矿 $score 公斤<img src=\".\hack\gold\image\gold.jpg\"><br>";
		}
		echo"</div>";


			$sqlallscore = "SELECT name,money FROM pw_gold  ORDER BY money DESC  LIMIT 0 , 10";
			$resultallscore = $this->db->query($sqlallscore);
			echo"<div class=\"topallgold\"> <h3>淘金富翁排行榜</h3><br>";
			while($resultall=$this->db->fetch_array($resultallscore))
			{
				$user2=$resultall['name'];
				$gmoney=$resultall['money'];
				echo"<span>{$user2}</span> ：$creditdb[name] $gmoney $creditdb[unit]<img src=\".\hack\gold\image\gold.jpg\"><br>";
			}
			echo"</div></div>";
	}


}


$a=new gold($ngid,$nname,$db);
$tcid=$a->getcid();
$creditdb=$db->get_one("SELECT * FROM pw_credits WHERE cid=".pwEscape($tcid));
//$creditdb['name']
$a->oc==0 && showmsg('插件已关闭');
$a->inset_first_value();
$a->inset_first_point();
$wquery = "SELECT health,tool,toolname FROM pw_gold where id='$ngid' ";
$wresult = $db->query($wquery);
$wrow=$db->fetch_array($wresult);
if($wrow['health']<5 or $wrow['toolname']==''){
	showmsg("你已经体力不支或工具严重破损，不能再挖金了，<a href=hack.php?H_name=stor >淘金商业街</a>转转吧！");
}else{
	$a->putout_user_msg();
	$ntoolname=$wrow['toolname'];
	switch($ntoolname){
		case 木镐 : $swf='kg1.swf';break;
		case 铁镐 : $swf='kg2.swf';break;
		case 铜镐 : $swf='kg3.swf';break;
		case 银镐 : $swf='kg4.swf';break;
		case 金镐 : $swf='kg5.swf';break;
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
