	  <div class="title"><?=$board_name?> 관리</div>
		<div>
			<p id="btnSw" style="float:left">
				<a href="index.php?WS=310&act=<?=$act?>&b_cd=person"><span class="btn type1 sch_bt <?=($b_cd=='person')?"gray":"white";?>">개인일정</span></a>
				<a href="index.php?WS=310&act=<?=$act?>&b_cd=center"><span class="btn type1 sch_bt <?=($b_cd=='center')?"gray":"white";?>"">센터일정</span></a>
				<a href="index.php?WS=310&act=<?=$act?>&b_cd=car"><span class="btn type1 sch_bt <?=($b_cd=='car')?"gray":"white";?>"">차량관리</span></a>
				<a href="index.php?WS=310&act=<?=$act?>&b_cd=room"><span class="btn type1 sch_bt <?=($b_cd=='room')?"gray":"white";?>"">상담실관리</span></a>
			</p>
			<p style="float:right">
				<? if($act=='pl'){?>
				<a href="index.php?WS=<?=$_GET['WS']?>&b_cd=<?=$b_cd?>"><span class="btn white type1 sch_bt">달력으로보기</span></a>	
				<? } else {?>
				<a href="index.php?WS=<?=$_GET['WS']?>&act=pl&b_cd=<?=$b_cd?>"><span class="btn white type1 sch_bt">리스트로보기</span></a>
				<? } ?>
				<a href="index.php?WS=<?=$_GET['WS']?>&act=pv"><span class="btn white type1 sch_bt">일정등록하기</span></a>	
			</p>
		</div>
		<div style="clear:both"></div>
		<hr/>