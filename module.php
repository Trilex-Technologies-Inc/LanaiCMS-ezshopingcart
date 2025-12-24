<?php
include_once("class.OrderPager.php");
include_once("class.PaymentPager.php");
include_once("class.ShippingPager.php");
include_once("class.ShoppingCart.php");
include_once("class.CategoryPager2.php");
include_once("class.ProductPager2.php");

class EzShop2 {

    var $uid;
    var $db;
    var $cfg;
    var $_sql;
    var $version="0.2";

    function EzShop2() {
        global $db,$cfg;
        $this->db=$db;
        $this->cfg=$cfg;
        if (!empty($_SESSION['uid']))
            $this->uid=$_SESSION['uid'];
    }

    function getProductItem($cid=0) {
        if ($cid==0) {
            $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_product_item
                        WHERE prdActive='y'
                        ORDER BY prdId DESC";
            $this->_sql=$sql;
        } else {
            $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_product_item
                       WHERE prdId=$cid";
        }
        return ($this->db->execute($sql));
    }

    function getProductByTitle($title) {
        $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_product_item
                    WHERE prdTitle='".$title."'
                    ORDER BY prdId DESC";
        return ($this->db->execute($sql));
    }

    function getProduct() {
        $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_product_item
                    ORDER BY prdId ASC";
        $this->_sql=$sql;
        return ($this->db->execute($sql));
    }

    function getProductShowcase() {
        $sql="SELECT ".$this->cfg['tablepre']."ezshop_showcase.*,".$this->cfg['tablepre']."ezshop_product_item.*
                    FROM ".$this->cfg['tablepre']."ezshop_showcase,".$this->cfg['tablepre']."ezshop_product_item
                    WHERE ".$this->cfg['tablepre']."ezshop_showcase.prdId=".$this->cfg['tablepre']."ezshop_product_item.prdId AND
                          ".$this->cfg['tablepre']."ezshop_product_item.prdActive='y'
                    ORDER BY ".$this->cfg['tablepre']."ezshop_showcase.scsId ASC";
        $this->_sql=$sql;
        return ($this->db->execute($sql));
    }

    function getProductAvailable($avList) {
        $avitem='';
        foreach ($avList as $item) {
            $avitem.=$item.",";
        }
        $avitem.="0";
        $sql="SELECT ".$this->cfg['tablepre']."ezshop_product_item. *
                    FROM ".$this->cfg['tablepre']."ezshop_product_item
                    WHERE ".$this->cfg['tablepre']."ezshop_product_item.prdId NOT IN ($avitem) AND
                            ".$this->cfg['tablepre']."ezshop_product_item.prdActive='y'
                    ORDER BY  ".$this->cfg['tablepre']."ezshop_product_item.prdId DESC ";
        return ($this->db->execute($sql));
    }

    function setShowcaseDelete() {
        $sql="DELETE FROM ".$this->cfg['tablepre']."ezshop_showcase ";
        $rs=$this->db->execute($sql);
        return $rs;
    }

    function setShowcaseNew($pid) {
        $sql="INSERT INTO ".$this->cfg['tablepre']."ezshop_showcase
                    (prdId) VALUES ($pid) ";
        $rs=$this->db->execute($sql);
        return $rs;
    }

    function getShowcaseConfig() {
        $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_showcase_config
                    LIMIT 1";
        $rs=$this->db->execute($sql);
        return $rs;
    }

    function setShowcaseConfig($cfgTitleInShowcase,$cfgDescriptionInShowcase,$cfgPriceInShowcase,$cfgBestPriceInShowcase,$cfgTitleInDetail,$cfgDescriptionInDetail,$cfgPriceInDetail,$cfgBestPriceInDetail,$cfgCurrencySymbol,$cfgColumnNumber){
        $sql="UPDATE ".$this->cfg['tablepre']."ezshop_showcase_config SET
					cfgTitleInShowcase='".$cfgTitleInShowcase."',
					cfgDescriptionInShowcase='".$cfgDescriptionInShowcase."',
					cfgPriceInShowcase='".$cfgPriceInShowcase."',
					cfgBestPriceInShowcase='".$cfgBestPriceInShowcase."',
					cfgTitleInDetail='".$cfgTitleInDetail."',
					cfgDescriptionInDetail='".$cfgDescriptionInDetail."',
					cfgPriceInDetail='".$cfgPriceInDetail."',
					cfgBestPriceInDetail='".$cfgBestPriceInDetail."',
					cfgCurrencySymbol='".$cfgCurrencySymbol."',
					cfgColumnNumber=$cfgColumnNumber
				   ";
        $rs=$this->db->execute($sql);
        return $rs;
    }

