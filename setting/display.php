<?

	if ( !eregi( "setting.php", $_SERVER['PHP_SELF'] ) ) {
	    die ( "You can't access this file directly..." );
	}

	$module_name = basename( dirname( substr( __FILE__, 0, strlen( dirname( __FILE__ ) ) ) ) );
	$modfunction = "modules/$module_name/module.php";
	include_once( $modfunction );

	$ezshop=new EzShop2();

    ?>

<script language="JavaScript" type="text/javascript">
<!--

var NS4 = (navigator.appName == "Netscape" && parseInt(navigator.appVersion) < 5);

function addOption(theSel, theText, theValue)
{
  var newOpt = new Option(theText, theValue);
  var selLength = theSel.length;
  theSel.options[selLength] = newOpt;
}

function deleteOption(theSel, theIndex)
{
  var selLength = theSel.length;
  if(selLength>0)
  {
    theSel.options[theIndex] = null;
  }
}

function moveOptions(theSelFrom, theSelTo)
{

  var selLength = theSelFrom.length;
  var selectedText = new Array();
  var selectedValues = new Array();
  var selectedCount = 0;

  var i;

  // Find the selected Options in reverse order
  // and delete them from the 'from' Select.
  for(i=selLength-1; i>=0; i--)
  {
    if(theSelFrom.options[i].selected)
    {
      selectedText[selectedCount] = theSelFrom.options[i].text;
      selectedValues[selectedCount] = theSelFrom.options[i].value;
      deleteOption(theSelFrom, i);
      selectedCount++;
    }
  }

  // Add the selected text/values in reverse order.
  // This will add the Options to the 'to' Select
  // in the same order as they were in the 'from' Select.
  for(i=selectedCount-1; i>=0; i--)
  {
    addOption(theSelTo, selectedText[i], selectedValues[i]);
  }

  if(NS4) history.go(0);


}

//-->
</script>
<script language="JavaScript" type="text/javascript">

// Technique 1
function placeInHidden(delim, selStr, hidStr)
{
  var selObj = document.getElementById(selStr);
  var hideObj = document.getElementById(hidStr);
  hideObj.value = '';
  for (var i=0; i<selObj.options.length; i++) {
    hideObj.value = hideObj.value ==
      '' ? selObj.options[i].value : hideObj.value + delim + selObj.options[i].value;
  }
}
// Technique 2
function selectAllOptions(selStr)
{
  var selObj = document.getElementById(selStr);
  for (var i=0; i<selObj.options.length; i++) {
    selObj.options[i].selected = true;
  }
}

</script>
<script language="JavaScript" type="text/javascript">
    function submitShowcase() {
         placeInHidden('.', 'selectedOptions', 'hide2');
         document.form.submit();
    }
</script>

    <span class="txtContentTitle"><?=_EZSHOP_SETTING; ?></span><br/><br/>
	<?=_EZSHOP_SHOWCASE_INSTRUCTION; ?><br/><br/>
	<form name="form" method="get"  action="<?=$_SERVER['PHP_SELF']; ?>" >
  	<img src="theme/<?=$cfg['theme']; ?>/images/save.gif" border="0" align="absmiddle"/>
  	<a href="javascript:submitShowcase();">
	<?=_SAVE; ?></a>&nbsp;&nbsp;
	<img src="theme/<?=$cfg['theme']; ?>/images/back.gif" border="0" align="absmiddle"/>
	<a href="setting.php?modname=ezshopingcart" >
	<?=_BACK; ?></a><br><br>

    <table cellpadding="3" cellspacing="1" >
	<input type="hidden" name="modname" value="<?=$module_name; ?>">
	<input type="hidden" name="mf" value="ezedit">
    <input type="hidden" name="ac" value="showcase">
    <input type="hidden" name="hide2Name" id="hide2" />
    <tr>
        <th><?=_SHOW_PRODUCT; ?></th>
        <td>&nbsp;</td>
        <th><?=_AVAILABLE_PRODUCT; ?></th>
    </tr>
    <tr>
    <td>
        <?
            $avList=array();
            $rsSelected=$ezshop->getProductShowcase();
        ?>
        <select id="selectedOptions" name="selectedOptions" size="10" multiple="multiple" style="width: 200px;">
        <?
            while (!$rsSelected->EOF) {
                ?><option value="<?=$rsSelected->fields['prdId']; ?>"><?=$rsSelected->fields['prdTitle']; ?></option><?
            array_push($avList,$rsSelected->fields['prdId']);
            $rsSelected->movenext();
         }
        ?>
        </select>
    </td>
    <td>
  	    <input type="button" class="inputButton" onclick="moveOptions(document.getElementById('availableOptions'), document.getElementById('selectedOptions'));" value="&lt;--"><br/>
		<input type="button" class="inputButton" onclick="moveOptions(document.getElementById('selectedOptions'), document.getElementById('availableOptions'));" value="--&gt;"><br/>
    </td>
    <td>
         <?
               $rsAvailable=$ezshop->getProductAvailable($avList);
         ?>
        <select id="availableOptions" name="availableOptions" size="10" multiple="multiple" style="width: 200px;">
        <?
            while (!$rsAvailable->EOF) {
                ?><option value="<?=$rsAvailable->fields['prdId']; ?>"><?=$rsAvailable->fields['prdTitle']; ?></option><?
              $rsAvailable->movenext();
         }
         ?>
        </select>
    </td>
    </table>
    </form>
    <br/>



