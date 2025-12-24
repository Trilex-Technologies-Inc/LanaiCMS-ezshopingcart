<?php

if (!eregi("setting.php", $_SERVER['PHP_SELF'])) {
    die("You can't access this file directly...");
}

$module_name = basename(dirname(substr(__FILE__, 0, strlen(dirname(__FILE__)))));
$modfunction = "modules/$module_name/module.php";
include_once($modfunction);

$ezshop = new EzShop2();

?>
<span class="txtContentTitle"><?=_EZSHOP_SETTING; ?></span><br/><br/>
<?=_EZSHOP_PAYMENT_NEW_INSTRUCTION; ?><br/><br/>

<img src="theme/<?=$cfg['theme']; ?>/images/save.gif" border="0" align="absmiddle"/>
<a href="#" onClick="javascript:document.form.submit();"><?=_SAVE; ?></a>&nbsp;&nbsp;

<img src="theme/<?=$cfg['theme']; ?>/images/back.gif" border="0" align="absmiddle"/>
<a href="#" onClick="javascript:history.back();"><?=_BACK; ?></a><br><br>

<table cellpadding="3" cellspacing="1">
<form name="form" method="post" action="<?=$_SERVER['PHP_SELF'];?>">

<input type="hidden" name="modname" value="<?=$module_name;?>">
<input type="hidden" name="mf" value="ezedit">
<input type="hidden" name="ac" value="paymentnew">

<tr>
    <td><?=_TITLE;?></td>
    <td><input type="text" name="payTitle" size="30"/></td>
</tr>

<tr>
    <td valign="top"><?=_DESCRIPTION;?></td>
    <td>
        <textarea name="payDescription" class="tinymce" style="width:460px; height:200px;">
This is some <strong>sample text</strong>.
        </textarea>

        <script src="include/tinymce/js/tinymce/tinymce.min.js"></script>
        <script>
        tinymce.init({
            selector: 'textarea.tinymce',
            height: 200,
            menubar: true,
            license_key: 'gpl',
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
            <option value="">-- Select Payment System --</option>
            <option value="paypal">PayPal</option>
            <option value="stripe">Stripe</option>
            <option value="square">Square</option>
            <option value="moneris">Moneris</option>
            <option value="interac">Interac e-Transfer</option>
            <option value="applepay">Apple Pay</option>
            <option value="googlepay">Google Pay</option>
        </select>
    </td>
</tr>

<tr>
    <td><?=_TOKEN;?></td>
    <td><input type="text" name="payToken" size="30" /></td>
</tr>

<tr>
    <td><?=_SECRET;?></td>
    <td><input type="text" name="paySecret" size="50" /></td>
</tr>

<tr>
    <td><?=_CURRENCY;?></td>
    <td>
        <select name="currency">
            <option value="">-- Select Currency --</option>
            <option value="USD">USD - US Dollar</option>
            <option value="CAD">CAD - Canadian Dollar</option>
            <option value="EUR">EUR - Euro</option>
            <option value="GBP">GBP - British Pound</option>
            <option value="AUD">AUD - Australian Dollar</option>
        </select>
    </td>
</tr>

</form>
</table>
