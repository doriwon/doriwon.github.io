			<div class="paging">
					<a class="btn_arr first" href="javascript:web.pageMove('','page=<?=$pageArr['first']?>')"><img src="images/board_btn_pprev.gif" alt="첫번째페이지"/></a>
					<a class="btn_arr prev" href="javascript:web.pageMove('','page=<?=$pageArr[prev]?>')"><img src="images/board_btn_prev.gif" alt="이전페이지"/></a>
					<?for($i=$pageArr['fblock'];$i<=$pageArr['lblock'];$i++){?>
					<a class="<?=($i==$cpage)? 'on': '';?>" href="javascript:web.pageMove('','page=<?=$i?>')" title="<?=$i?>페이지"><?=$i?></a>
					<?}?>
					<a class="btn_arr next" href="javascript:web.pageMove('','page=<?=$pageArr['next']?>')"><img src="images/board_btn_next.gif" alt="다음페이지"/></a>
					<a class="btn_arr last" href="javascript:web.pageMove('','page=<?=$pageArr['last']?>')"><img src="images/board_btn_nnext.gif"  alt="마지막페이지"/></a>
			</div>