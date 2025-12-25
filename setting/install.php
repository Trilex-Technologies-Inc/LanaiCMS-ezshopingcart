<?

	if ( !eregi( "setting.php", $_SERVER['PHP_SELF'] ) ) {
	    die ( "You can't access this file directly..." );
	} 
	
	$module_name = basename( dirname( substr( __FILE__, 0, strlen( dirname( __FILE__ ) ) ) ) );
	$modfunction = "modules/$module_name/module.php";
	include_once( $modfunction ); 
		
	$dwn=new EzShop2();
?>
<span class="txtContentTitle">Install</span><br/><br/>
<OL>
<?
	global $cfg,$db;
	switch($_REQUEST['step']){
		case "1": 			
			// create nessary tables
			?><LI>Create Table <?=$cfg['tablepre']."ezshop_product_item" ?> <?
			
			// create table script
			//$sql="DROP TABLE IF EXISTS ".$cfg['tablepre']."ezshop_product_item";
			//$db->execute($sql);

			$sql="CREATE TABLE IF NOT EXISTS ".$cfg['tablepre']."ezshop_product_item (
                    prdId INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
                    catId INTEGER UNSIGNED NOT NULL,
                    prdTitle VARCHAR(80) NOT NULL,
                    prdDescription TEXT NULL,
                    prdPrice FLOAT NULL,
                    prdBestPrice FLOAT NULL,
                    prdActive ENUM('y','n') DEFAULT 'y',
                    prdCreate TIMESTAMP,
                    PRIMARY KEY(prdId)
                  )";
			$rs1=$db->execute($sql);
			
			if (empty($rs1)) {
				?><span style="color:red;">Error!</span><?
			} else {
				?><span style="color:green;">OK</span><?
			}
					
			?><LI>Create Table <?=$cfg['tablepre']."ezshop_category" ?> <?
			//$sql="DROP TABLE IF EXISTS ".$cfg['tablepre']."ezshop_category";
			//$db->execute($sql);
			$sql="CREATE TABLE IF NOT EXISTS ".$cfg['tablepre']."ezshop_category (
                    catId INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
                    catParentId INTEGER UNSIGNED NOT NULL DEFAULT 0,
                    catTitle VARCHAR(80) NOT NULL,
                    catDescription TEXT NULL,
                    catActive ENUM('y','n') NULL DEFAULT 'y',
                    PRIMARY KEY(catId)
                  )";
			$rs2=$db->execute($sql);
			
			if (empty($rs2)) {
				?><span style="color:red;">Error!</span><?
			} else {
				?><span style="color:green;">OK</span><?
			}
			
			?><LI>Create Table <?=$cfg['tablepre']."ezshop_showcase" ?> <?
			//$sql="DROP TABLE IF EXISTS ".$cfg['tablepre']."ezshop_showcase";
			//$db->execute($sql);
			$sql="CREATE TABLE IF NOT EXISTS ".$cfg['tablepre']."ezshop_showcase (
                      scsId INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
                      prdId INTEGER UNSIGNED NOT NULL,
                      PRIMARY KEY(scsId)
                    )";
			$rs3=$db->execute($sql);
			
			if (empty($rs3)) {
				?><span style="color:red;">Error!</span><?
			} else {
				?><span style="color:green;">OK</span><?
			}

			?><LI>Create Table <?=$cfg['tablepre']."ezshop_showcase_config" ?> <?
			//$sql="DROP TABLE IF EXISTS ".$cfg['tablepre']."ezshop_showcase_config";
			//$db->execute($sql);
			$sql="CREATE TABLE IF NOT EXISTS ".$cfg['tablepre']."ezshop_showcase_config (
						  cfgPriceInShowcase ENUM('y','n') NOT NULL DEFAULT 'n',
						  cfgBestPriceInShowcase ENUM('y','n') NOT NULL DEFAULT 'n',
						  cfgTitleInShowcase ENUM('y','n') NOT NULL DEFAULT 'n',
						  cfgDescriptionInShowcase ENUM('y','n') NOT NULL DEFAULT 'n',
						  cfgPriceInDetail ENUM('y','n') NOT NULL DEFAULT 'n',
						  cfgBestPriceInDetail ENUM('y','n') NOT NULL DEFAULT 'n',
						  cfgTitleInDetail ENUM('y','n') NOT NULL DEFAULT 'n',
						  cfgDescriptionInDetail ENUM('y','n') NOT NULL DEFAULT 'n',
						  cfgCurrencySymbol VARCHAR(10) NULL DEFAULT '$',
						  cfgColumnNumber INTEGER UNSIGNED NOT NULL DEFAULT 4
						)";
			$rs4=$db->execute($sql);

			$sql="INSERT INTO `".$cfg['tablepre']."ezshop_showcase_config` ( `cfgPriceInShowcase` , `cfgBestPriceInShowcase` , `cfgTitleInShowcase` , `cfgDescriptionInShowcase` , `cfgPriceInDetail` , `cfgBestPriceInDetail` , `cfgTitleInDetail` , `cfgDescriptionInDetail` , `cfgCurrencySymbol` , `cfgColumnNumber` )	VALUES ( 'n', 'n', 'y', 'y', 'y', 'y', 'y', 'y', 'USD', '4'	)";
			$db->execute($sql);
			
			if (empty($rs4)) {
				?><span style="color:red;">Error!</span><?
			} else {
				?><span style="color:green;">OK</span><?
			}

			?><LI>Create Table <?=$cfg['tablepre']."ezshop_cart_item" ?> <?
			//$sql="DROP TABLE IF EXISTS ".$cfg['tablepre']."ezshop_showcase";
			//$db->execute($sql);
			$sql="CREATE TABLE IF NOT EXISTS ".$cfg['tablepre']."ezshop_cart_item (
						  prdId INTEGER UNSIGNED NOT NULL,
						  crtId INTEGER UNSIGNED NOT NULL DEFAULT 0,
						  crtSession VARCHAR(32) NOT NULL,
						  prdPrice FLOAT NULL,
						  crtQuantity SMALLINT UNSIGNED NULL DEFAULT 1
						)";
			$rs5=$db->execute($sql);			
			if (empty($rs5)) {
				?><span style="color:red;">Error!</span><?
			} else {
				?><span style="color:green;">OK</span><?
			}

			?><LI>Create Table <?=$cfg['tablepre']."ezshop_cart" ?> <?
			//$sql="DROP TABLE IF EXISTS ".$cfg['tablepre']."ezshop_showcase";
			//$db->execute($sql);
			$sql="CREATE TABLE IF NOT EXISTS ".$cfg['tablepre']."ezshop_cart (
						  crtId INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
						  crtSession VARCHAR(32) NOT NULL,
						  userId INTEGER UNSIGNED NOT NULL,
						  shpId INTEGER UNSIGNED NOT NULL,
						  payId INTEGER UNSIGNED NOT NULL,
						  crtStatus ENUM('p','s','c','t') NULL DEFAULT 'p',
						  crtRemark TEXT NULL,
						  crtCreate TIMESTAMP,
						  PRIMARY KEY(crtId)
						)";
			$rs6=$db->execute($sql);			
			if (empty($rs6)) {
				?><span style="color:red;">Error!</span><?
			} else {
				?><span style="color:green;">OK</span><?
			}

			?><LI>Create Table <?=$cfg['tablepre']."ezshop_shipping" ?> <?
			//$sql="DROP TABLE IF EXISTS ".$cfg['tablepre']."ezshop_showcase";
			//$db->execute($sql);
			$sql="CREATE TABLE IF NOT EXISTS ".$cfg['tablepre']."ezshop_shipping (
						  shpId INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
						  shpTitle VARCHAR(20) BINARY NOT NULL,
						  shpDescription TEXT NULL,
						  shpRateType ENUM('f','r') NULL DEFAULT 'f',
						  shpRateTable VARCHAR(100) NULL,
						  shpRateTableType ENUM('q','w') NULL DEFAULT 'q',
						  shpFixRate FLOAT NULL,
						  PRIMARY KEY(shpId)
						)";
			$rs7=$db->execute($sql);			
			if (empty($rs7)) {
				?><span style="color:red;">Error!</span><?
			} else {
				?><span style="color:green;">OK</span><?
			}

			?><LI>Create Table <?=$cfg['tablepre']."ezshop_payment" ?> <?
			//$sql="DROP TABLE IF EXISTS ".$cfg['tablepre']."ezshop_showcase";
			//$db->execute($sql);
			$sql="CREATE TABLE IF NOT EXISTS ".$cfg['tablepre']."ezshop_payment (
							  payId INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
							  payTitle VARCHAR(40) NOT NULL,
							  payDescription TEXT NULL,
							  payModule VARCHAR(30) NULL,
							  payToken VARCHAR(250) NULL,
                              isSandbox TINYINT(1) NOT NULL DEFAULT 0,
                              paySecret VARCHAR(250) NULL,
                              currency VARCHAR(10) NULL,
							  
						     PRIMARY KEY(payId)
							)";
			$rs8=$db->execute($sql);			
			if (empty($rs8)) {
				?><span style="color:red;">Error!</span><?
			} else {
				?><span style="color:green;">OK</span><?
			}
			
			if ((!empty($rs1)) AND (!empty($rs2)) AND (!empty($rs3)) AND (!empty($rs4))  AND (!empty($rs5))  AND (!empty($rs6))   AND (!empty($rs7))   AND (!empty($rs8)) ) {
				?><br><br><input type="button" class="inputButton" value="Next ->" onClick="javascript:location.href='<?=$_SERVER['PHP_SELF']?>?modname=<?=$module_name; ?>&mf=install&step=2';"><?
			}
			
			break;
		case "2":
			// create module data and menu
			// check module data exist!
			$sql="SELECT COUNT(*),modId FROM ".$cfg['tablepre']."module 
					WHERE modName='ezshopingcart' GROUP BY modId";
			$rs=$db->execute($sql);
			if (($rs->fields[0])>0) {
				// delete from module table
				$sql="DELETE FROM ".$cfg['tablepre']."module 
					WHERE modId=".$rs->fields[1];
				$db->execute($sql);
				// delete from menu table
				$sql="DELETE FROM ".$cfg['tablepre']."menu 
					WHERE modId=".$rs->fields[1]." AND mnuType='m'";
				$db->execute($sql);
				// delete from privilege table
				$sql="DELETE FROM ".$cfg['tablepre']."privilege 
					WHERE modId=".$rs->fields[1];
				$db->execute($sql);
			}
			// select for max order 
			// select module data
			$sql="SELECT MAX(modOrder) FROM ".$cfg['tablepre']."module ";
			$rsOModule=$db->execute($sql);
			?><LI>Create module data<?
			// create module data
			$sql="INSERT INTO ".$cfg['tablepre']."module 
					(modTitle,modName,modActive,modOrder,modSetting) 
					VALUES ('ezshopingcart','ezshopingcart','y',".(($rsOModule->fields[0])+1).",'y')";
			$rs1=$db->execute($sql);						
			// select module data
			$sql="SELECT COUNT(*),modId FROM ".$cfg['tablepre']."module 
					WHERE modName='ezshopingcart' GROUP BY modId";
			$rsIModule=$db->execute($sql);			
			// select module data
			$sql="SELECT MAX(mnuOrder) FROM ".$cfg['tablepre']."menu ";
			$rsOMenu=$db->execute($sql);
			?><LI>Create menu data<?
			// create menu data
			$sql="INSERT INTO ".$cfg['tablepre']."menu 
					(mnuParentId,mnuTitle,modId,mnuType,mnuActive,mnuOrder)
					VALUES (0,'EzShopingCart',".$rsIModule->fields[1].",'m','y',".(($rsOMenu->fields[0])+1).")";
			$rs2=$db->execute($sql);	
			?><LI>Create privilege data<?
			// create privilege data
			$sql="INSERT INTO ".$cfg['tablepre']."privilege 
					(modAccess,modId,userPrivilege)
					VALUES ('y',".$rsIModule->fields[1].",'a')";
			$rs3=$db->execute($sql);			
			if ((!empty($rs1)) AND (!empty($rs2)) AND (!empty($rs3))) {
				?><br><br><input type="button" class="inputButton" value="Install Complete Click to Setting" onClick="javascript:location.href='<?=$_SERVER['PHP_SELF']?>?modname=<?=$module_name; ?>';"><?
			}
			break;
		default:
			// check nessary environment			
			// 1 check safe mode
			?><LI>PHP Safe Mode is <?
			if (ini_get('safe_mode')) {
			    ?><span style="color:green;">ON</span><?
			} else {
				?><span style="color:red;">OFF</span><?
			}
			// 2 check dir exist
			?> 
			<LI>Module Directory & EzShop Data Directory is 
			<?
			
			if ((is_writable($cfg['dir'].$sys_lanai->getPath()."modules")) AND (is_writable($cfg['dir'].$sys_lanai->getPath()."modules"))  AND (is_writable($cfg['datadir'].$sys_lanai->getPath()."ezshop")) ) {
				?>
				<span style="color:green;">WRITABLE</span><br/><br/>
				<input type="button" class="inputButton" value="Next ->" onClick="javascript:location.href='<?=$_SERVER['PHP_SELF']?>?modname=<?=$module_name; ?>&mf=install&step=1';">
				<?
			} else {
				?><span style="color:red;">NOT WRITABLE</span>, please change permission in <span style="color:red;">'modules'</span> and create <span style="color:red;">'datacenter/ezshop'</span> and change its permission<?
			}
			
	} // switch
	
	
?>
</OL>
