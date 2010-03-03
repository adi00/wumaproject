<?php
!function_exists('readover') && exit('Forbidden');

        $groupid=="guest" && showmsg('not_login');

	$notice_f=file(D_P."data/bbscache/fish/fishlog{$tddays}.php");
	$count=count($notice_f)-1;
	$notes="";
	for($i=$count;$i>=0;$i--){

            $detail=explode("|",$notice_f[$i]);

            if(!$notice_f[$i]){

                 $notes.="暂时还没有人敢来这里探宝！大批宝藏沉在海底，真是可惜！！";

            }else{
                 if($detail[2]==1){
                     $notes.="因为很久没探险了，$detail[0] 于 $detail[1] 探宝一夜，只找到一条海马,卖给海边渔民，赚取了 $detail[3] 元<br/>";
                 }elseif($detail[2]==2){

                     $notes.="$detail[0] 于 $detail[1] 探宝一夜，引起了大白鲨的注意,身受重伤，治疗花费 $detail[3] 元,并且必须休息 $vtime 分钟<br/>";
                 }elseif($detail[2]==3){
                     $notes.="$detail[0] 于 $detail[1] 捕获到一条驼背鲸,赚取 $detail[3] 元，开心的不得了！<br/>";

                 }elseif($detail[2]==4){

                     $notes.="$detail[0] 于 $detail[1] 抓到一条金鱼,赚取 $detail[3] 元，因为钱太少了而闷闷不乐<br/>";

                 }elseif($detail[2]==5){

                     $notes.="亚马逊热带雨林的食人鱼居然被 $detail[0] 于 $detail[1] 在海里发现了,身受重伤，花费 $detail[3] 元治疗，时间为 $vtime 分钟<br/>";

                 }elseif($detail[2]==6){

                     $notes.="$detail[0] 于 $detail[1] 探宝一夜，只抓到1条比目鱼,卖给海边小孩，赚取 $detail[3] 元<br/>";

                 }elseif($detail[2]==7){

                     $notes.="$detail[0] 于 $detail[1] 在海底居然发现了鳄鱼,身上装备损坏花费 $detail[3] 金币修补,并且已经受伤必须休息 $vtime 分钟<br/>";
					 
			     }elseif($detail[2]==8){

                     $notes.="$detail[0] 于 $detail[1] 找到了宝船南海II号,财产瞬间增加了 $detail[3] 金币，大家快去打劫他啊！";
					 
			     }elseif($detail[2]==9){

                     $notes.="$detail[0] 于 $detail[1] 看到了传说中的美人鱼,得到出版社的 $detail[3] 金币写成传记！";
					 
			     }elseif($detail[2]==10){

                     $notes.="$detail[0] 于 $detail[1] 遇到深海乌贼,没有受伤，但是装备都坏了，维修花费 $detail[3] 金币,并且决定休假 $vtime 分钟<br/>";
					 
				 }elseif($detail[2]==11){

                     $notes.="$detail[0] 于 $detail[1] 被剧毒海蜇攻击，治疗花费 $detail[3] 金币,必须住院休息 $vtime 分钟<br/>";
					 
				 }elseif($detail[2]==12){

                     $notes.="$detail[0] 于 $detail[1] 什么也没找到，顺手拿了点珊瑚礁出来卖,增加 $detail[3] 金币";
					 
				 }elseif($detail[2]==13){

                     $notes.="$detail[0] 于 $detail[1] 找到了一颗大珍珠,增加 $detail[3] 金币";
					 
				 }elseif($detail[2]==14){

                     $notes.="$detail[0] 于 $detail[1] 遇到了传说中的海怪！损失 $detail[3] 金币治疗过度惊吓,并且已经受伤必须休息 $vtime 分钟<br/>";
                 }
            }
	}



setcookie(click,''); 
require_once PrintHack(click);footer();
?>