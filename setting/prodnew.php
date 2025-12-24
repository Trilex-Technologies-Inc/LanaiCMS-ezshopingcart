<?php

    if ( !eregi( "setting.php", $_SERVER['PHP_SELF'] ) ) {
        die ( "You can't access this file directly..." );
    }

    $module_name = basename( dirname( substr( __FILE__, 0, strlen( dirname( __FILE__ ) ) ) ) );
    $modfunction = "modules/$module_name/module.php";
    include_once( $modfunction );

    $ezshop = new EzShop2();

?>
<span class="txtContentTitle"><?=_EZSHOP_SETTING; ?></span><br/><br/>
<?=_EZSHOP_CATEGORY_NEW_INSTRUCTION; ?><br/><br/>

<img src="theme/<?=$cfg['theme']; ?>/images/save.gif" border="0" align="absmiddle"/>
<a href="#" onClick="javascript:document.form.submit();">
<?=_SAVE; ?></a>&nbsp;&nbsp;

<img src="theme/<?=$cfg['theme']; ?>/images/back.gif" border="0" align="absmiddle"/>
<a href="#" onClick="javascript:history.back();">
<?=_BACK; ?></a><br><br>

<table cellpadding="3" cellspacing="1">
<form name="form" method="post" action="<?=$_SERVER['PHP_SELF']; ?>" ENCTYPE="multipart/form-data">

<input type="hidden" name="modname" value="<?=$module_name; ?>">
<input type="hidden" name="mf" value="ezedit">
<input type="hidden" name="ac" value="new">

<tr>
    <td width="120"><?=_CATEGORY; ?></td>
    <td><?=$ezshop->getCategoryCombo("catId",0); ?></td>
</tr>

<tr>
    <td><?=_TITLE; ?></td>
    <td><input type="text" name="prdTitle" size="30"/></td>
</tr>

<tr>
    <td valign="top"><?=_DESCRIPTION; ?></td>
    <td>

        <!-- TinyMCE Textarea -->
        <textarea name="prdDescription" class="tinymce" style="width:460px; height:250px;">
This is some <strong>sample text</strong>.
        </textarea>

        <!-- TinyMCE Script -->
        <script src="include/tinymce/js/tinymce/tinymce.min.js"></script>
        <script>
        tinymce.init({
            selector: 'textarea.tinymce',
        license_key: 'gpl'  ,

            height: 250,
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
    <td><?=_PRICE; ?></td>
    <td><input type="text" name="prdPrice" size="10"/></td>
</tr>

<tr>
    <td><?=_BESTPRICE; ?></td>
    <td><input type="text" name="prdBestPrice" size="10"/></td>
</tr>

<tr>
    <td><?=_ACTIVE; ?></td>
    <td>
        <input type="radio" name="prdActive" value="y" checked><?=_YES; ?>
        <input type="radio" name="prdActive" value="n"><?=_NO; ?>
    </td>
</tr>

<tr>
    <td>&nbsp;</td>
    <td>* <?=_IMAGE_NOTE; ?></td>
</tr>

<tr>
    <td><?=_SMALL_IMAGE; ?></td>
    <td><input type="file" name="userfile[]" /></td>
</tr>
<tr>
    <td><?=_BIG_IMAGE1; ?></td>
    <td><input type="file" name="userfile[]" /></td>
</tr>
<tr>
    <td><?=_BIG_IMAGE2; ?></td>
    <td><input type="file" name="userfile[]" /></td>
</tr>
<tr>
    <td><?=_ACTION_SHOT_IMAGE1; ?></td>
    <td><input type="file" name="userfile[]" /></td>
</tr>
<tr>
    <td><?=_ACTION_SHOT_IMAGE2; ?></td>
    <td><input type="file" name="userfile[]" /></td>
</tr>
<tr>
    <td><?=_ACTION_SHOT_IMAGE3; ?></td>
    <td><input type="file" name="userfile[]" /></td>
</tr>
<tr>
    <td><?=_ACTION_SHOT_IMAGE4; ?></td>
    <td><input type="file" name="userfile[]" /></td>
</tr>

</form>
</table>