    function setProductNew($catId,$prdTitle,$prdDescription,$prdPrice,$prdBestPrice,$prdActive) {
        $sql="INSERT INTO  ".$this->cfg['tablepre']."ezshop_product_item
                    (catId, prdTitle, prdDescription, prdPrice, prdBestPrice, prdActive, prdCreate)
                    VALUES ($catId,'".$prdTitle."','".$prdDescription."',$prdPrice,$prdBestPrice,'".$prdActive."',NOW()) ";
        return ($this->db->execute($sql));
    }

    function setProductEdit($prdId,$catId,$prdTitle,$prdDescription,$prdPrice,$prdBestPrice,$prdActive) {
        $sql="UPDATE ".$this->cfg['tablepre']."ezshop_product_item SET
                        catId=$catId,prdTitle='".$prdTitle."',prdDescription='".$prdDescription."',
                        prdPrice=$prdPrice,prdBestPrice=$prdBestPrice,prdActive='".$prdActive."',prdCreate=NOW()
                        WHERE prdId=$prdId ";
        return ($this->db->execute($sql));
    }

    function setProductDelete($mid){
        $sql="DELETE FROM ".$this->cfg['tablepre']."ezshop_product_item
					WHERE prdId=".$mid;
        $rs=$this->db->execute($sql);
        return $rs;
    }

    function setProductActive($mid,$value){
        $sql="UPDATE ".$this->cfg['tablepre']."ezshop_product_item
					SET prdActive='".$value."'
					WHERE prdId=".$mid;
        $rs=$this->db->execute($sql);
        return $rs;
    }

    function getCategoryItem($cid) {
        if((empty($cid)) OR ($cid==0)) {
            $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_category
                        WHERE catActive='y'
                        ORDER BY catId ASC";
        } else {
            $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_category
                        WHERE catId=$cid ";
        }
        return ($this->db->execute($sql));
    }

    function getCategory() {
        $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_category
                          ORDER BY catParentId,catId ASC";
        $this->_sql=$sql;
        return ($this->db->execute($sql));
    }

    function getOrder($filter=null) {
        $sql="SELECT *
                    FROM ".$this->cfg['tablepre']."ezshop_cart,".$this->cfg['tablepre']."ezshop_cart_item,".$this->cfg['tablepre']."user
                    WHERE ".$this->cfg['tablepre']."ezshop_cart.crtId=".$this->cfg['tablepre']."ezshop_cart_item.crtId AND
                            ".$this->cfg['tablepre']."ezshop_cart.userId=".$this->cfg['tablepre']."user.userId
                            ".$filter."
                    GROUP BY ".$this->cfg['tablepre']."ezshop_cart.crtId
                    ORDER BY ".$this->cfg['tablepre']."ezshop_cart.crtId DESC";
        $this->_sql=$sql;
        return ($this->db->execute($sql));
    }

    function getShipping() {
        $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_shipping
                          ORDER BY shpId ASC";
        $this->_sql=$sql;
        return ($this->db->execute($sql));
    }

    function getPayment() {
        $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_payment
                          ORDER BY payId ASC";
        $this->_sql=$sql;
        return ($this->db->execute($sql));
    }

    function getCategoryParent() {
        $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_category
                    WHERE catParentId=0 AND catActive='y'
                    ORDER BY catTitle ASC";
        return ($this->db->execute($sql));
    }

    function getSubCategoryByParent($pid) {
        $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_category
                    WHERE catParentId=$pid AND catActive='y'
                    ORDER BY catTitle ASC";
        return ($this->db->execute($sql));
    }

    function getProductInCategory($mid) {
        $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_product_item
					WHERE catId=".$mid." AND prdActive='y'
                    ORDER BY prdTitle ASC";
        $rs=$this->db->execute($sql);
        return $rs;
    }

    function getOrderList($row=50){
        $this->getOrder();
        $pager=new OrderPager($this->db,$this->_sql,true);
        $pager->Render($row);
    }

    function getShippingList($row=50){
        $this->getShipping();
        $pager=new ShippingPager($this->db,$this->_sql,true);
        $pager->Render($row);
    }

    function getPaymentList($row=50){
        $this->getPayment();
        $pager=new PaymentPager($this->db,$this->_sql,true);
        $pager->Render($row);
    }

