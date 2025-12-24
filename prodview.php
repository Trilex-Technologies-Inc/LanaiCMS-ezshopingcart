<?
if (!eregi("module.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}

$module_name = basename(dirname(__FILE__));
$modfunction = "modules/$module_name/module.php";
include_once($modfunction);

$ezshop = new EzShop2();

// convert type
settype($_REQUEST['cid'], "integer");

$rs = $ezshop->getProductItem($_REQUEST['cid']);
$rsshconfig = $ezshop->getShowcaseConfig();
?>

<script type="text/javascript">
    function setimage(fname) {
        document.getElementById('mainProductImage').src = fname;
    }
</script>


<div class="col-md-8">
    <div class="article-content bg-white p-4 rounded shadow-sm">
        <? $ezshop->getJumpBox("c", $rs->fields['catId'], 0, $rs->fields['prdTitle']); ?>

        <!-- PRODUCT IMAGES -->
        <div class="col-md-5 text-center mb-4">
            <?
            if (file_exists($ezshop->cfg['datadir'].$sys_lanai->getPath()."ezshop".$sys_lanai->getPath()."img_".$rs->fields['prdId']."_big1.jpg")) {
                $imgname1 = "datacenter/ezshop/img_".$rs->fields['prdId']."_big1.jpg";
                $imgname2 = "datacenter/ezshop/img_".$rs->fields['prdId']."_big2.jpg";
            } else {
                $imgname1 = "modules/".$module_name."/images/kblackbox.jpg";
            }
            ?>

            <img id="mainProductImage"
                 src="<?= $imgname1; ?>"
                 class="img-fluid mb-3"
                 alt="<?= htmlspecialchars($rs->fields['prdTitle']); ?>">

            <? if (!empty($imgname2) && file_exists($ezshop->cfg['datadir'].$sys_lanai->getPath()."ezshop".$sys_lanai->getPath()."img_".$rs->fields['prdId']."_big2.jpg")) { ?>
                <div class="d-flex justify-content-center gap-2">
                    <span class="me-2"><?= _EZSHOP_CLICK2SEE; ?></span>
                    <a href="javascript:setimage('<?= $imgname1; ?>');">
                        <img src="<?= $imgname1; ?>" class="img-thumbnail" width="50">
                    </a>
                    <a href="javascript:setimage('<?= $imgname2; ?>');">
                        <img src="<?= $imgname2; ?>" class="img-thumbnail" width="50">
                    </a>
                </div>
            <? } ?>
        </div>

        <!-- PRODUCT INFO -->
        <div class="col-md-7">
            <h4 class="fw-bold"><?= $rs->fields['prdTitle']; ?></h4>

            <? if ($rsshconfig->fields['cfgDescriptionInDetail'] == 'y') { ?>
                <div class="mt-3">
                    <?= $rs->fields['prdDescription']; ?>
                </div>
            <? } ?>

            <!-- ADD TO CART CARD -->
            <div class="card mt-4">
                <div class="card-body">

                    <? if ($rsshconfig->fields['cfgPriceInDetail'] == 'y') { ?>
                        <p class="fw-bold mb-1">
                            <?= _PRICE; ?>
                            <? if ($rsshconfig->fields['cfgBestPriceInDetail'] == 'y') { ?>
                                <s class="text-muted">
                                    <?= $rsshconfig->fields['cfgCurrencySymbol']; ?>
                                    <?= number_format($rs->fields['prdPrice'], 2); ?>
                                </s>
                            <? } else { ?>
                                <?= $rsshconfig->fields['cfgCurrencySymbol']; ?>
                                <?= number_format($rs->fields['prdPrice'], 2); ?>
                            <? } ?>
                        </p>
                    <? } ?>

                    <? if ($rsshconfig->fields['cfgBestPriceInDetail'] == 'y') { ?>
                        <p class="text-danger fw-bold">
                            <?= _BESTPRICE; ?>
                            <?= $rsshconfig->fields['cfgCurrencySymbol']; ?>
                            <?= number_format($rs->fields['prdBestPrice'], 2); ?>
                        </p>
                    <? } ?>

                    <form name="addcart" action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" class="row g-2 align-items-center mt-3">
                        <input type="hidden" name="modname" value="<?= $module_name; ?>">
                        <input type="hidden" name="mf" value="viewcart">
                        <input type="hidden" name="ac" value="add">
                        <input type="hidden" name="cid" value="<?= $rs->fields['prdId']; ?>">

                        <div class="col-auto">
                            <label class="col-form-label"><?= _QUANTITY; ?></label>
                        </div>
                        <div class="col-auto">
                            <input name="crtQt" type="number" value="1" min="1" class="form-control form-control-sm" style="width:70px;">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <?= _EZSHOP_ADD2CART; ?>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- ACTION IMAGES -->
    <div class="row mt-4">
        <div class="col">
            <?php
            for ($i = 0; $i < 4; $i++) {
                if (file_exists($ezshop->cfg['datadir'].$sys_lanai->getPath()."ezshop".$sys_lanai->getPath()."img_".$rs->fields['prdId']."_action".($i+1).".jpg")) {
                    ?>
                    <img src="datacenter/ezshop/img_<?= $rs->fields['prdId']; ?>_action<?= ($i+1); ?>.jpg"
                         class="img-thumbnail me-2 mb-2">
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="sidebar">
        <?php
        $ezshop->getMenu(null, null, null);
        ?>
    </div>
</div>
