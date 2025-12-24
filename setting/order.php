<?php

	if ( !eregi( "setting.php", $_SERVER['PHP_SELF'] ) ) {
	    die ( "You can't access this file directly..." );
	}

	$module_name = basename( dirname( substr( __FILE__, 0, strlen( dirname( __FILE__ ) ) ) ) );
	$modfunction = "modules/$module_name/module.php";
	include_once( $modfunction );

	$ezshop=new EzShop2();

    ?><span class="txtContentTitle"><?=_EZSHOP_SETTING; ?></span><br/><br/>
	<?=_EZSHOP_ORDER_SETTING_INSTRUCTION; ?><br/><br/>
	<img src="theme/<?=$cfg['theme']; ?>/images/config.gif" border="0" align="absmiddle"/>
	<a href="javascript:chk_mpending();" >
	<?=_PENDING; ?></a>&nbsp;&nbsp;
	<a href="javascript:chk_mrtrans();" >
	<!--
	<img src="theme/<?=$cfg['theme']; ?>/images/user.gif" border="0" align="absmiddle"/>
	<?=_PAYRECIEVED; ?></a>&nbsp;&nbsp;-->
	<img src="theme/<?=$cfg['theme']; ?>/images/ok.gif" border="0" align="absmiddle"/>
	 <a href="javascript:chk_mshipped();" >
	<?=_SHIPPED; ?></a>&nbsp;&nbsp;
	<img src="theme/<?=$cfg['theme']; ?>/images/cancel.gif" border="0" align="absmiddle"/>
	<a href="javascript:chk_mcancel();" >
	<?=_CANCEL; ?></a>&nbsp;&nbsp;
	<img src="theme/<?=$cfg['theme']; ?>/images/back.gif" border="0" align="absmiddle"/>
	<a href="setting.php?modname=ezshopingcart" >
	<?=_BACK; ?></a>
	<br><br>
    <script language="JavaScript" type="text/javascript">
    <!--
		function chk_mcancel() {
			if (confirm("<?=_CHANGE_STATUS_QUESTION; ?>")){
				document.form.ac.value="mordercancel";
				document.form.submit();
			}
		}
        function chk_mpending() {
			if (confirm("<?=_CHANGE_STATUS_QUESTION; ?>")){
				document.form.ac.value="morderpending";
				document.form.submit();
			}
		}
        function chk_mrtrans() {
			if (confirm("<?=_CHANGE_STATUS_QUESTION; ?>")){
				document.form.ac.value="mordertrans";
				document.form.submit();
			}
		}
        function chk_mshipped() {
			if (confirm("<?=_CHANGE_STATUS_QUESTION; ?>")){
				document.form.ac.value="mordershipped";
				document.form.submit();
			}
		}
	//-->
    </script>
<?
	$ezshop->getOrderList();

?>
