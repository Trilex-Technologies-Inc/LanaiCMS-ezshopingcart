<?php
if (!eregi("setting.php", $_SERVER['PHP_SELF'])) {
    die("You can't access this file directly...");
}

$module_name = basename(dirname(substr(__FILE__, 0, strlen(dirname(__FILE__)))));
$modfunction = "modules/$module_name/module.php";
include_once($modfunction);

$ezshop = new EzShop2();
$ezcart = new EzShoppingCart();

// Get cart item
$cartitem = $ezcart->getCartById(isset($_REQUEST['cid']) ? $_REQUEST['cid'] : 0);

// Initialize variables
$sbtotal = 0;   // subtotal
$tqty = 0;      // total quantity
$crtRemark = isset($_REQUEST['crtRemark']) ? $_REQUEST['crtRemark'] : '';

?>
<span class="txtContentTitle"><?php echo _EZSHOP_SHIPPING_TO; ?></span><br/><br/>

<?php
$rsmember = $ezcart->getMemberInfo($cartitem->fields['userId']);
?>
<table cellpadding="3" cellspacing="1">
    <tr>
        <td><?php echo _EZSHOP_FNAME; ?></td>
        <td><?php echo $rsmember->fields['userFname']; ?></td>
    </tr>
    <tr>
        <td><?php echo _EZSHOP_LNAME; ?></td>
        <td><?php echo $rsmember->fields['userLname']; ?></td>
    </tr>
    <tr>
        <td><?php echo _EZSHOP_ADDRESS1; ?></td>
        <td><?php echo $rsmember->fields['userAddress1']; ?></td>
    </tr>
    <tr>
        <td><?php echo _EZSHOP_ADDRESS2; ?></td>
        <td><?php echo $rsmember->fields['userAddress2']; ?></td>
    </tr>
    <tr>
        <td><?php echo _EZSHOP_CITY; ?></td>
        <td><?php echo $rsmember->fields['userCity']; ?></td>
    </tr>
    <tr>
        <td><?php echo _EZSHOP_STATE; ?></td>
        <td><?php echo $rsmember->fields['userState']; ?></td>
    </tr>
    <tr>
        <td><?php echo _EZSHOP_COUNTRY; ?></td>
        <td><?php echo $rsmember->fields['cntId']; ?></td>
    </tr>
    <tr>
        <td><?php echo _EZSHOP_ZIPCODE; ?></td>
        <td><?php echo $rsmember->fields['userZipcode']; ?></td>
    </tr>
</table>

<?php
// Show cart detail
$rs = $ezcart->getCartDetailByRef($cartitem->fields['crtSession']);
?>
<br/>
<span class="txtContentTitle"><?php echo _EZSHOP_CART; ?></span><br/><br/>
<table width="100%" cellpadding="3" cellspacing="1">
<tr bgcolor="#CCCCCC">
    <th><?php echo _QTY; ?></th>
    <th><?php echo _DESCRIPTION; ?></th>
    <th><?php echo _PRICE; ?></th>
    <th><?php echo _AMOUNT; ?></th>
</tr>

<?php
while (!$rs->EOF) {
    $quantity = $rs->fields['crtQuantity'];
    $price = $rs->fields[3];

    $tqty += $quantity;
    $sbtotal += ($quantity * $price);

    $rspitem = $ezcart->getProductItem($rs->fields['prdId']);
?>
<tr bgcolor="#F2F2F2">
    <td align="center"><?php echo $quantity; ?></td>
    <td width="80%"><?php echo $rspitem->fields['prdTitle']; ?></td>
    <td align="right"><?php echo $price; ?></td>
    <td align="right"><?php echo ($quantity * $price); ?></td>
</tr>
<?php
    $rs->movenext();
}
?>

<tr bgcolor="#EEEEEE">
    <th colspan="3" align="right"><?php echo _SUBTOTAL; ?></th>
    <th align="right"><?php echo sprintf("%01.2f", $sbtotal); ?></th>
</tr>
<tr bgcolor="#EEEEEE">
    <th colspan="3" align="right"><?php echo _SHIPPING; ?></th>
    <th align="right">
    <?php
    $shpcost = $ezcart->getShippingCost($cartitem->fields['shpId'], $tqty);
    echo sprintf("%01.2f", $shpcost);
    ?>
    </th>
</tr>
<tr bgcolor="#EEEEEE">
    <th colspan="3" align="right"><?php echo _TOTAL; ?></th>
    <th align="right">
    <?php $amt = $sbtotal + $shpcost; ?>
    <?php echo sprintf("%01.2f", $amt); ?>
    </th>
</tr>
</table>

<br/>
<span class="txtContentTitle"><?php echo _EZSHOP_SHIPPING_METHOD; ?></span><br/><br/>
<?php
$rsship = $ezcart->getShippingMethod($cartitem->fields['shpId']);
?>
<table cellpadding="3" cellspacing="1">
<tr>
    <td colspan="2"><strong><?php echo $rsship->fields['shpTitle']; ?></strong><br/><?php echo $rsship->fields['shpDescription']; ?></td>
</tr>
</table>

<br/>
<span class="txtContentTitle"><?php echo _EZSHOP_PAYMENT_METHOD; ?></span><br/><br/>
<?php
$rspay = $ezcart->getPaymentMethod($cartitem->fields['payId']);
?>
<table cellpadding="3" cellspacing="1">
<tr>
    <td colspan="2"><strong><?php echo $rspay->fields['payTitle']; ?></strong><br/><?php echo $rspay->fields['payDescription']; ?></td>
</tr>
</table>

<br/>
<span class="txtContentTitle"><?php echo _EZSHOP_REMARK; ?></span><br/><br/>
<table cellpadding="3" cellspacing="1">
<tr>
    <td valign="top">
    <?php echo nl2br($crtRemark); ?>
    <input type="hidden" name="crtRemark" value="<?php echo nl2br($cartitem->fields['crtRemark']); ?>"/>
    </td>
</tr>
</table>
