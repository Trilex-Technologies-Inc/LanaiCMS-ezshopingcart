<?php

	if ( !eregi( "setting.php", $_SERVER['PHP_SELF'] ) ) {
	    die ( "You can't access this file directly..." );
	}

	$module_name = basename( dirname( substr( __FILE__, 0, strlen( dirname( __FILE__ ) ) ) ) );
	$modfunction = "modules/$module_name/module.php";
	include_once( $modfunction );

	$ezshop=new EzShop2();

?>
      <span class="txtContentTitle"><?=_EZSHOP_SETTING; ?></span><br/><br/>
	<?=_EZSHOP_PAYMENT_SETTING_INSTRUCTION; ?><br/><br/>
	<img src="theme/<?=$cfg['theme']; ?>/images/new.gif" border="0" align="absmiddle"/>
	<a href="<?=$_SERVER['PHP_SELF']?>?modname=<?=$module_name?>&mf=paymentnew" >
	<?=_NEW; ?></a>&nbsp;&nbsp;
    <!--
	<img src="theme/<?=$cfg['theme']; ?>/images/ok.gif" border="0" align="absmiddle"/>
	<a href="javascript:chk_mactive();" >
	<?=_ACTIVE; ?></a>&nbsp;&nbsp; -->
	<img src="theme/<?=$cfg['theme']; ?>/images/delete.gif" border="0" align="absmiddle"/>
	<a href="javascript:chk_mdelete();" >
	<?=_DELETE; ?></a>&nbsp;&nbsp;
	<img src="theme/<?=$cfg['theme']; ?>/images/back.gif" border="0" align="absmiddle"/>
	<a href="setting.php?modname=ezshopingcart" >
	<?=_BACK; ?></a>
	<br><br>
    <script language="JavaScript" type="text/javascript">
    <!--
		function chk_mdelete() {
			if (confirm("<?=_DELETE_QUESTION; ?>")){
				document.form.ac.value="mpaymentdelete";
				document.form.submit();
			}
		}
		function chk_mactive() {
			document.form.ac.value="mpaymentactive";
			document.form.submit();
		}
	//-->
    </script>
<?
	$ezshop->getPaymentList();

?>
