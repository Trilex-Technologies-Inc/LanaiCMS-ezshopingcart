<?
if (!eregi("module.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}

$module_name = basename(dirname(__FILE__));
$modfunction = "modules/$module_name/module.php";
include_once($modfunction);

$ezcart = new EzShoppingCart();
if (!$sys_lanai->isUserLogin()) {
    $sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=checkputcart");
}
?>

<!-- BOOTSTRAP CONTAINER -->
<div class="container my-4">


<h5 class="mb-3"><?=_EZSHOP_SHIPPING_TO; ?></h5>
<p class="text-muted"><?=_EZSHOP_SHIPPING_TO_INSTRUCTION; ?></p>

<? $rsmember = $ezcart->getMemberInfo(); ?>

<div class="card mb-4">
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <tbody>
                <tr><th><?=_EZSHOP_FNAME; ?></th><td><?=$rsmember->fields['userFname']; ?></td></tr>
                <tr><th><?=_EZSHOP_LNAME; ?></th><td><?=$rsmember->fields['userLname']; ?></td></tr>
                <tr><th><?=_EZSHOP_ADDRESS1; ?></th><td><?=$rsmember->fields['userAddress1']; ?></td></tr>
                <tr><th><?=_EZSHOP_ADDRESS2; ?></th><td><?=$rsmember->fields['userAddress2']; ?></td></tr>
                <tr><th><?=_EZSHOP_CITY; ?></th><td><?=$rsmember->fields['userCity']; ?></td></tr>
                <tr><th><?=_EZSHOP_STATE; ?></th><td><?=$rsmember->fields['userState']; ?></td></tr>
                <tr><th><?=_EZSHOP_COUNTRY; ?></th><td><?=$rsmember->fields['cntId']; ?></td></tr>
                <tr><th><?=_EZSHOP_ZIPCODE; ?></th><td><?=$rsmember->fields['userZipcode']; ?></td></tr>
            </tbody>
        </table>
    </div>
</div>

<?

$rs = $ezcart->getProductInCart(0);
$sbtotal = 0;
$tqty = 0;
?>

<h5 class="mb-3"><?=_EZSHOP_CART; ?></h5>

<div class="table-responsive mb-4">
<table class="table table-bordered align-middle">
    <thead class="table-light">
        <tr>
            <th class="text-center"><?=_QTY; ?></th>
            <th><?=_DESCRIPTION; ?></th>
            <th class="text-end"><?=_PRICE; ?></th>
            <th class="text-end"><?=_AMOUNT; ?></th>
        </tr>
    </thead>
    <tbody>
    <?
    while (!$rs->EOF) {
        $rspitem = $ezcart->getProductItem($rs->fields['prdId']);
        $qty = $rs->fields['crtQuantity'];
        $price = $rs->fields['prdPrice'];
        $amount = $price * $qty;

        $tqty += $qty;
        $sbtotal += $amount;
    ?>
        <tr>
            <td class="text-center"><?=$qty; ?></td>
            <td><?=$rspitem->fields['prdTitle']; ?></td>
            <td class="text-end"><?=$price; ?></td>
            <td class="text-end"><?=$amount; ?></td>
        </tr>
    <?
        $rs->movenext();
    }
    ?>
    </tbody>
    <tfoot class="table-light">
        <tr>
            <th colspan="3" class="text-end"><?=_SUBTOTAL; ?></th>
            <th class="text-end"><?=$sbtotal; ?></th>
        </tr>
        <tr>
            <th colspan="3" class="text-end"><?=_SHIPPING; ?></th>
            <th class="text-end">
                <? $shpcost = $ezcart->getShippingCost($_REQUEST['shpId'], $tqty); ?>
                <?=sprintf("%01.2f", $shpcost); ?>
            </th>
        </tr>
        <tr>
            <th colspan="3" class="text-end"><?=_TOTAL; ?></th>
            <th class="text-end fw-bold">
                <? $amt = $sbtotal + $shpcost; ?>
                <?=sprintf("%01.2f", $amt); ?>
            </th>
        </tr>
    </tfoot>
</table>
</div>

<script type="text/javascript">
function checkoutcart() {
    location.href="module.php?modname=ezshopingcart&mf=checkoutcart";
}
</script>

<form action="<?=$_SERVER['PHP_SELF']; ?>" method="POST">
<input type="hidden" name="modname" value="<?=$module_name; ?>">
<input type="hidden" name="mf" value="checkoutsave">
<input type="hidden" name="crtref" value="<?=$_COOKIE['cartref']; ?>">
<input type="hidden" name="amt" value="<?=$amt; ?>">
<input type="hidden" name="clrcook" value="clear">

<?
// ================== SHIPPING METHOD ==================
$rsship = $ezcart->getShippingMethod($_REQUEST['shpId']);
?>

<h5 class="mb-3"><?=_EZSHOP_SHIPPING_METHOD; ?></h5>

<div class="card mb-4">
    <div class="card-body">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="shpId"
                   value="<?=$rsship->fields['shpId']; ?>" checked>
            <label class="form-check-label">
                <strong><?=$rsship->fields['shpTitle']; ?></strong><br>
                <small class="text-muted"><?=$rsship->fields['shpDescription']; ?></small>
            </label>
        </div>
    </div>
</div>

<?

$rspay = $ezcart->getPaymentMethod($_REQUEST['payId']);
?>

<h5 class="mb-3"><?=_EZSHOP_PAYMENT_METHOD; ?></h5>

<div class="card mb-4">
    <div class="card-body">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="payId"
                   value="<?=$rspay->fields['payId']; ?>" checked>
            <label class="form-check-label">
                <strong><?=$rspay->fields['payTitle']; ?></strong><br>
                <small class="text-muted"><?=$rspay->fields['payDescription']; ?></small>
            </label>
        </div>
    </div>
</div>



<h5 class="mb-3"><?=_EZSHOP_REMARK; ?></h5>

<div class="card mb-4">
    <div class="card-body">
        <?=$_REQUEST['crtRemark']; ?>
        <input type="hidden" name="crtRemark" value="<?=nl2br($_REQUEST['crtRemark']); ?>">
    </div>
</div>

<div class="d-flex gap-2">
    <button type="button" class="btn btn-secondary" onclick="checkoutcart()">
        <?=_BACKCHECKOUT; ?>
    </button>

    <button type="submit" class="btn btn-primary">
        <?=_CHECKOUT_CONFIRM; ?>
    </button>
</div>

</form>
</div>
