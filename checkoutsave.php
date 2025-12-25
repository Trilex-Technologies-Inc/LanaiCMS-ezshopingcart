<?php
// CHANGED: eregi() is deprecated
if (!preg_match("/module\.php/i", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}

$module_name = basename(dirname(__FILE__));
$modfunction = "modules/$module_name/module.php";
include_once($modfunction);

$cartstr  = '';
$subtotal = 0;
$tqty     = 0;
$ezcart   = new EzShoppingCart();
$ezshop = new EzShop2();
?>
<div class="col-md-12">

    <?php
    $ezshop->getMenu(null, null, null);
    ?>

</div>
<div class="col-md-12">
    <div class="article-content bg-white p-4 rounded shadow-sm">
<span class="txtContentTitle"><?=_EZSHOP_CHECKOUT_RESULT;?></span><br/><br/>
<?=_EZSHOP_CHECKOUT_RESULT_INSTRUCTION;?><br/><br/>

<?php
$result = $ezcart->setCartSave(
    $_REQUEST['crtref'],
    $_REQUEST['shpId'],
    $_REQUEST['payId'],
    $_REQUEST['crtRemark']
);

if (!empty($result)) {
?>
<img src="theme/<?=$cfg['theme'];?>/images/ok.gif" align="absmiddle"/>
<?= _EZSHOP_SAVE_CART_COMPLETE; ?>

<?php
$crtitem    = $ezcart->getCartByRef($_REQUEST['crtref']);
$cartdetail = $ezcart->getCartDetailByRef($_REQUEST['crtref']);
$memitem   = $ezcart->getMemberInfo();

while (!$cartdetail->EOF) {
    $subtotal += ($cartdetail->fields[3] * $cartdetail->fields['crtQuantity']);
    $tqty     += $cartdetail->fields['crtQuantity'];
    $cartdetail->movenext();
}

$shppvalue = $ezcart->getShippingCost($crtitem->fields['shpId'], $tqty);
$total     = $subtotal + $shppvalue;

// REQUIRED FOR PAYPAL
$prd = "Product Payment!";
$amt = number_format($total, 2, '.', '');
$inv = sprintf("%010d", $crtitem->fields['crtId']);

// Load payment plugin
$payitem = $ezcart->getPaymentMethod($_REQUEST['payId']);
if ($payitem->recordcount() > 0 && !empty($payitem->fields['payModule'])) {
    echo '<br><br><span style="color:red">*</span>';
    require_once(
        "modules/".$module_name."/plugin/".
        $payitem->fields['payModule']."/".
        $payitem->fields['payModule'].".php"
    );
}

} else {
?>
<img src="theme/<?=$cfg['theme'];?>/images/worning.gif" align="absmiddle"/>
<?= _EZSHOP_CANNOT_SAVE_CART; ?>
<?php } ?>
    </div>
</div>