    function getCategoryList($row=50){
        $this->getCategory();
        $pager=new CategoryPager2($this->db,$this->_sql,true);
        $pager->Render($row);
    }

    function getProductList($row=50){
        $this->getProduct();
        $pager=new ProductPager2($this->db,$this->_sql,true);
        $pager->Render($row);
    }

   public function setPaymentNew($payTitle, $payDescription, $payModule, $payToken, $paySecret, $currency) {

    $payTitle = $this->db->escape($payTitle);
    $payDescription = $this->db->escape($payDescription);
    $payModule = $this->db->escape($payModule);
    $payToken = $this->db->escape($payToken);
    $paySecret = $this->db->escape($paySecret);
    $currency = $this->db->escape($currency);

    $sql = "INSERT INTO ".$this->cfg['tablepre']."ezshop_payment
        (payTitle, payDescription, payModule, payToken, paySecret, currency)
        VALUES ('$payTitle', '$payDescription', '$payModule', '$payToken', '$paySecret', '$currency')";

    $rs = $this->db->execute($sql);
    return $rs;
}



    function setPaymentDelete($mid) {
        $sql="DELETE FROM ".$this->cfg['tablepre']."ezshop_payment
                    WHERE payId=$mid";
        $rs=$this->db->execute($sql);
        return $rs;
    }

    public function setPaymentEdit($payId, $payTitle, $payDescription, $payModule, $payToken, $paySecret, $currency) {

    $payId = (int)$payId; 
    $payTitle = $this->db->escape($payTitle);
    $payDescription = $this->db->escape($payDescription);
    $payModule = $this->db->escape($payModule);
    $payToken = $this->db->escape($payToken);
    $paySecret = $this->db->escape($paySecret);
    $currency = $this->db->escape($currency);

    $sql = "UPDATE ".$this->cfg['tablepre']."ezshop_payment SET
            payTitle='$payTitle',
            payDescription='$payDescription',
            payModule='$payModule',
            payToken='$payToken',
            paySecret='$paySecret',
            currency='$currency'
        WHERE payId=$payId";

    $rs = $this->db->execute($sql);
    return $rs;
}



    function setOrderStatus($mid,$state="P") {
        $sql="UPDATE ".$this->cfg['tablepre']."ezshop_cart SET
                    crtStatus='".$state."'
                    WHERE crtId=$mid";
        $rs=$this->db->execute($sql);
        return $rs;
    }

    function setShippingNew($shpTitle,$shpDescription,$shpRateType,$shpRateTable,$shpRateTableType,$shpFixRate){
        $sql="INSERT INTO ".$this->cfg['tablepre']."ezshop_shipping
                    (shpTitle,shpDescription,shpRateType,shpRateTable,shpRateTableType,shpFixRate)
                    VALUES ('".$shpTitle."','".$shpDescription."','".$shpRateType."','".$shpRateTable."','".$shpRateTableType."',".$shpFixRate.") ";
        $rs=$this->db->execute($sql);
        return $rs;
    }

    function setShippingEdit($shpId,$shpTitle,$shpDescription,$shpRateType,$shpRateTable,$shpRateTableType,$shpFixRate){
        $sql="UPDATE ".$this->cfg['tablepre']."ezshop_shipping SET
                    shpTitle='".$shpTitle."',shpDescription='".$shpDescription."',shpRateType='".$shpRateType."',shpRateTable='".$shpRateTable."',shpRateTableType='".$shpRateTableType."',shpFixRate=".$shpFixRate."
                    WHERE shpId=$shpId";
        $rs=$this->db->execute($sql);
        return $rs;
    }

    function setShippingDelete($mid) {
        $sql="DELETE FROM ".$this->cfg['tablepre']."ezshop_shipping
                    WHERE shpId=$mid";
        $rs=$this->db->execute($sql);
        return $rs;
    }

    function setCategoryNew($catParentId,$catTitle,$catDescription,$catActive){
        $sql="INSERT INTO ".$this->cfg['tablepre']."ezshop_category
                    (catParentId,catTitle,catDescription,catActive)
                    VALUES ($catParentId,'".$catTitle."','".$catDescription."','".$catActive."') ";
        $rs=$this->db->execute($sql);
        return $rs;
    }

    function setCategoryEdit($catId,$catParentId,$catTitle,$catDescription,$catActive) {
        $sql="UPDATE ".$this->cfg['tablepre']."ezshop_category SET
                    catParentId=$catParentId,catTitle='".$catTitle."',catDescription='".$catDescription."',catActive='".$catActive."'
                    WHERE catId=$catId";
        $rs=$this->db->execute($sql);
        return $rs;
    }

