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
	private $toolname;


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
		$hquery = "SELECT health,toolname,tool,score,allscore FROM pw_gold where id='$this->gid' ";
		$hresult = $this->db->query($hquery);
		$hrow=$this->db->fetch_array($hresult);
		$this->userhealth=$hrow['health'];
		$this->tool=$hrow['tool'];
		$this->oldscore = $hrow['score'];
		$this->allscore = $hrow['allscore'];
		$this->toolname = $hrow['toolname'];
		$this->allscore = $this->allscore + $this->score;
	}

	function dohealth()
	{
		$this->getgoldmsg();
			$hhealth = $this->userhealth - 5 ;
			$htool = $this->tool - 1 ;
			if($htool>=0 and $hhealth>=0)
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


	function updatagold()
	{
		$this->getgoldmsg();
		if($this->yzcode == $this->toolname)
		{
		$time=date("YmdHis");
		$ntime=date("Y-m-d");
		if($this->oldscore < $this->score and $this->userhealth>=0 and $this->tool>=1)
		{
			if($this->tool<=1)
			{
				$msg="我没工具了！555~~ ━ $ntime";
				$execl2="update pw_gold set toolname='' , score='$this->score' , allscore ='$this->allscore',msg='$msg',time='$time' where id='$this->gid' ";
			$this->db->query($execl2);
			}
			else
			{
				$msg="我挖到了$this->score 公斤金矿，你们都来和我比比吧！ ━ $ntime";
				$execl2="update pw_gold set score='$this->score' , allscore ='$this->allscore',msg='$msg',time='$time' where id='$this->gid' ";
			$this->db->query($execl2);
			}

		}
		elseif($this->userhealth>=0 and $this->tool>=1)
		{

			if($this->tool<=1)
			{
				$msg="我没工具了！555~~ ━ $ntime";
				$execl2="update pw_gold set toolname='' , allscore ='$this->allscore',msg='$msg',time='$time' where id='$this->gid' ";
			$this->db->query($execl2);
			}
			else
			{
				$msg="我挖到了$this->score 公斤金矿，你们都来和我比比吧！ ━ $ntime";
				$execl2="update pw_gold set allscore ='$this->allscore',msg='$msg',time='$time' where id='$this->gid' ";
			$this->db->query($execl2);
			}


		}
		else
		{
			$msg="我没工具了！555~~ ━ $ntime";
			$execl2="update pw_gold set msg='$msg',time='$time' where id='$this->gid' ";
			$this->db->query($execl2);
		}
		}
	}
}
$nb = new insetgold($nname,$ngid,$nyzcode,$nscore,$db);
$nb->updatagold();
$nb->dohealth();
?>
