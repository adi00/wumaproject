<?php
!function_exists('readover') && exit('Forbidden');

        //userinfo start
        list($db_moneyname,$db_moneyunit,$db_moneyunit,$db_rvrcname,$db_rvrcunit,$db_creditname,$db_creditunit)=explode("\t",$db_credits);
        $userdb = $db->get_one("SELECT m.*,md.postnum,md.digests,md.rvrc,md.money,md.credit,md.currency,md.starttime,md.lastvisit,md.onlinetime,mb.deposit,mb.ddeposit FROM pw_members m LEFT JOIN pw_memberdata md USING(uid) LEFT JOIN pw_memberinfo mb USING(uid) WHERE m.uid='$winduid'");  
	
 	require_once(R_P.'require/credit.php');
	$creditdb = $credit->get($winduid,$pwgift_credit);
	    $userdb['rvrc']=floor($userdb['rvrc']/10);
        //userinfo end

        $dayfile = D_P."data/bbscache/fish/hotday.php";
        $fish1 = $fish2 = $fish3 = $fish4 = $fish5 = $fish6 = $fish7 = $fish8 = $fish9 = $fish10 = $fish11 = $fish12 = $fish13 = $fish14 ="";
        if(($timestamp-@filemtime($dayfile) >= 120)){
            
            $rt = $db->get_one("SELECT * FROM pw_fishlog");

            $fish1 = $rt[fish1];
            $fish2 = $rt[fish2];
            $fish3 = $rt[fish3];
            $fish4 = $rt[fish4];
            $fish5 = $rt[fish5];
            $fish6 = $rt[fish6];
            $fish7 = $rt[fish7];
			$fish8 = $rt[fish8];
			$fish9 = $rt[fish9];
			$fish10 = $rt[fish10];
			$fish11 = $rt[fish11];
			$fish12 = $rt[fish12];
			$fish13 = $rt[fish13];
			$fish14 = $rt[fish14];

            !$fish1 && $fish1=0;
            !$fish2 && $fish2=0;
            !$fish3 && $fish3=0;
            !$fish4 && $fish4=0;
            !$fish5 && $fish5=0;
            !$fish6 && $fish6=0;
            !$fish7 && $fish7=0;
			!$fish8 && $fish8=0;
			!$fish9 && $fish9=0;
			!$fish10 && $fish10=0;
			!$fish11 && $fish11=0;
			!$fish12 && $fish12=0;
			!$fish13 && $fish13=0;
			!$fish14 && $fish14=0;

	    @writeover($dayfile,"<?php\r\n\$fish1=\"$fish1\";\r\n\$fish2=\"$fish2\";\r\n\$fish3=\"$fish3\";\r\n\$fish4=\"$fish4\";\r\n\$fish5=\"$fish5\";\r\n\$fish6=\"$fish6\";\r\n\$fish7=\"$fish7\";\r\n\$fish8=\"$fish8\";\r\n\$fish9=\"$fish9\";\r\n\$fish10=\"$fish10\";\r\n\$fish11=\"$fish11\";\r\n\$fish12=\"$fish12\";\r\n\$fish13=\"$fish13\";\r\n\$fish14=\"$fish14\";\r\n?>");

        }else{
            @include($dayfile);
        }



        $hotpostsfile = D_P."data/bbscache/fish/hotpost.php";
        $uptime=get_date(filemtime($hotpostsfile)+60,'A:H:i:s');
	$uptime='下次统计时间 '.$uptime;
        $hotposts = "";
        if(($timestamp-@filemtime($hotpostsfile) >= 60)){


            $query = $db->query("SELECT * FROM pw_fish ORDER BY statistics DESC LIMIT 0,10");
	    while($loop = $db->fetch_array($query)) {

               $hotposts.="<tr align=center>
                           <td><a href=profile.php?action=show&uid=$loop[uid]>$loop[username]</a></td>
                           <td>$loop[fish1]</td>
                           <td>$loop[fish3]</td>
                           <td>$loop[fish4]</td>
                           <td>$loop[fish6]</td>
                           <td>$loop[fish8]</td>
                           <td>$loop[fish9]</td>
                           <td>$loop[fish12]</td>
						   <td>$loop[fish13]</td>
                           <td>$loop[statistics]</td>
                           </tr>\r\n";
	    }
	    unset($loop);
            $db->free_result($query);

            !$hotposts && $hotposts="<tr align=center><td colspan=9>无记录</td></tr>";


	    @writeover($hotpostsfile,"<?php\r\n\$hotposts=\"$hotposts\";\r\n?>");

        }else{
            @include($hotpostsfile);
        }



@extract($db->get_one("SELECT * FROM pw_bbsinfo WHERE id=1"));

if($tdtcontrol!=$tdtime){

@writeover(D_P."data/bbscache/fish/fishlog{$tddays}.php","");

}


require_once PrintHack(index);footer();
?>