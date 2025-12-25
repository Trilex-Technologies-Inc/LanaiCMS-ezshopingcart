<?

	if ( !eregi( "setting.php", $_SERVER['PHP_SELF'] ) ) {
	    die ( "You can't access this file directly..." );
	}

	$module_name = basename( dirname( substr( __FILE__, 0, strlen( dirname( __FILE__ ) ) ) ) );
	$modfunction = "modules/$module_name/module.php";
	include_once( $modfunction );

	$ezshop=new EzShop2();
    $ezshcart=new EzShoppingCart();

    switch($_REQUEST['ac']){
		case "new":
			   	if (empty($_REQUEST['prdTitle'])) {
			   		$sys_lanai->getErrorBox(_REQUIRE_FIELDS." <a href=\"#\" onClick=\"javascript:history.back();\">"._BACK."</a>");
				} else {
                  // store data in database
                  $rs=$ezshop->setProductNew($_REQUEST['catId'],trim($_REQUEST['prdTitle']),$_REQUEST['prdDescription'],$_REQUEST['prdPrice'],$_REQUEST['prdBestPrice'],$_REQUEST['prdActive']);
                  // if success then query back by product title
                    if ($rs) {
                     $rsitem=$ezshop->getProductByTitle(trim($_REQUEST['prdTitle']));
                     foreach ($_FILES["userfile"]["error"] as $key => $error) {
                         if ($error == UPLOAD_ERR_OK) {
                             $tmp_name = $_FILES["userfile"]["tmp_name"][$key];
                             //$name = $_FILES["userfile"]["name"][$key];
                             //$name=basename($_FILES["userfile"]["name"][$key],".jpg");
                             if ($key==0) {
                                $filename="img_".$rsitem->fields['prdId']."_small.jpg";
                             } else if ($key==1) {
                                $filename="img_".$rsitem->fields['prdId']."_big1.jpg";
                             } else if ($key==2) {
                                $filename="img_".$rsitem->fields['prdId']."_big2.jpg";
                             } else if ($key==3) {
                                $filename="img_".$rsitem->fields['prdId']."_action1.jpg";
                             } else if ($key==4) {
                                $filename="img_".$rsitem->fields['prdId']."_action2.jpg";
                             } else if ($key==5) {
                                $filename="img_".$rsitem->fields['prdId']."_action3.jpg";
                             } else if ($key==6) {
                                $filename="img_".$rsitem->fields['prdId']."_action4.jpg";
                             }
                             //echo $ezshop->cfg['datadir'].$sys_lanai->getPath()."ezshop".$sys_lanai->getPath().$name;
                             move_uploaded_file($tmp_name, $ezshop->cfg['datadir'].$sys_lanai->getPath()."ezshop".$sys_lanai->getPath().$filename);
                         }
                     }
                  }
                  $sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=product");
                }
        break;
        case "edit" :
                if (empty($_REQUEST['prdTitle'])) {
			   		$sys_lanai->getErrorBox(_REQUIRE_FIELDS." <a href=\"#\" onClick=\"javascript:history.back();\">"._BACK."</a>");
				} else {
                  // store data in database
                  $rs=$ezshop->setProductEdit($_REQUEST['mid'],$_REQUEST['catId'],trim($_REQUEST['prdTitle']),$_REQUEST['prdDescription'],$_REQUEST['prdPrice'],$_REQUEST['prdBestPrice'],$_REQUEST['prdActive']);
                  // if success then query back by product title
                    if ($rs) {
                     $rsitem=$ezshop->getProductItem($_REQUEST['mid']);
                     foreach ($_FILES["userfile"]["error"] as $key => $error) {
                         if ($error == UPLOAD_ERR_OK) {
                             $tmp_name = $_FILES["userfile"]["tmp_name"][$key];
                             //$name = $_FILES["userfile"]["name"][$key];
                             //$name=basename($_FILES["userfile"]["name"][$key],".jpg");
                             if ($key==0) {
                                $filename="img_".$rsitem->fields['prdId']."_small.jpg";
                             } else if ($key==1) {
                                $filename="img_".$rsitem->fields['prdId']."_big1.jpg";
                             } else if ($key==2) {
                                $filename="img_".$rsitem->fields['prdId']."_big2.jpg";
                             } else if ($key==3) {
                                $filename="img_".$rsitem->fields['prdId']."_action1.jpg";
                             } else if ($key==4) {
                                $filename="img_".$rsitem->fields['prdId']."_action2.jpg";
                             } else if ($key==5) {
                                $filename="img_".$rsitem->fields['prdId']."_action3.jpg";
                             } else if ($key==6) {
                                $filename="img_".$rsitem->fields['prdId']."_action4.jpg";
                             }
                             //echo $ezshop->cfg['datadir'].$sys_lanai->getPath()."ezshop".$sys_lanai->getPath().$name;
                             move_uploaded_file($tmp_name, $ezshop->cfg['datadir'].$sys_lanai->getPath()."ezshop".$sys_lanai->getPath().$filename);
                         }
                     }
                  }
                  $sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=product");
                }
        break;
		case "gnew":
                if (empty($_REQUEST['catTitle'])) {
			   		$sys_lanai->getErrorBox(_REQUIRE_FIELDS." <a href=\"javascript:history.back();\">"._BACK."</a>");
				} else {
                    $ezshop->setCategoryNew($_REQUEST['catParentId'],$_REQUEST['catTitle'],$_REQUEST['catDescription'],$_REQUEST['catActive']);
                    $sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=category");
                }
        break;
        case "gedit" :
            if (empty($_REQUEST['catTitle'])) {
			   		$sys_lanai->getErrorBox(_REQUIRE_FIELDS." <a href=\"javascript:history.back();\">"._BACK."</a>");
				} else {
                    $ezshop->setCategoryEdit($_REQUEST['mid'],$_REQUEST['catParentId'],$_REQUEST['catTitle'],$_REQUEST['catDescription'],$_REQUEST['catActive']);
                    $sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=category");
                }
        break;
        case "active":
				$ezshop->setProductActive($_REQUEST['mid'],$_REQUEST['v']);
				$sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=product");
		break;
        case "mactive":
				$midarr=$_REQUEST['mid'];
				for ($i=0;$i<count($midarr);$i++) {
					$rsdwn=$ezshop->getProductItem($midarr[$i]);
					if ($rsdwn->fields['prdActive']=='y') {
					    $value="n";
					} else {
			            $value="y";
					}
			        $ezshop->setProductActive($midarr[$i],$value);
				}
			   $sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=product");
			break;
		case "mdelete":
				$midarr=$_REQUEST['mid'];
				for ($i=0;$i<count($midarr);$i++) {
					$ezshop->setProductDelete($midarr[$i]);
				}
			    $sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=product");
			break;
		case "gactive":
				$ezshop->setCategoryActive($_REQUEST['mid'],$_REQUEST['v']);
				$sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=category");
			break;
		case "mgactive":
				$midarr=$_REQUEST['mid'];
				for ($i=0;$i<count($midarr);$i++) {
					$rsdwn=$ezshop->getCategoryItem($midarr[$i]);
					if ($rsdwn->fields['catActive']=='y') {
					    $value="n";
					} else {
						$value="y";
					}
					$ezshop->setCategoryActive($midarr[$i],$value);
				}
				$sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=category");
			break;
		case "mgdelete":
				$midarr=$_REQUEST['mid'];
				for ($i=0;$i<count($midarr);$i++) {
					$ezshop->setCategoryDelete($midarr[$i]);
				}
				$sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=category");
			break;
        case "showcase" :
             $ezshop->setShowcaseDelete();
             $selSplit = explode(".", $_REQUEST['hide2Name']);
             foreach ($selSplit as $prdId) {
                $ezshop->setShowcaseNew($prdId);
             }
            $sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=display");
        break;
		case "config" :
			 // set showcase config
		$ezshop->setShowcaseConfig($_REQUEST['cfgTitleInShowcase'],$_REQUEST['cfgDescriptionInShowcase'],$_REQUEST['cfgPriceInShowcase'],$_REQUEST['cfgBestPriceInShowcase'],$_REQUEST['cfgTitleInDetail'],$_REQUEST['cfgDescriptionInDetail'],$_REQUEST['cfgPriceInDetail'],$_REQUEST['cfgBestPriceInDetail'],$_REQUEST['cfgCurrencySymbol'],$_REQUEST['cfgColumnNumber']);
		$sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=config");
		break;

        // shopping cart
        case "shippingnew" :
            $ezshop->setShippingNew($_REQUEST['shpTitle'],$_REQUEST['shpDescription'],$_REQUEST['shpRateType'],$_REQUEST['shpRateTable'],$_REQUEST['shpRateTableType'],$_REQUEST['shpFixRate']);
            $sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=shipping");
        break;
        case "shippingedit" :
            $ezshop->setShippingEdit($_REQUEST['mid'],$_REQUEST['shpTitle'],$_REQUEST['shpDescription'],$_REQUEST['shpRateType'],$_REQUEST['shpRateTable'],$_REQUEST['shpRateTableType'],$_REQUEST['shpFixRate']);
            $sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=shipping");
        break;
        case "mshippingdelete" :
          	$midarr=$_REQUEST['mid'];
		        for ($i=0;$i<count($midarr);$i++) {
			    $ezshop->setShippingDelete($midarr[$i]);
	    	}
            $sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=shipping");
        break;
      case "paymentnew":
    if (empty($_REQUEST['payTitle'])) {
        $sys_lanai->getErrorBox(_REQUIRE_FIELDS." <a href=\"#\" onClick=\"javascript:history.back();\">"._BACK."</a>");
    } else {
        $is_sandbox = isset($_REQUEST['is_sandbox']) ? (int)$_REQUEST['is_sandbox'] : 0;

        $ezshop->setPaymentNew(
            $_REQUEST['payTitle'],
            $_REQUEST['payDescription'],
            $_REQUEST['payModule'],
            $_REQUEST['payToken'],
            isset($_REQUEST['paySecret']) ? $_REQUEST['paySecret'] : '',
            $_REQUEST['currency'],
            $is_sandbox // added sandbox
        );
        $sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=payment");
    }
    break;

case "paymentedit":
    if (empty($_REQUEST['payTitle'])) {
        $sys_lanai->getErrorBox(_REQUIRE_FIELDS." <a href=\"#\" onClick=\"javascript:history.back();\">"._BACK."</a>");
    } else {
        $is_sandbox = isset($_REQUEST['is_sandbox']) ? (int)$_REQUEST['is_sandbox'] : 0;

        $ezshop->setPaymentEdit(
            $_REQUEST['mid'],
            $_REQUEST['payTitle'],
            $_REQUEST['payDescription'],
            $_REQUEST['payModule'],
            $_REQUEST['payToken'],
            isset($_REQUEST['paySecret']) ? $_REQUEST['paySecret'] : '',
            $_REQUEST['currency'],
            $is_sandbox // added sandbox
        );
        $sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=payment");
    }
    break;


        case "mpaymentdelete" :
          	$midarr=$_REQUEST['mid'];
		        for ($i=0;$i<count($midarr);$i++) {
			    $ezshop->setPaymentDelete($midarr[$i]);
	    	}
            $sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=shipping");
        break;
        case "morderpending" :
            $midarr=$_REQUEST['mid'];
		    for ($i=0;$i<count($midarr);$i++) {
                $ezshop->setOrderStatus($midarr[$i],"p");
	        }
            $sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=order");
        break;
        case "mordertrans" :
            $midarr=$_REQUEST['mid'];
		    for ($i=0;$i<count($midarr);$i++) {
                $ezshop->setOrderStatus($midarr[$i],"t");
            }
            $sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=order");
        break;
        case "mordershipped" :
            $midarr=$_REQUEST['mid'];
		    for ($i=0;$i<count($midarr);$i++) {
                $ezshop->setOrderStatus($midarr[$i],"s");
            }
            $sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=order");
        break;
        case "mordercancel" :
            $midarr=$_REQUEST['mid'];
		    for ($i=0;$i<count($midarr);$i++) {
                $ezshop->setOrderStatus($midarr[$i],"c");
            }
            $sys_lanai->go2Page($_SERVER['PHP_SELF']."?modname=".$module_name."&mf=order");
        break;
    }

?>
