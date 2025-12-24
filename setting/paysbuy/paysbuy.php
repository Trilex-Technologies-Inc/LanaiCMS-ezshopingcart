<?
    $psbuy_email="anoochit@gmail.com";

?>
<br/><br/>
<Form name="payment" method=post action="https://www.paysbuy.com/paynow.aspx" target="_blank">
<input type="hidden" Name="psb" value="psb">
<Input Type="hidden" Name="biz" value="<?=$psbuy_email; ?>">
<Input Type="Hidden" Name="inv" value="<?=$inv; ?>">
<Input Type="Hidden" Name="itm" value="<?=$prd; ?>">
<Input Type="Hidden" Name="amt" value="<?=$amt; ?>">
<input type="image" src="https://www.paysbuy.com/images/p_click2pay.gif" border="0" name="submit" alt="Make payments with PaySbuy - it's fast, free and secure!">
</Form >
<script language="JavaScript" type="text/javascript">
    document.payment.submit();
</script>
