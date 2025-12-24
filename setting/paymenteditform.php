<?php

if (!eregi("setting.php", $_SERVER['PHP_SELF'])) {
    die("You can't access this file directly...");
}

$module_name = basename(dirname(substr(__FILE__, 0, strlen(dirname(__FILE__)))));
$modfunction = "modules/$module_name/module.php";
include_once($modfunction);

$ezshop = new EzShoppingCart();
$rs = $ezshop->getPaymentMethod($_REQUEST['mid']);

?>
<span class="txtContentTitle"><?=_EZSHOP_SETTING; ?></span><br/><br/>
<?=_EZSHOP_PAYMENT_EDIT_INSTRUCTION; ?><br/><br/>

<img src="theme/<?=$cfg['theme']; ?>/images/save.gif" border="0" align="absmiddle"/>
<a href="#" onClick="javascript:document.form.submit();"><?=_SAVE; ?></a>&nbsp;&nbsp;

<img src="theme/<?=$cfg['theme']; ?>/images/back.gif" border="0" align="absmiddle"/>
<a href="#" onClick="javascript:history.back();"><?=_BACK; ?></a><br><br>

<table cellpadding="3" cellspacing="1">
<form name="form" method="post" action="<?=$_SERVER['PHP_SELF'];?>">

<input type="hidden" name="modname" value="<?=$module_name;?>">
<input type="hidden" name="mf" value="ezedit">
<input type="hidden" name="ac" value="paymentedit">
<input type="hidden" name="mid" value="<?=$_REQUEST['mid'];?>">

<tr>
    <td><?=_TITLE;?></td>
    <td><input type="text" name="payTitle" size="30" value="<?=$rs->fields['payTitle'];?>"/></td>
</tr>

<tr>
    <td valign="top"><?=_DESCRIPTION;?></td>
    <td>
        <textarea name="payDescription" class="tinymce" style="width:460px; height:200px;">
<?=$rs->fields['payDescription'];?>
        </textarea>

        <script src="include/tinymce/js/tinymce/tinymce.min.js"></script>
        <script>
        tinymce.init({
            selector: 'textarea.tinymce',
            height: 200,
            license_key: 'gpl',
            menubar: true,
            plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code image link media table lists wordcount',
            toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | code',
            paste_as_text: false,
            valid_elements: '*[*]',
            extended_valid_elements: '*[*]',
            verify_html: false,
            cleanup: false,
            content_css: false
        });
        </script>
    </td>
</tr>

<tr>
    <td><?=_MODULE;?></td>
    <td>
        <select name="payModule">
            <?php
            $paymentSystems = array(
                'paypal' => 'PayPal',
                'stripe' => 'Stripe',
                'square' => 'Square',
                'moneris' => 'Moneris',
                'interac' => 'Interac e-Transfer',
                'applepay' => 'Apple Pay',
                'googlepay' => 'Google Pay'
            );

            $currentModule = $rs->fields['payModule'];

            foreach ($paymentSystems as $value => $label) {
                $selected = ($currentModule == $value) ? 'selected' : '';
                echo "<option value=\"$value\" $selected>$label</option>";
            }
            ?>
        </select>
    </td>
</tr>

<tr>
    <td><?=_TOKEN;?></td>
    <td><input type="text" name="payToken" size="30" value="<?=$rs->fields['payToken'];?>" /></td>
</tr>

<tr>
    <td><?=_SECRET;?></td>
    <td><input type="text" name="paySecret" size="50" value="<?php echo isset($rs->fields['paySecret']) ? $rs->fields['paySecret'] : ''; ?>" /></td>
</tr>

<tr>
    <td><?=_CURRENCY;?></td>
    <td>
        <select name="currency">
            <?php
            $currencies = array(
                'USD' => 'USD - US Dollar',
                'CAD' => 'CAD - Canadian Dollar',
                'EUR' => 'EUR - Euro',
                'GBP' => 'GBP - British Pound',
                'AUD' => 'AUD - Australian Dollar'
            );
            $currentCurrency = $rs->fields['currency'];
            foreach ($currencies as $code => $label) {
                $selected = ($currentCurrency == $code) ? 'selected' : '';
                echo "<option value=\"$code\" $selected>$label</option>";
            }
            ?>
        </select>
    </td>
</tr>

</form>
</table>