    function setCategoryActive($mid,$value){
        $sql="UPDATE ".$this->cfg['tablepre']."ezshop_category
					SET catActive='".$value."'
					WHERE catId=".$mid;
        $rs=$this->db->execute($sql);
        return $rs;
    }

    function setCategoryDelete($mid){
        $sql="DELETE FROM ".$this->cfg['tablepre']."ezshop_category
					WHERE catId=".$mid;
        $rs=$this->db->execute($sql);
        return $rs;
    }

    function getCategoryParentCombo($name,$value){
        $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_category
                    WHERE catParentId=0
                    ORDER BY catParentId,catId ASC";
        $rs=$this->db->execute($sql);

        ?>
    <select name="<?=$name; ?>" class="form-control" >
        <option value="0" ><?=_NONE; ?></option>
        <?php
        while (!$rs->EOF) {
            if ($value==$rs->fields['catId']) {
                $check="selected";
            } else {
                $check="";
            }
            ?><option value="<?=$rs->fields['catId']; ?>" <?=$check; ?> ><?=$rs->fields['catTitle']; ?></option><?php
            $rs->movenext();
        }
        ?></select><?php
    }

    function getCategoryCombo($name, $value) {
    $sql = "SELECT * FROM ".$this->cfg['tablepre']."ezshop_category
            ORDER BY catParentId, catId ASC";
    $rs = $this->db->execute($sql);
    ?>
    <div class="mb-3">
        <select name="<?= $name; ?>" class="form-select">
            <?php
            while (!$rs->EOF) {
                $check = ($value == $rs->fields['catId']) ? 'selected' : '';
                ?>
                <option value="<?= $rs->fields['catId']; ?>" <?= $check; ?>>
                    <?= $rs->fields['catTitle']; ?>
                </option>
                <?php
                $rs->movenext();
            }
            ?>
        </select>
    </div>
    <?php
}


/* ===============================
 * SHOW ITEMS (BOOTSTRAP CARDS)
 * =============================== */
function showItem($rsitem, $maxcolum) {
    global $sys_lanai;

    $rsshconfig = $this->getShowcaseConfig();
    $colClass = 12 / $maxcolum;
    ?>
    <div class="container">
        <div class="row">
            <?php
            while (!$rsitem->EOF) {
                if (!empty($rsitem->fields['prdTitle'])) {

                    if (file_exists(
                        $this->cfg['datadir'] .
                        $sys_lanai->getPath() .
                        "ezshop" .
                        $sys_lanai->getPath() .
                        "img_" . $rsitem->fields['prdId'] . "_small.jpg"
                    )) {
                        $imgname = "datacenter/ezshop/img_" . $rsitem->fields['prdId'] . "_small.jpg";
                    } else {
                        $imgname = "modules/ezshopingcart/images/kblackbox.jpg";
                    }
                    ?>
                    <div class="col-md-<?= $colClass ?> mb-4">
                        <div class="card h-100 text-center">
                            <a href="module.php?modname=ezshopingcart&mf=prodview&cid=<?= $rsitem->fields['prdId']; ?>">
                                <img src="<?= $imgname; ?>" class="card-img-top"
                                     alt="<?= htmlspecialchars($rsitem->fields['prdTitle']); ?>">
                            </a>

                            <div class="card-body">
                                <?php if ($rsshconfig->fields['cfgTitleInShowcase'] == 'y') { ?>
                                    <h6 class="card-title">
                                        <a href="module.php?modname=ezshopingcart&mf=prodview&cid=<?= $rsitem->fields['prdId']; ?>"
                                           class="text-decoration-none">
                                            <?= $rsitem->fields['prdTitle']; ?>
                                        </a>
                                    </h6>
                                <?php } ?>

                                <?php if ($rsshconfig->fields['cfgDescriptionInShowcase'] == 'y') { ?>
                                    <p class="card-text small">
                                        <?= substr(strip_tags($rsitem->fields['prdDescription']), 0, 50); ?>...
                                    </p>
                                <?php } ?>

                                <?php if ($rsshconfig->fields['cfgPriceInShowcase'] == 'y') { ?>
                                    <p class="card-text fw-bold">
                                        <?= _PRICE; ?>
                                        <?php if ($rsshconfig->fields['cfgBestPriceInShowcase'] == 'y') { ?>
                                            <s class="text-muted">
                                                <?= $rsshconfig->fields['cfgCurrencySymbol']; ?>
                                                <?= number_format($rsitem->fields['prdPrice'], 2); ?>
                                            </s>
                                        <?php } else { ?>
                                            <?= $rsshconfig->fields['cfgCurrencySymbol']; ?>
                                            <?= number_format($rsitem->fields['prdPrice'], 2); ?>
                                        <?php } ?>
                                    </p>
                                <?php } ?>

                                <?php if ($rsshconfig->fields['cfgBestPriceInShowcase'] == 'y') { ?>
                                    <p class="text-danger fw-bold">
                                        <?= _BESTPRICE; ?>
                                        <?= $rsshconfig->fields['cfgCurrencySymbol']; ?>
                                        <?= number_format($rsitem->fields['prdBestPrice'], 2); ?>
                                    </p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                $rsitem->MoveNext();
            }
            ?>
        </div>
    </div>
    <?php
}


/* ===============================
 * JUMP BOX + BREADCRUMB
 * =============================== */
function getJumpBox($value = null, $cid = null, $pcid = null, $srt = null) {
    $rspcat = $this->getCategoryParent();
    ?>
    <script type="text/javascript">
        function MM_jumpMenu(targ, selObj, restore) {
            eval(targ + ".location='" + selObj.options[selObj.selectedIndex].value + "'");
            if (restore) selObj.selectedIndex = 0;
        }
    </script>

    <div class="container-fluid mb-3">
        <div class="row align-items-center">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="module.php?modname=ezshopingcart"><?=_EZSHOP_HOME; ?></a>
                        </li>

                        <?php
                        switch ($value) {
                            case "a":
                                ?>
                                <li class="breadcrumb-item active">
                                    <?= _EZSHOP_ALL_CATEGORY; ?>
                                </li>
                                <?php
                                break;

                            case "c":
                                if (empty($pcid)) {
                                    $rscat = $this->getCategoryItem($cid);
                                    if ($rscat->recordcount() > 0) {
                                        ?>
                                        <li class="breadcrumb-item active">
                                            <?= $rscat->fields['catTitle']; ?>
                                        </li>
                                        <?php if (!empty($srt)) { ?>
                                            <li class="breadcrumb-item active"><?= $srt; ?></li>
                                        <?php }
                                    }
                                } else {
                                    $rscat = $this->getCategoryItem($pcid);
                                    if ($rscat->recordcount() > 0) {
                                        ?>
                                        <li class="breadcrumb-item">
                                            <a href="module.php?modname=ezshopingcart&ac=c&cid=<?=$pcid; ?>">
                                                <?=$rscat->fields['catTitle']; ?>
                                            </a>
                                        </li>
                                        <?php
                                    }

                                    $rscat = $this->getCategoryItem($cid);
                                    if ($rscat->recordcount() > 0) {
                                        ?>
                                        <li class="breadcrumb-item active">
                                            <?=$rscat->fields['catTitle']; ?>
                                        </li>
                                        <?php
                                    }
                                }
                                break;
                        }
                        ?>
                    </ol>
                </nav>
            </div>

            <div class="col-auto">
                <form class="d-flex align-items-center">
                    <select onchange="MM_jumpMenu('parent',this,0)"
                            class="form-select form-select-sm">
                        <option value=""><?=_SELECT; ?></option>
                        <option value="module.php?modname=ezshopingcart"><?=_EZSHOP_SHOWCASE; ?></option>
                        <option value="module.php?modname=ezshopingcart&ac=a"><?=_EZSHOP_ALL_CATEGORY; ?></option>

                        <?php
                        while (!$rspcat->EOF) {
                            ?>
                            <option value="module.php?modname=ezshopingcart&ac=c&cid=<?=$rspcat->fields['catId']; ?>">
                                <?=$rspcat->fields['catTitle']; ?>
                            </option>
                            <?php
                            $rssub = $this->getSubCategoryByParent($rspcat->fields['catId']);
                            while ($rssub && !$rssub->EOF) {
                                ?>
                                <option value="module.php?modname=ezshopingcart&ac=c&pcid=<?=$rssub->fields['catParentId']; ?>&cid=<?=$rssub->fields['catId']; ?>">
                                    — <?=$rssub->fields['catTitle']; ?>
                                </option>
                                <?php
                                $rssub->movenext();
                            }
                            $rspcat->movenext();
                        }
                        ?>
                    </select>
                </form>
            </div>
        </div>
    </div>
    <?php
}
}
?>