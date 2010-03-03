<?php
!function_exists('readover') && exit('Forbidden');

$winddb[money] < 1 && showmsg("你现在包里金钱不够,我无法卖藏宝图给你!");

if(strpos($fish_groups,",$groupid,")===false){

           showmsg("你所在的级别,无法出来探宝，还是再修炼修炼再来吧!");
}

if($_COOKIE[click]==1){

showmsg("你动作太快了，把宝物都破坏了，改天再来吧！ <META HTTP-EQUIV=REFRESH CONTENT='0;URL=hack.php?H_name=fish&action=click'> ");

}


$rt = $db->get_one("SELECT * FROM pw_fish where uid='$winduid'");

if($vtime && $rt[time] && $timestamp-$rt[time]<$vtime*60){

        $time = get_date($rt[time],'Y-m-d A:H:i:s');

	showmsg("你在 $time 因探宝受伤,必须休息 $vtime 分钟");

}



$rand=rand(1,14);

if($rand==1){

   fish($windid,1,$fish1money);
   fishsql($winduid,1);

   $db->update("UPDATE pw_memberdata SET money=money+'$fish1money' WHERE uid='$winduid'");

   $msg="因为很久没探险了，只找到一条海马,卖给海边渔民，增加 $fish1money 金币,等等自动返回....<META HTTP-EQUIV=REFRESH CONTENT='1;URL=hack.php?H_name=fish&action=click'>";

}elseif($rand==2){

   fish($windid,2,$fish2money);
   fishsql($winduid,2);

   $db->update("UPDATE pw_memberdata SET money=money-'$fish2money' WHERE uid='$winduid'");

   $msg="你今日运气不好!被大白鲨攻击,损失 $fish2money 金币并且已经受伤,必须休息 $vtime 分钟,等等自动返回....<META HTTP-EQUIV=REFRESH CONTENT='1;URL=hack.php?H_name=fish&action=click'>";

}elseif($rand==3){

   fish($windid,3,$fish3money);
   fishsql($winduid,3);

   $db->update("UPDATE pw_memberdata SET money=money+'$fish3money' WHERE uid='$winduid'");

   $msg="恭喜你!捕到一条驼背鲸,增加 $fish3money 金币,等等自动返回....<META HTTP-EQUIV=REFRESH CONTENT='1;URL=hack.php?H_name=fish&action=click'>";


}elseif($rand==4){

   fish($windid,4,$fish4money);
   fishsql($winduid,4);

   $db->update("UPDATE pw_memberdata SET money=money+'$fish4money' WHERE uid='$winduid'");

   $msg="忙乎半天，只抓到一条金鱼,增加 $fish4money 金币,钱太少了！等等自动返回....<META HTTP-EQUIV=REFRESH CONTENT='1;URL=hack.php?H_name=fish&action=click'>";

}elseif($rand==5){

   fish($windid,5,$fish5money);
   fishsql($winduid,5);

   $db->update("UPDATE pw_memberdata SET money=money-'$fish5money' WHERE uid='$winduid'");

   $msg="你今日运气不好!在亚马逊热带雨林的食人鱼居然让你在海里发现了,身受重伤，治疗花费 $fish5money 金币并且已经受伤,必须休息 $vtime 分钟,等等自动返回....<META HTTP-EQUIV=REFRESH CONTENT='1;URL=hack.php?H_name=fish&action=click'>";

}elseif($rand==6){


   fish($windid,6,$fish6money);
   fishsql($winduid,6);

   $db->update("UPDATE pw_memberdata SET money=money+'$fish6money' WHERE uid='$winduid'");

   $msg="探宝一夜，只抓到1条比目鱼,卖给海边小孩，赚取 $fish6money 金币,等等自动返回....<META HTTP-EQUIV=REFRESH CONTENT='1;URL=hack.php?H_name=fish&action=click'>";

}elseif($rand==7){

   fish($windid,7,$fish7money);
   fishsql($winduid,7);

   $db->update("UPDATE pw_memberdata SET money=money-'$fish7money' WHERE uid='$winduid'");

   $msg="你今日运气不好!钓起一条鳄鱼,损失 $fish7money 金币并且已经受伤,休息 $vtime 分钟,等等自动返回....<META HTTP-EQUIV=REFRESH CONTENT='1;URL=hack.php?H_name=fish&action=click'>";

}elseif($rand==8){

   fish($windid,8,$fish8money);
   fishsql($winduid,8);

   $db->update("UPDATE pw_memberdata SET money=money+'$fish8money' WHERE uid='$winduid'");

   $msg="你今日运气好!找到了南海II号,增加 $fish8money 金币，财不外露，千万别让别人知道啊！等等自动返回....<META HTTP-EQUIV=REFRESH CONTENT='1;URL=hack.php?H_name=fish&action=click'>";


}elseif($rand==9){

   fish($windid,9,$fish9money);
   fishsql($winduid,9);

   $db->update("UPDATE pw_memberdata SET money=money+'$fish9money' WHERE uid='$winduid'");

   $msg="你今日运气好!看到了传说中的美人鱼,出版社给了您 $fish9money 金币写成传记,等等自动返回....<META HTTP-EQUIV=REFRESH CONTENT='1;URL=hack.php?H_name=fish&action=click'>";
   
   }elseif($rand==10){

   fish($windid,10,$fish10money);
   fishsql($winduid,10);

   $db->update("UPDATE pw_memberdata SET money=money-'$fish10money' WHERE uid='$winduid'");

   $msg="深海乌贼警惕的看着你，无功而返，花费 $fish10money 金币决定休假 $vtime 分钟，等等自动返回....<META HTTP-EQUIV=REFRESH CONTENT='1;URL=hack.php?H_name=fish&action=click'>";
   
   }elseif($rand==11){

   fish($windid,11,$fish11money);
   fishsql($winduid,11);

   $db->update("UPDATE pw_memberdata SET money=money-'$fish11money' WHERE uid='$winduid'");

   $msg="剧毒海蜇攻击了你，治疗花费 $fish11money 金币并且已经受伤,必须休息 $vtime 分钟,等等自动返回....<META HTTP-EQUIV=REFRESH CONTENT='1;URL=hack.php?H_name=fish&action=click'>";
   
   }elseif($rand==12){

   fish($windid,12,$fish12money);
   fishsql($winduid,12);

   $db->update("UPDATE pw_memberdata SET money=money+'$fish12money' WHERE uid='$winduid'");

   $msg="你今天什么也没找到，顺手拿了点珊瑚礁出来卖,增加 $fish12money 金币,等等自动返回....<META HTTP-EQUIV=REFRESH CONTENT='1;URL=hack.php?H_name=fish&action=click'>";
   
   }elseif($rand==13){

   fish($windid,13,$fish13money);
   fishsql($winduid,13);

   $db->update("UPDATE pw_memberdata SET money=money+'$fish13money' WHERE uid='$winduid'");

   $msg="你今日运气好!找到了一颗大珍珠,增加 $fish13money 金币,等等自动返回....<META HTTP-EQUIV=REFRESH CONTENT='1;URL=hack.php?H_name=fish&action=click'>";
   
   }elseif($rand==14){

   fish($windid,14,$fish14money);
   fishsql($winduid,14);

   $db->update("UPDATE pw_memberdata SET money=money-'$fish14money' WHERE uid='$winduid'");

   $msg="不好!你遇到了传说中的海怪！花了 $fish14money 金币治疗过度惊吓,必须休息 $vtime 分钟,等等自动返回....<META HTTP-EQUIV=REFRESH CONTENT='1;URL=hack.php?H_name=fish&action=click'>";
}

