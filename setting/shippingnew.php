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
<?=_EZSHOP_SHIPPING_NEW_INSTRUCTION; ?><br/><br/>

<img src="theme/<?=$cfg['theme']; ?>/images/save.gif" border="0" align="absmiddle"/>
<a href="#" onClick="javascript:document.form.submit();">
<?=_SAVE; ?></a>&nbsp;&nbsp;

<img src="theme/<?=$cfg['theme']; ?>/images/back.gif" border="0" align="absmiddle"/>
<a href="#" onClick="javascript:history.back();">
<?=_BACK; ?></a><br><br>

<table cellpadding="3" cellspacing="1">
<form name="form" method="post" action="<?=$_SERVER['PHP_SELF']; ?>">

<input type="hidden" name="modname" value="<?=$module_name; ?>">
<input type="hidden" name="mf" value="ezedit">
<input type="hidden" name="ac" value="shippingnew">

<tr>
    <td><?=_TITLE; ?></td>
    <td><input type="text" name="shpTitle" size="30"/></td>
</tr>

<tr>
    <td valign="top"><?=_DESCRIPTION; ?></td>
    <td>

        <!-- TinyMCE Textarea -->
        <textarea name="shpDescription" class="tinymce" style="width:460px; height:200px;">
This is some <strong>sample text</strong>.
        </textarea>

        <!-- TinyMCE Script -->
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
    <td><?=_SHIPPING_RATE; ?></td>
    <td>
      <input type="radio" name="shpRateType" value="r" checked/> <?=_EZSHOP_RATETABLE; ?>
      <input type="text" name="shpRateTable" size="40" value="1:80,5:80,10:85,15:90,20:95,25:100,120"/>
      <input type="hidden" name="shpRateTableType" value="q">
    </td>
</tr>

<tr>
    <td>&nbsp;</td>
    <td>
      <input type="radio" name="shpRateType" value="f" /> <?=_EZSHOP_RATEFIXED; ?>
      <input type="text" name="shpFixRate" size="5" value="80"/>
    </td>
</tr>

</form>
</table>
