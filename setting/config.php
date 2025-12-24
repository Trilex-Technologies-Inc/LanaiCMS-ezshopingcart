<?

if (!eregi("setting.php", $_SERVER['PHP_SELF'])) {
    die("You can't access this file directly...");
}

$module_name = basename(dirname(substr(__FILE__, 0, strlen(dirname(__FILE__)))));
$modfunction = "modules/$module_name/module.php";
include_once($modfunction);

$ezshop = new EzShop2();
$rsconfig = $ezshop->getShowcaseConfig();

$check_yes = $check_no = "";
$checkd_yes = $checkd_no = "";
$checkp_yes = $checkp_no = "";
$checkbp_yes = $checkbp_no = "";
$checkdt_yes = $checkdt_no = "";
$checkdd_yes = $checkdd_no = "";
$checkdp_yes = $checkdp_no = "";
$checkdbp_yes = $checkdbp_no = "";

?>
<span class="txtContentTitle"><?=_EZSHOP_SETTING; ?></span><br/><br/>
<?=_EZSHOP_CONFIG_INSTRUCTION; ?><br/><br/>

<form name="form" method="get" action="<?=$_SERVER['PHP_SELF']; ?>">
    <img src="theme/<?=$cfg['theme']; ?>/images/save.gif" border="0" align="absmiddle"/>
    <a href="javascript:document.form.submit();"><?=_SAVE; ?></a>&nbsp;&nbsp;
    <img src="theme/<?=$cfg['theme']; ?>/images/back.gif" border="0" align="absmiddle"/>
    <a href="setting.php?modname=ezshopingcart"><?=_BACK; ?></a><br><br>

    <table>
        <input type="hidden" name="modname" value="<?=$module_name; ?>">
        <input type="hidden" name="mf" value="ezedit">
        <input type="hidden" name="ac" value="config">

        <tr>
            <td><?=_TITLE_IN_SHOWCASE; ?></td>
            <?
            if ($rsconfig->fields['cfgTitleInShowcase'] == 'y') $check_yes = "checked";
            else if ($rsconfig->fields['cfgTitleInShowcase'] == 'n') $check_no = "checked";
            ?>
            <td>
                <input type="radio" name="cfgTitleInShowcase" value="y" <?=$check_yes; ?>/><?=_YES; ?>
                <input type="radio" name="cfgTitleInShowcase" value="n" <?=$check_no; ?>/><?=_NO; ?>
            </td>
        </tr>

        <tr>
            <td><?=_DESCRIPTION_IN_SHOWCASE; ?></td>
            <?
            if ($rsconfig->fields['cfgDescriptionInShowcase'] == 'y') $checkd_yes = "checked";
            else if ($rsconfig->fields['cfgDescriptionInShowcase'] == 'n') $checkd_no = "checked";
            ?>
            <td>
                <input type="radio" name="cfgDescriptionInShowcase" value="y" <?=$checkd_yes; ?>/><?=_YES; ?>
                <input type="radio" name="cfgDescriptionInShowcase" value="n" <?=$checkd_no; ?>/><?=_NO; ?>
            </td>
        </tr>

        <tr>
            <td><?=_PRICE_IN_SHOWCASE; ?></td>
            <?
            if ($rsconfig->fields['cfgPriceInShowcase'] == 'y') $checkp_yes = "checked";
            else if ($rsconfig->fields['cfgPriceInShowcase'] == 'n') $checkp_no = "checked";
            ?>
            <td>
                <input type="radio" name="cfgPriceInShowcase" value="y" <?=$checkp_yes; ?>/><?=_YES; ?>
                <input type="radio" name="cfgPriceInShowcase" value="n" <?=$checkp_no; ?>/><?=_NO; ?>
            </td>
        </tr>

        <tr>
            <td><?=_BESTPRICE_IN_SHOWCASE; ?></td>
            <?
            if ($rsconfig->fields['cfgBestPriceInShowcase'] == 'y') $checkbp_yes = "checked";
            else if ($rsconfig->fields['cfgBestPriceInShowcase'] == 'n') $checkbp_no = "checked";
            ?>
            <td>
                <input type="radio" name="cfgBestPriceInShowcase" value="y" <?=$checkbp_yes; ?>/><?=_YES; ?>
                <input type="radio" name="cfgBestPriceInShowcase" value="n" <?=$checkbp_no; ?>/><?=_NO; ?>
            </td>
        </tr>

        <tr>
            <td><?=_TITLE_IN_DETAIL; ?></td>
            <?
            if ($rsconfig->fields['cfgTitleInDetail'] == 'y') $checkdt_yes = "checked";
            else if ($rsconfig->fields['cfgTitleInDetail'] == 'n') $checkdt_no = "checked";
            ?>
            <td>
                <input type="radio" name="cfgTitleInDetail" value="y" <?=$checkdt_yes; ?>/><?=_YES; ?>
                <input type="radio" name="cfgTitleInDetail" value="n" <?=$checkdt_no; ?>/><?=_NO; ?>
            </td>
        </tr>

        <tr>
            <td><?=_DESCRIPTION_IN_DETAIL; ?></td>
            <?
            if ($rsconfig->fields['cfgDescriptionInDetail'] == 'y') $checkdd_yes = "checked";
            else if ($rsconfig->fields['cfgDescriptionInDetail'] == 'n') $checkdd_no = "checked";
            ?>
            <td>
                <input type="radio" name="cfgDescriptionInDetail" value="y" <?=$checkdd_yes; ?>/><?=_YES; ?>
                <input type="radio" name="cfgDescriptionInDetail" value="n" <?=$checkdd_no; ?>/><?=_NO; ?>
            </td>
        </tr>

        <tr>
            <td><?=_PRICE_IN_DETAIL; ?></td>
            <?
            if ($rsconfig->fields['cfgPriceInDetail'] == 'y') $checkdp_yes = "checked";
            else if ($rsconfig->fields['cfgPriceInDetail'] == 'n') $checkdp_no = "checked";
            ?>
            <td>
                <input type="radio" name="cfgPriceInDetail" value="y" <?=$checkdp_yes; ?>/><?=_YES; ?>
                <input type="radio" name="cfgPriceInDetail" value="n" <?=$checkdp_no; ?>/><?=_NO; ?>
            </td>
        </tr>

        <tr>
            <td><?=_BESTPRICE_IN_DETAIL; ?></td>
            <?
            if ($rsconfig->fields['cfgBestPriceInDetail'] == 'y') $checkdbp_yes = "checked";
            else if ($rsconfig->fields['cfgBestPriceInDetail'] == 'n') $checkdbp_no = "checked";
            ?>
            <td>
                <input type="radio" name="cfgBestPriceInDetail" value="y" <?=$checkdbp_yes; ?>/><?=_YES; ?>
                <input type="radio" name="cfgBestPriceInDetail" value="n" <?=$checkdbp_no; ?>/><?=_NO; ?>
            </td>
        </tr>

        <tr>
            <td><?=_CURRENCY_SYMBOL; ?></td>
            <td><input name="cfgCurrencySymbol" type="text" value="<?=$rsconfig->fields['cfgCurrencySymbol']; ?>" size="5"/></td>
        </tr>

        <tr>
            <td><?=_NUM_COLUMN; ?></td>
            <td><input name="cfgColumnNumber" type="text" value="<?=$rsconfig->fields['cfgColumnNumber']; ?>" size="5"/></td>
        </tr>
    </table>
</form>