setcookie(click,'1');

showmsg("$msg",1);

function fishsql($uid,$cate){

	global $db,$timestamp,$windid;

        if($cate==2||$cate==5||$cate==7){

           $catsql=",time='$timestamp'";
        }

        $rt = $db->get_one("SELECT * FROM pw_fish where uid='$uid'");

        if(!$rt[uid]){

             $db->update("INSERT INTO pw_fish set uid='$uid',username='$windid',fish{$cate}=fish{$cate}+'1',statistics='1'{$catsql}");

        }else{

             $db->update("UPDATE pw_fish  SET fish{$cate}=fish{$cate}+1,statistics=statistics+'1'{$catsql} WHERE uid='$uid'");

        }


        $db->update("UPDATE pw_fishlog SET fish{$cate}=fish{$cate} + '1'");
}


function fish($uid,$cate,$money){
         global $db,$timestamp,$tddays;

         $time = get_date($timestamp,'Y-m-d A:H:i:s');

         $club_list=openfile(D_P."data/bbscache/fish/fishlog{$tddays}.php"); 
         $num=count($club_list);  
         for($r=0;$r<$num;$r++){
               $detail=explode("|",$club_list[$r]); 
               if(trim($detail[0])==$uid){ 
                       unset($club_list[$r]);
                       $ichack=1;
               }else{
                       $ichack=1;
               }
   
         }
         $club_list=implode("",$club_list); 
         @writeover(D_P."data/bbscache/fish/fishlog{$tddays}.php",$club_list,w); 
         

       if($ichack==1){
       
         $club_list=file(D_P."data/bbscache/fish/fishlog{$tddays}.php"); 
         $num=count($club_list)-1;
    
	 $club_list="$uid|$time|$cate|$money\r\n";

	 @writeover(D_P."data/bbscache/fish/fishlog{$tddays}.php",$club_list,"a");

       } 

}

?>