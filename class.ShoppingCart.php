<?

    class EzShoppingCart extends EzShop2 {

        var $uid;
		var $db;
		var $cfg;
		var $_sql;


		function EzShoppingCart () {
			global $db,$cfg;
			$this->db=$db;
			$this->cfg=$cfg;
			$this->uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;

$this->cartref = isset($_COOKIE['cartref']) ? $_COOKIE['cartref'] : null;
			//$this->db->debug=true;
		}

        function setItemAddToCart($cid,$qt) {
            // check in cart if product exist
            $rspitem=$this->getProductInCart($cid);
            if ($rspitem->recordcount() > 0) {
                // product exist
                $sql="UPDATE ".$this->cfg['tablepre']."ezshop_cart_item
                        SET crtQuantity=".(($rspitem->fields['crtQuantity'])+$qt)."
                        WHERE prdId=$cid AND crtSession='".$this->cartref."' ";
                $this->db->execute($sql);
            } else {
                // product not exist
                $rsitem=$this->getProductItem($cid);
                if (($rsitem->fields['prdBestPrice'] > 0) AND ($rsitem->fields['prdBestPrice'] < $rsitem->fields['prdPrice'])) {
                    $price=$rsitem->fields['prdBestPrice'];
                } else {
                    $price=$rsitem->fields['prdPrice'];
                }
                $sql="INSERT INTO ".$this->cfg['tablepre']."ezshop_cart_item
                        (prdId, crtId, crtSession, prdPrice, crtQuantity)
                        VALUES ($cid, 0,'".$this->cartref."',".$price.",$qt)";
                $this->db->execute($sql);
            }
        }

        function getProductInCart($cid) {
            if ($cid>0) {
                $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_cart_item
                      WHERE prdId=$cid AND crtSession='".$this->cartref."' ";
            } else {
                $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_cart_item
                      WHERE crtSession='".$this->cartref."' ";
            }
            return ($this->db->execute($sql));
        }

        function setItemUpdateCart($cid,$qty) {
            $sql="UPDATE ".$this->cfg['tablepre']."ezshop_cart_item
                    SET crtQuantity=$qty
                    WHERE crtSession='".$this->cartref."' AND prdId=$cid";
            return ($this->db->execute($sql));
        }

       function setItemRemoveFromCart($cid) {
            $sql="DELETE FROM ".$this->cfg['tablepre']."ezshop_cart_item
                    WHERE crtSession='".$this->cartref."' AND prdId=$cid ";
            return ($this->db->execute($sql));
       }

       function getMemberInfo($mid=null){
            if (empty($mid)) {
                $sql="SELECT * FROM ".$this->cfg['tablepre']."user
                        WHERE userId=".$this->uid;
            } else {
                $sql="SELECT * FROM ".$this->cfg['tablepre']."user
                        WHERE userId=$mid";
            }
            return ($this->db->execute($sql));
       }

       function getShippingMethod($mid=null){
            if (empty($mid)) {
                $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_shipping";
            } else {
                $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_shipping
                        WHERE shpId=$mid ";
            }
            return ($this->db->execute($sql));
       }

       function getPaymentMethod($mid=null){
             if (empty($mid)) {
                $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_payment";
             } else {
                $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_payment
                        WHERE payId=$mid ";
            }
            return ($this->db->execute($sql));
       }

       function getShippingCost($shpId, $qty)
{
    $cost = 0;
    $ow   = 0;

    $shpitem = $this->getShippingMethod($shpId);

    switch ($shpitem->fields['shpRateType']) {

        case 'f':
            $cost = $shpitem->fields['shpFixRate'];
        break;

        case 'r':
            $rate = explode(",", $shpitem->fields['shpRateTable']);

            foreach ($rate as $value) {

                if (trim($value) == "") continue;

                $parts = explode(":", $value);
                if (!isset($parts[0]) || !isset($parts[1])) continue;

                $w  = trim($parts[0]);
                $ct = trim($parts[1]);

                if ($w <= $qty) {
                    $cost = $ct;
                } elseif ($ct == "" && $qty > $ow) {
                    $cost = $w;
                }

                $ow = $w;
            }
        break;
    }

    return $cost;
}


        function setCartSave($crtref,$shpid,$payid,$remark) {
            $sql="INSERT INTO ".$this->cfg['tablepre']."ezshop_cart
                    (crtSession, userId, shpId, payId, crtStatus, crtRemark, crtCreate)
                    VALUES ('".$crtref."',".$this->uid.",".$shpid.",".$payid.",'p','".$remark."',NOW())";
            $this->db->execute($sql);
            $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_cart
                    WHERE crtSession='".$crtref."' AND userId=".$this->uid;
            $rsitem=$this->db->execute($sql);
            $sql="UPDATE ".$this->cfg['tablepre']."ezshop_cart_item
                    SET crtId=".$rsitem->fields['crtId']."
                    WHERE crtSession='".$crtref."' ";
            return ($this->db->execute($sql));
        }

        function getCartByRef($crtref){
            $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_cart
                    WHERE crtSession='".$crtref."' AND userId=".$this->uid."
                    ORDER BY crtId DESC
                    ";
            return ($this->db->execute($sql));
        }

         function getCartById($cid){
            $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_cart
                    WHERE crtId=$cid";
            return ($this->db->execute($sql));
         }

        function getCartDetailByRef($crtref){
            $sql="SELECT * FROM ".$this->cfg['tablepre']."ezshop_cart_item,".$this->cfg['tablepre']."ezshop_product_item
                    WHERE ".$this->cfg['tablepre']."ezshop_cart_item.crtSession='".$crtref."' AND
                            ".$this->cfg['tablepre']."ezshop_cart_item.prdId=".$this->cfg['tablepre']."ezshop_product_item.prdId
                    ORDER BY ".$this->cfg['tablepre']."ezshop_cart_item.crtId DESC
                    ";
            return ($this->db->execute($sql));
        }


    }

?>
