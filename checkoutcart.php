<?php
if (!eregi("module.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}



$module_name = basename(dirname(__FILE__));
$modfunction = "modules/$module_name/module.php";
include_once($modfunction);

$ezcart = new EzShoppingCart();
$sbtotal = 0;
$ezshop = new EzShop2();

if (!empty($_REQUEST['ac']) && $_REQUEST['ac'] == "login") {
    $userCaptcha = isset($_REQUEST['captext']) ? trim($_REQUEST['captext']) : '';
    if (empty($_SESSION['captcha']) || strcasecmp($userCaptcha, $_SESSION['captcha']) != 0) {
        $sys_lanai->getErrorBox("Captcha is incorrect.");
            echo '<div class="mt-3"><button class="btn btn-secondary" onclick="location.href=\'module.php?modname=ezshopingcart&mf=checkoutcart\'">Back</button></div>';

    } else {
        unset($_SESSION['captcha']);
        $xuid = $sys_lanai->getUserAuthentication($_REQUEST['username'], $_REQUEST['password']);
        if ($xuid > 0) {
            $_SESSION['uid'] = $xuid;
            $sys_lanai->go2Page("module.php?modname=".$module_name."&mf=checkoutcart");
        } else {
            $sys_lanai->getErrorBox(_LOGIN_FAIL);
        }
    }
} else {
?>

<div class="col-md-12">

    <?php
    $ezshop->getMenu(null, null, null);
    ?>

</div>
<div class="col-md-12">
    <div class="article-content bg-white p-4 rounded shadow-sm">

<?php if (!$sys_lanai->isUserLogin()) { ?>

    <p class="mb-3"><?=_EZSHOP_LOGIN_INSTRUCTION; ?></p>

    <form action="module.php" method="POST" class="card p-4 col-md-12">
        <input type="hidden" name="modname" value="<?=$module_name; ?>">
        <input type="hidden" name="mf" value="checkoutcart">
        <input type="hidden" name="ac" value="login">

        <div class="mb-3">
            <label class="form-label"><?=_USERNAME; ?></label>
            <input name="username" type="text" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label"><?=_PASSWORD; ?></label>
            <input name="password" type="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label"><?=_MEMBER_CAPTEXT; ?></label>
            <div class="d-flex align-items-center gap-2">
                <input type="text" name="captext" class="form-control w-auto" size="12" maxlength="5" placeholder="<?= _ENTER_CAPTCHA; ?>" required>
                <img src="images/captcha.php?hash=<?= md5(time()); ?>" alt="Captcha" class="border rounded">
            </div>
            <small class="text-muted">Enter the captcha text as shown above.</small>
        </div>

        <div class="mb-3 small">
            <a href="module.php?modname=member&mf=memsignup" target="_blank">
                <?=_USER_SIGNUP; ?>
            </a>
            &nbsp;|&nbsp;
            <a href="module.php?modname=member&mf=memlostpass" target="_blank">
                <?=_USER_LOST; ?>
            </a>
        </div>

        <button type="submit" class="btn btn-primary">
            <?=_LOGIN; ?>
        </button>
    </form>

<?php } else { ?>

    <h5 class="mb-3"><?=_EZSHOP_SHIPPING_TO; ?></h5>
    <p><?=_EZSHOP_SHIPPING_TO_INSTRUCTION; ?></p>

    <?php $rsmember = $ezcart->getMemberInfo(); ?>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong><?=_EZSHOP_FNAME; ?>:</strong> <?=$rsmember->fields['userFname']; ?></p>
            <p><strong><?=_EZSHOP_LNAME; ?>:</strong> <?=$rsmember->fields['userLname']; ?></p>
            <p><strong><?=_EZSHOP_ADDRESS1; ?>:</strong> <?=$rsmember->fields['userAddress1']; ?></p>
            <p><strong><?=_EZSHOP_ADDRESS2; ?>:</strong> <?=$rsmember->fields['userAddress2']; ?></p>
            <p><strong><?=_EZSHOP_CITY; ?>:</strong> <?=$rsmember->fields['userCity']; ?></p>
            <p><strong><?=_EZSHOP_STATE; ?>:</strong> <?=$rsmember->fields['userState']; ?></p>
            <p><strong><?=_EZSHOP_ZIPCODE; ?>:</strong> <?=$rsmember->fields['userZipcode']; ?></p>
            <p><strong><?=_EZSHOP_COUNTRY; ?>:</strong> <?=$rsmember->fields['cntId']; ?></p>
        </div>
    </div>

    <h5 class="mb-3"><?=_EZSHOP_CART; ?></h5>

    <?php $rs = $ezcart->getProductInCart(0); ?>

    <div class="list-group mb-4">
        <?php while (!$rs->EOF) {
            $rspitem = $ezcart->getProductItem($rs->fields['prdId']);
            $amount = $rs->fields['prdPrice'] * $rs->fields['crtQuantity'];
            $sbtotal += $amount;
        ?>
        <div class="list-group-item d-flex justify-content-between">
            <div>
                <strong><?=$rspitem->fields['prdTitle']; ?></strong><br>
                <?=_QTY; ?>: <?=$rs->fields['crtQuantity']; ?>
            </div>
            <div class="text-end">
                <?=$rs->fields['prdPrice']; ?><br>
                <strong><?=$amount; ?></strong>
            </div>
        </div>
        <?php $rs->movenext(); } ?>
    </div>

    <div class="text-end mb-4">
        <h5><?=_SUBTOTAL; ?>: <strong><?=$sbtotal; ?></strong></h5>
    </div>

    <form action="<?=$_SERVER['PHP_SELF']; ?>" method="POST">
        <input type="hidden" name="modname" value="<?=$module_name; ?>">
        <input type="hidden" name="mf" value="checkoutconfirm">

        <h5 class="mb-3"><?=_EZSHOP_SHIPPING_METHOD; ?></h5>
        <?php $rsship = $ezcart->getShippingMethod(); ?>
        <?php while (!$rsship->EOF) { ?>
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="shpId" value="<?=$rsship->fields['shpId']; ?>" checked>
                <label class="form-check-label">
                    <strong><?=$rsship->fields['shpTitle']; ?></strong><br>
                    <?=$rsship->fields['shpDescription']; ?>
                </label>
            </div>
        <?php $rsship->movenext(); } ?>

        <h5 class="mt-4 mb-3"><?=_EZSHOP_PAYMENT_METHOD; ?></h5>
        <?php $rspay = $ezcart->getPaymentMethod(); ?>
        <?php while (!$rspay->EOF) { ?>
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="payId" value="<?=$rspay->fields['payId']; ?>" checked>
                <label class="form-check-label">
                    <strong><?=$rspay->fields['payTitle']; ?></strong><br>
                    <?=$rspay->fields['payDescription']; ?>
                </label>
            </div>
        <?php $rspay->movenext(); } ?>

        <h5 class="mt-4"><?=_EZSHOP_REMARK; ?></h5>
        <textarea name="crtRemark" class="form-control mb-4" rows="4"></textarea>

        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-outline-secondary" onclick="location.href='module.php?modname=ezshopingcart&mf=viewcart';">
                <?=_BACK2CART; ?>
            </button>

            <?php if (!empty($rsmember->fields['userAddress1']) && !empty($rsmember->fields['userCity']) && !empty($rsmember->fields['userState']) && !empty($rsmember->fields['userZipcode'])) { ?>
                <button type="submit" class="btn btn-success">
                    <?=_CHECKOUT_PROCEED; ?>
                </button>
            <?php } ?>
        </div>
    </form>

<?php } ?>

</div>

<?php } ?>
