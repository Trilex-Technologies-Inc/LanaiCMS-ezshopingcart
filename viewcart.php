<?
if (!eregi("module.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}

$sbtotal = 0;

$module_name = basename(dirname(__FILE__));
$modfunction = "modules/$module_name/module.php";
include_once($modfunction);

$ezcart = new EzShoppingCart();

/* ACTIONS */
switch ($_REQUEST['ac']) {
    case "add":
        $ezcart->setItemAddToCart($_REQUEST['cid'], $_REQUEST['crtQt']);
        break;

    case "remove":
        $ezcart->setItemRemoveFromCart($_REQUEST['cid']);
        break;

    case "update":
        $i = 0;
        foreach ($_REQUEST['crtQt'] as $qitem) {
            if ($qitem == 0) {
                $ezcart->setItemRemoveFromCart($_REQUEST['prdId'][$i]);
            } else {
                $ezcart->setItemUpdateCart($_REQUEST['prdId'][$i], $qitem);
            }
            $i++;
        }
        break;
}

/* GET CART */
$rs = $ezcart->getProductInCart(0);
?>

<h4 class="mb-4"><?=_EZSHOP_YOUR_CART; ?></h4>

<script type="text/javascript">
    function updatecart() {
        document.cart.ac.value = "update";
        document.cart.submit();
    }
    function removeitem(cid) {
        if (confirm("<?=_DELETE_QUESTION; ?>")) {
            document.cart.cid.value = cid;
            document.cart.ac.value = "remove";
            document.cart.submit();
        }
    }
    function back2shop() {
        location.href = "module.php?modname=ezshopingcart&ac=a";
    }
    function checkout() {
        location.href = "module.php?modname=ezshopingcart&mf=checkoutcart";
    }
</script>

<form name="cart" action="<?=$_SERVER['PHP_SELF']; ?>" method="POST">
<input type="hidden" name="modname" value="<?=$module_name; ?>">
<input type="hidden" name="mf" value="viewcart">
<input type="hidden" name="cid" value="0">
<input type="hidden" name="ac" value="update">

<div class="container">
    <div class="row fw-bold border-bottom pb-2 mb-3">
        <div class="col-2 text-center"><?=_QTY; ?></div>
        <div class="col-6"><?=_DESCRIPTION; ?></div>
        <div class="col-2 text-end"><?=_PRICE; ?></div>
        <div class="col-2 text-end"><?=_AMOUNT; ?></div>
    </div>

    <? while (!$rs->EOF) { ?>
        <?
            $rspitem = $ezcart->getProductItem($rs->fields['prdId']);
            $amount = $rs->fields['prdPrice'] * $rs->fields['crtQuantity'];
            $sbtotal += $amount;
        ?>
        <div class="row align-items-center border-bottom py-2">
            <div class="col-2 text-center">
                <input name="crtQt[]" value="<?=$rs->fields['crtQuantity']; ?>"
                       class="form-control form-control-sm text-center mb-1" type="number" min="0">
                <input name="prdId[]" type="hidden" value="<?=$rs->fields['prdId']; ?>">
                <a href="javascript:removeitem(<?=$rs->fields['prdId']; ?>);"
                   class="text-danger small text-decoration-none">
                    <?=_REMOVE; ?>
                </a>
            </div>

            <div class="col-6">
                <?=$rspitem->fields['prdTitle']; ?>
            </div>

            <div class="col-2 text-end">
                <?=$rs->fields['prdPrice']; ?>
            </div>

            <div class="col-2 text-end fw-bold">
                <?=$amount; ?>
            </div>
        </div>
        <? $rs->movenext(); ?>
    <? } ?>

    <? if ($rs->recordcount() > 0) { ?>
        <div class="row mt-4 align-items-center">
            <div class="col-md-6 mb-2">
                <button type="button" class="btn btn-outline-secondary btn-sm"
                        onclick="updatecart();">
                    <?=_UPDATE; ?>
                </button>
            </div>
            <div class="col-md-6 text-end">
                <h5><?=_SUBTOTAL; ?>: <strong><?=$sbtotal; ?></strong></h5>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col text-end">
                <button type="button" class="btn btn-outline-secondary me-2"
                        onclick="back2shop();">
                    <?=_BACK2SHOP; ?>
                </button>
                <button type="button" class="btn btn-primary"
                        onclick="checkout();">
                    <?=_CHECKOUT; ?>
                </button>
            </div>
        </div>
    <? } ?>
</div>
</form>
