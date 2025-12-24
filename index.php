<?
if (!eregi("module.php", $_SERVER['PHP_SELF'])) {
    die("You can't access this file directly...");
}

$module_name = basename(dirname(__FILE__));
$modfunction = "modules/$module_name/module.php";
include_once($modfunction);

$ezshop = new EzShop2();

$cid  = isset($_REQUEST['cid'])  ? (int)$_REQUEST['cid']  : 0;
$pcid = isset($_REQUEST['pcid']) ? (int)$_REQUEST['pcid'] : 0;
$ac   = isset($_REQUEST['ac'])   ? $_REQUEST['ac']       : '';
?>

<a id="top"></a>

<div class="container my-4">

<?php
switch ($ac) {

    // ================= CATEGORY VIEW =================
    case "c":

        $ezshop->getJumpBox($ac, $cid, $pcid);
        $rscat = $ezshop->getCategoryItem($cid);

        if ($rscat->recordcount() > 0) {
        ?>
            <h4 class="mb-3"><?=$rscat->fields['catTitle']; ?></h4>
            <hr class="mb-4">

            <div class="row">
                <div class="col-12">
                    <?
                    $rsshconfig = $ezshop->getShowcaseConfig();
                    $ezshop->showItem(
                        $ezshop->getProductInCategory($cid),
                        $rsshconfig->fields['cfgColumnNumber']
                    );
                    ?>
                </div>
            </div>

            <div class="text-end mt-3">
                <a href="#top" class="btn btn-sm btn-outline-secondary">
                    <?=_GOTOP;?> ↑
                </a>
            </div>
        <?
        }
        break;


    // ================= ALL CATEGORIES =================
    case "a":

        $rspcat = $ezshop->getCategoryParent();
        $rsshconfig = $ezshop->getShowcaseConfig();
        $ezshop->getJumpBox($ac, 0);

        while (!$rspcat->EOF) {
        ?>
            <h4 class="mt-4 mb-3"><?=$rspcat->fields['catTitle']; ?></h4>
            <hr class="mb-4">

            <div class="row">
                <div class="col-12">
                    <?=$ezshop->showItem(
                        $ezshop->getProductInCategory($rspcat->fields['catId']),
                        $rsshconfig->fields['cfgColumnNumber']
                    ); ?>
                </div>
            </div>

            <div class="text-end mt-3">
                <a href="#top" class="btn btn-sm btn-outline-secondary">
                    <?=_GOTOP;?> ↑
                </a>
            </div>

            <?
            $rssub = $ezshop->getSubCategoryByParent($rspcat->fields['catId']);
            if ($rssub->recordcount() > 0) {
                while (!$rssub->EOF) {
                ?>
                    <h5 class="mt-4 mb-3 ps-3 border-start border-3">
                        <?=$rssub->fields['catTitle']; ?>
                    </h5>
                    <hr class="mb-4">

                    <div class="row">
                        <div class="col-12">
                            <?=$ezshop->showItem(
                                $ezshop->getProductInCategory($rssub->fields['catId']),
                                $rsshconfig->fields['cfgColumnNumber']
                            ); ?>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <a href="#top" class="btn btn-sm btn-outline-secondary">
                            <?=_GOTOP;?> ↑
                        </a>
                    </div>
                <?
                    $rssub->movenext();
                }
            }
            $rspcat->movenext();
        }
        break;


    // ================= SHOWCASE =================
    default:

        $rsshowcase = $ezshop->getProductShowcase();
        $rsshconfig = $ezshop->getShowcaseConfig();
        $ezshop->getJumpBox();
        ?>

        <h4 class="mb-3"><?=_EZSHOP_SHOWCASE;?></h4>
        <hr class="mb-4">

        <div class="row">
            <div class="col-12">
                <?=$ezshop->showItem(
                    $rsshowcase,
                    $rsshconfig->fields['cfgColumnNumber']
                ); ?>
            </div>
        </div>

        <div class="text-end mt-3">
            <a href="#top" class="btn btn-sm btn-outline-secondary">
                <?=_GOTOP;?> ↑
            </a>
        </div>

        <?
}
?>

</div>
