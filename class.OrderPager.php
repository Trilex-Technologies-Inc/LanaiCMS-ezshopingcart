<?php
  
/**
	 * OrderPager
	 * 
	 * @package 
	 * @author Administrator
	 * @copyright Copyright (c) 2006
	 * @version $Id: class.OrderPager.php,v 1.2 2008/07/25 02:37:25 redlinesoft Exp $
	 * @access public
	 **/
	class OrderPager extends ADODB_Pager {
	
		function __construct (&$db,$sql,$id = 'adodb', $showPageLinks = false){
			 parent::__construct($db,$sql,$id = 'adodb', $showPageLinks = false);
			$this->page=_PAGE;
		}
		
		function RenderLayout($header,$grid,$footer)
		{
			echo "<table width=\"100%\" ><tr><td>",
				 "</td></tr><tr><td>",
					$grid,
				"</td></tr><tr><td>",
					$footer," ",$header,
				"</td></tr></table>";
		}
		
		//---------------------------
		// Display link to first page
		function Render_First($anchor=true)
		{
			global $PHP_SELF;
			if ($anchor) {
			?>
				<a href="<?php echo $PHP_SELF,'?modname=',$_REQUEST['modname'],'&mf=',$_REQUEST['mf'],'&',$this->id;?>_next_page=1"><?php echo $this->first;?></a> &nbsp; 
			<?php
			} else {
				print "$this->first &nbsp; ";
			}
		}
		
		//--------------------------
		// Display link to next page
		function render_next($anchor=true)
		{
			global $PHP_SELF;
		
			if ($anchor) {
			?>
			<a href="<?php echo $PHP_SELF,'?modname=',$_REQUEST['modname'],'&mf=',$_REQUEST['mf'],'&',$this->id,'_next_page=',$this->rs->AbsolutePage() + 1 ?>"><?php echo $this->next;?></a> &nbsp; 
			<?php
			} else {
				print "$this->next &nbsp; ";
			}
		}
		
		//------------------
		// Link to last page
		
		function render_last($anchor=true)
		{
			global $PHP_SELF;
		
			if (!$this->db->pageExecuteCountRows) return;
			
			if ($anchor) {
			?>
				<a href="<?php echo $PHP_SELF,'?modname=',$_REQUEST['modname'],'&mf=',$_REQUEST['mf'],'&',$this->id,'_next_page=',$this->rs->LastPageNo() ?>"><?php echo $this->last;?></a> &nbsp; 
			<?php
			} else {
				print "$this->last &nbsp; ";
			}
		}
		
		// Link to previous page
		function render_prev($anchor=true)
		{
			global $PHP_SELF;
			if ($anchor) {
			?>
				<a href="<?php echo $PHP_SELF,'?modname=',$_REQUEST['modname'],'&mf=',$_REQUEST['mf'],'&',$this->id,'_next_page=',$this->rs->AbsolutePage() - 1 ?>"><?php echo $this->prev;?></a> &nbsp; 
			<?php 
			} else {
				print "$this->prev &nbsp; ";
			}
		}
		
		//--------------------------------------------------------
		// Simply rendering of grid. You should override this for
		// better control over the format of the grid
		//
		// We use output buffering to keep code clean and readable.
		function RenderGrid()
		{
			//global $gSQLBlockRows; // used by rs2html to indicate how many rows to display
			//include_once(ADODB_DIR.'/tohtml.inc.php');
			ob_start();
			$gSQLBlockRows = $this->rows;
			//rs2html($this->rs,$this->gridAttributes,$this->gridHeader,$this->htmlSpecialChars);
			$mod_lanai=new EzShop2();
			?>
			<script language="javascript" type="text/javascript"> 
		
			function selectall(obj) { 
				var checkBoxes = document.getElementsByTagName('input'); 
				for (i = 0; i < checkBoxes.length; i++) { 
					if (obj.checked == true) { 
						checkBoxes[i].checked = true; // this checks all the boxes 
					} else { 
						checkBoxes[i].checked = false; // this unchecks all the boxes 
					} 
				} 
			} 
			
			</script> 
			<table cellpadding="3" cellspacing="1" width="">
			<form name="form" method="get" action="<?=$_SERVER['PHP_SELF']?>">
			<input type="hidden" name="modname" value="ezshopingcart">
			<input type="hidden" name="mf" value="ezedit">
			<input type="hidden" name="ac" value="">
			<tr>
				<th class="tblRowSolidTopDown" align="center" width="0"><input type="checkbox" value="select_all" onclick="selectall(this);" class="radioButton" /></th>
                <th class="tblRowSolidTopDown" width="80"><?=_ORDERID; ?></th>
                <th class="tblRowSolidTopDown" width="200"><?=_DATETIME; ?></th>
                <th class="tblRowSolidTopDown" width="60%"><?=_MEMBER; ?></th>
                <th class="tblRowSolidTopDown" width="50"><?=_STATUS; ?></th>
				<!--<th class="tblRowSolidTopDown"><?=_EDIT; ?></th>-->
			</tr>
			<?
			while(!$this->rs->EOF){
			?>
			<tr>
				<td class="tblRowDash" align="center">
					<input type="checkbox" name="mid[]"  value="<?=$this->rs->fields['crtId']; ?>"  class="radioButton" />
				</td>
				<td class="tblRowDash">
					<img src="theme/<?=$mod_lanai->cfg['theme'];?>/images/file.gif" border="0" align="absmiddle">
                    <a href="<?=$_SERVER['PHP_SELF']; ?>?modname=ezshopingcart&mf=orderview&cid=<?=$this->rs->fields['crtId']; ?>" target="_blank">
                        <?=sprintf("%06d",$this->rs->fields['crtId']); ?>
                    </a>
				</td>
                <td class="tblRowDash">
                    <?=adodb_date2("d M Y H:i:s",$this->rs->fields['crtCreate']); ?>
                    &nbsp;
                </td>
                <td class="tblRowDash">
                    <?=$this->rs->fields['userFname']." ".$this->rs->fields['userLname']; ?>
                    &nbsp;
                </td>
                <td class="tblRowDash" align="center">
                    <?
                        if ($this->rs->fields['crtStatus']=='c') {
                            ?><span style="color:red; font-weight: bold;" >&nbsp;<?=strtoupper(_ST_CANCEL); ?>&nbsp;</span><?
                        } else if ($this->rs->fields['crtStatus']=='p') {
                            ?><span style="color:blue; font-weight: bold;" >&nbsp;<?=strtoupper(_ST_PANDING); ?>&nbsp;</span><?
                        } else if ($this->rs->fields['crtStatus']=='t') {
                            ?><span style="color:magenta; font-weight: bold;" >&nbsp;<?=strtoupper(_ST_PAYRECIEVED); ?>&nbsp;</span><?
                        } else if ($this->rs->fields['crtStatus']=='s') {
                            ?><span style="color:green; font-weight: bold;" >&nbsp;<?=strtoupper(_ST_SHIPPED); ?>&nbsp;</span><?
                        }

                    ?>
                </td>
            <!--
             	<td class="tblRowDash" align="center">
					<a href="<?=$_SERVER['PHP_SELF']."?modname=".$_REQUEST['modname']; ?>&mf=ordereditform&mid=<?=$this->rs->fields['crtId']; ?>">
					<img src="theme/<?=$mod_lanai->cfg['theme'];?>/images/edit.gif" border="0" align="absmiddle">
					</a>
				</td>
            -->
			</tr>
			<?
				$this->rs->movenext();
			} // while
			?></table><?
			$s = ob_get_contents();
			ob_end_clean();
			return $s;
		}

	}


  
?>
