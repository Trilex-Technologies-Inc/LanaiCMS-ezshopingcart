<?php

	if ( !eregi( "setting.php", $_SERVER['PHP_SELF'] ) ) {
	    die ( "You can't access this file directly..." );
	}

	$module_name = basename( dirname( substr( __FILE__, 0, strlen( dirname( __FILE__ ) ) ) ) );
	$modfunction = "modules/$module_name/module.php";
	include_once( $modfunction );

	$ezshop=new EzShop2();

    ?><span class="txtContentTitle"><?=_EZSHOP_SETTING; ?></span><br/><br/>
	<?=_EZSHOP_SETTING_INSTRUCTION; ?><br/><br/>

    <table width="80%" cellpadding="5" cellspacing="3">
      <tr>
        <td align="center">
            
	        <img src="modules/<?=$module_name; ?>/images/category.gif" border="0" align="absmiddle"/>
            <br/><br/>
            <a href="<?=$_SERVER['PHP_SELF']?>?modname=<?=$module_name; ?>&mf=category" >
            <?=_EZSHOP_CATEGORY; ?>
            </a>
        </td>
        <td align="center">
            
	        <img src="modules/<?=$module_name; ?>/images/product.gif" border="0" align="absmiddle"/>
            <br/><br/>
            <a href="<?=$_SERVER['PHP_SELF']?>?modname=<?=$module_name; ?>&mf=product" >
            <?=_EZSHOP_PRODUCT; ?>
            </a>
        </td>
		<td align="center">
            
	        <img src="modules/<?=$module_name; ?>/images/display.gif" border="0" align="absmiddle"/>
            <br/><br/>
            <a href="<?=$_SERVER['PHP_SELF']?>?modname=<?=$module_name; ?>&mf=display" >
            <?=_EZSHOP_SHOWCASE; ?>
            </a>
        </td>
        <td align="center">
            
	        <img src="modules/<?=$module_name; ?>/images/config.gif" border="0" align="absmiddle"/>
            <br/><br/>
            <a href="<?=$_SERVER['PHP_SELF']?>?modname=<?=$module_name; ?>&mf=config" >
            <?=_EZSHOP_CONFIG; ?>
            </a>
        </td>
      </tr>
      <tr>
        <td align="center">
            
	        <img src="modules/<?=$module_name; ?>/images/shipping.gif" border="0" align="absmiddle"/>
            <br/><br/>
            <a href="<?=$_SERVER['PHP_SELF']?>?modname=<?=$module_name; ?>&mf=shipping" >
            <?=_EZSHOP_SHIPPING; ?>
            </a>
        </td>
        <td align="center">
            
	        <img src="modules/<?=$module_name; ?>/images/payment.gif" border="0" align="absmiddle"/>
            <br/><br/>
            <a href="<?=$_SERVER['PHP_SELF']?>?modname=<?=$module_name; ?>&mf=payment" >
            <?=_EZSHOP_PAYMENT; ?>
            </a>
        </td>
        <td align="center">
            
	        <img src="modules/<?=$module_name; ?>/images/order.gif" border="0" align="absmiddle"/>
            <br/><br/>
            <a href="<?=$_SERVER['PHP_SELF']?>?modname=<?=$module_name; ?>&mf=order" >
            <?=_EZSHOP_ORDER; ?>
            </a>
        </td>
        <td align="center">
            
	        <img src="modules/<?=$module_name; ?>/images/back.gif" border="0" align="absmiddle"/>
            <br/><br/>
            <a href="module.php?modname=setting" >
            <?=_EZSHOP_BACK; ?>
            </a>
        </td>
      </tr>
    </table>
