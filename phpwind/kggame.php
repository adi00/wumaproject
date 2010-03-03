<?php
require_once('global.php');
$nname=$windid;
$ngid=$winduid;
$db->query("set names 'gb2312'");
$nscore=addslashes($_POST['score']);
$nyzcode=addslashes($_POST['gamename']);

class insetgold
{
	private $name1;
	private $gid;
	private $yzcode;
	private $score;
	private $oldscore;
	private $allscore;
	private $userhealth;
	private $tool;
	private $gamescore;


	public function __construct($name1,$gid,$yzcode,$score,$db)
	{
		$this->gid=$gid;
		$this->name1=$name1;
		$this->yzcode=$yzcode;
		$this->score=$score;
		$this->db=$db;
	}




	function getgoldmsg()
	{
		$hquery = "SELECT health,tool,score,allscore FROM pw_gold where id='$this->gid' ";
		$hresult = $this->db->query($hquery);
		$hrow=$this->db->fetch_array($hresult);
		$this->userhealth=$hrow['health'];
		$this->tool=$hrow['tool'];
		$this->oldscore = $hrow['score'];
		$this->allscore = $hrow['allscore'];
		//$this->toolname = $hrow['toolname'];
		$this->allscore = $this->allscore + $this->score;
	}

	function getgamemsg()
	{
		$gquery = "SELECT score FROM pw_goldgame where uid='$this->gid' and ticket='2' ";
		$gresult = $this->db->query($gquery);
		$grow=$this->db->fetch_array($gresult);
		$this->gamescore = $grow['score'];
	}

	function dohealth()
	{
		$this->getgoldmsg();
		if($this->yzcode == "黄金矿工大赛")
		{
			$hhealth = $this->userhealth - 5 ;
			$htool = $this->tool - 1 ;
			if($htool<1)
			{
			$hexecl="update pw_gold set toolname='', health='$hhealth' , tool='$htool' where id='$this->gid' ";
			}
			else
			{
				$hexecl="update pw_gold set health='$hhealth' , tool='$htool' where id='$this->gid' ";
			}
			if($this->db->query($hexecl))
			{
			    echo"数据更新正常";
			}
			else
			{
				echo"无法提交挖金数据";
			}
		}
	}


	function updatagold()
	{
		$this->getgamemsg();
		$this->getgoldmsg();
		$time=date("YmdHis");
		$ntime=date("Y-m-d");
		if($this->gamescore < $this->score and $this->userhealth>=0 and $this->tool>=0)
		{
			//$msg="淘金大赛我挖到了$this->score 公斤金矿，大家多多支持我！ ━ $ntime";
			$execl2="update pw_goldgame set score='$this->score' where uid='$this->gid' ";
			$this->db->query($execl2);

		}
		if($this->oldscore < $this->score and $this->userhealth>=0 and $this->tool>=0)
		{
			$msg="淘金大赛我挖到了$this->score 公斤金矿，大家多多支持我！ ━ $ntime";
			$execl2="update pw_gold set score='$this->score' , allscore ='$this->allscore',msg='$msg',time='$time' where id='$this->gid' ";
			$this->db->query($execl2);
		}
		elseif($this->userhealth>=0 and $this->tool>=0)
		{
			$msg="淘金大赛我挖到了$this->score 公斤金矿，大家多多支持我！ ━ $ntime";
			$execl2="update pw_gold set allscore ='$this->allscore',msg='$msg',time='$time' where id='$this->gid' ";
			$this->db->query($execl2);

		}
		else
		{
			$msg="大赛中途工具损坏！真TM倒霉~~ ━ $ntime";
			$execl2="update pw_gold set msg='$msg',time='$time' where id='$this->gid' ";
			$this->db->query($execl2);
		}
	}
}
$nb = new insetgold($nname,$ngid,$nyzcode,$nscore,$db);
$nb->dohealth();
$nb->updatagold();
?>