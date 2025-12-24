<?php
$module_name = $_REQUEST['modname'];
$modfunction = "modules/$module_name/module.php";
include_once($modfunction);

$ezcart = new EzShoppingCart();
$ezshop = new EzShop2();

$payitem = $ezcart->getPaymentMethod($_REQUEST['payId']);
$PAYPAL_CLIENT_ID = $payitem->fields['payToken'];
$PAYPAL_SECRET = $payitem->fields['paySecret'];
$SANDBOX = true;

$orderID = isset($_GET['order_id']) ? $_GET['order_id'] : '';
$invoice = isset($_GET['invoice']) ? $_GET['invoice'] : '';

if (!$orderID) {
    die('<div class="alert alert-danger" role="alert">No order ID provided.</div>');
}

$baseUrl = $SANDBOX ? "https://api-m.sandbox.paypal.com" : "https://api-m.paypal.com";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . "/v1/oauth2/token");
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Accept: application/json",
    "Accept-Language: en_US"
));
curl_setopt($ch, CURLOPT_USERPWD, $PAYPAL_CLIENT_ID . ":" . $PAYPAL_SECRET);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);
if (!$result) {
    die('<div class="alert alert-danger" role="alert">Error connecting to PayPal</div>');
}

$data = json_decode($result, true);
$accessToken = isset($data['access_token']) ? $data['access_token'] : '';
curl_close($ch);

if (!$accessToken) {
    die('<div class="alert alert-danger" role="alert">Unable to get access token from PayPal</div>');
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . "/v2/checkout/orders/" . $orderID);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "Authorization: Bearer " . $accessToken
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$orderData = curl_exec($ch);
curl_close($ch);

if (!$orderData) {
    die('<div class="alert alert-danger" role="alert">Error fetching order details from PayPal</div>');
}

$orderData = json_decode($orderData, true);
?>
<div class="container mt-4">
<?php if (isset($orderData['status']) && $orderData['status'] === 'COMPLETED') { ?>
    <?php $ezshop->setOrderStatus($invoice, "t"); ?>
    <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Payment Successful!</h4>
        <p><strong>Invoice:</strong> <?php echo htmlspecialchars($invoice); ?></p>
        <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($orderData['id']); ?></p>
        <p><strong>Amount:</strong> <?php echo htmlspecialchars($orderData['purchase_units'][0]['amount']['value']); ?>
            <?php echo htmlspecialchars($orderData['purchase_units'][0]['amount']['currency_code']); ?></p>
    </div>
<?php } else { ?>
    <div class="alert alert-warning" role="alert">
        <h4 class="alert-heading">Payment not completed.</h4>
        <pre><?php print_r($orderData); ?></pre>
    </div>
<?php } ?>
</div>
