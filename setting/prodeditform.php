<?php

if (!eregi("setting.php", $_SERVER['PHP_SELF'])) {
    die("You can't access this file directly...");
}

$module_name = basename(dirname(substr(__FILE__, 0, strlen(dirname(__FILE__)))));
$modfunction = "modules/$module_name/module.php";
include_once($modfunction);

$ezshop = new EzShop2();
$rs = $ezshop->getProductItem($_REQUEST['mid']);

?>
<span class="txtContentTitle"><?=_EZSHOP_SETTING; ?></span><br/><br/>
<?=_EZSHOP_CATEGORY_NEW_INSTRUCTION; ?><br/><br/>

<img src="theme/<?=$cfg['theme']; ?>/images/save.gif" border="0" align="absmiddle"/>
<a href="#" onclick="document.form.submit();"><?=_SAVE; ?></a>&nbsp;&nbsp;

<img src="theme/<?=$cfg['theme']; ?>/images/back.gif" border="0" align="absmiddle"/>
<a href="javascript:history.back();"><?=_BACK; ?></a><br><br>

<form name="form" method="post" action="<?=$_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
<table cellpadding="3" cellspacing="1">

<input type="hidden" name="modname" value="<?=$module_name;?>">
<input type="hidden" name="mf" value="ezedit">
<input type="hidden" name="mid" value="<?=$_REQUEST['mid'];?>">
<input type="hidden" name="ac" value="edit">

<tr>
    <td><?=_CATEGORY;?></td>
    <td><?=$ezshop->getCategoryCombo("catId", $rs->fields['catId']);?></td>
</tr>

<tr>
    <td><?=_TITLE;?></td>
    <td><input type="text" name="prdTitle" size="30" value="<?=htmlspecialchars($rs->fields['prdTitle']);?>"/></td>
</tr>

<tr>
    <td valign="top"><?=_DESCRIPTION;?></td>
    <td>
        <textarea name="prdDescription" class="tinymce" style="width:460px; height:250px;"><?=htmlspecialchars($rs->fields['prdDescription']);?></textarea>

        <!-- TinyMCE Script -->
        <script src="include/tinymce/js/tinymce/tinymce.min.js"></script>
        <script>
        tinymce.init({
            selector: 'textarea.tinymce',
            license_key: 'gpl',
            height: 250,
            menubar: true,
            plugins: 'print preview paste importcss searchreplace autolink autosave save code link image media table lists wordcount',
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
    <td><?=_PRICE;?></td>
    <td><input type="text" name="prdPrice" size="10" value="<?=htmlspecialchars($rs->fields['prdPrice']);?>"/></td>
</tr>

<tr>
    <td><?=_BESTPRICE;?></td>
    <td><input type="text" name="prdBestPrice" size="10" value="<?=htmlspecialchars($rs->fields['prdBestPrice']);?>"/></td>
</tr>

<tr>
    <td><?=_ACTIVE;?></td>
    <?php
        $check_yes = ($rs->fields['prdActive'] == 'y') ? 'checked' : '';
        $check_no  = ($rs->fields['prdActive'] == 'n') ? 'checked' : '';
    ?>
    <td>
        <input type="radio" name="prdActive" value="y" <?=$check_yes;?>><?=_YES;?>
        <input type="radio" name="prdActive" value="n" <?=$check_no;?>><?=_NO;?>
    </td>
</tr>

<tr>
    <td>&nbsp;</td>
    <td>* <?=_IMAGE_NOTE;?></td>
</tr>

<tr>
    <td><?=_SMALL_IMAGE;?></td>
    <td><input type="file" name="userfile[]" /> * <?=_NOT_UPDATE_LEAVE_BLANK;?><br></td>
</tr>

<tr>
    <td><?=_BIG_IMAGE1;?></td>
    <td><input type="file" name="userfile[]" /> * <?=_NOT_UPDATE_LEAVE_BLANK;?></td>
</tr>

<tr>
    <td><?=_BIG_IMAGE2;?></td>
    <td><input type="file" name="userfile[]" /> * <?=_NOT_UPDATE_LEAVE_BLANK;?></td>
</tr>

<tr>
    <td><?=_ACTION_SHOT_IMAGE1;?></td>
    <td><input type="file" name="userfile[]" /> * <?=_NOT_UPDATE_LEAVE_BLANK;?></td>
</tr>

<tr>
    <td><?=_ACTION_SHOT_IMAGE2;?></td>
    <td><input type="file" name="userfile[]" /> * <?=_NOT_UPDATE_LEAVE_BLANK;?></td>
</tr>

<tr>
    <td><?=_ACTION_SHOT_IMAGE3;?></td>
    <td><input type="file" name="userfile[]" /> * <?=_NOT_UPDATE_LEAVE_BLANK;?></td>
</tr>

<tr>
    <td><?=_ACTION_SHOT_IMAGE4;?></td>
    <td><input type="file" name="userfile[]" /> * <?=_NOT_UPDATE_LEAVE_BLANK;?></td>
</tr>

</table>
</form>
