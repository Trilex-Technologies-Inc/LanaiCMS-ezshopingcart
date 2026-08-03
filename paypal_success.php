<?php
if (stripos($_SERVER['PHP_SELF'], 'module.php') === false) {
    die("You can't access this file directly...");
}

$module_name = basename(__DIR__);
include_once __DIR__.'/module.php';

$ezcart = new EzShoppingCart();

function paypalFailure($message)
{
    echo '<div class="alert alert-danger" role="alert">'.
        htmlspecialchars($message, ENT_QUOTES, 'UTF-8').
        '</div>';
    return false;
}

function paypalRequest($url, $clientId, $secret, $accessToken = null)
{
    $ch = curl_init($url);
    $headers = array('Accept: application/json');

    if ($accessToken === null) {
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_USERPWD, $clientId.':'.$secret);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    } else {
        $headers[] = 'Authorization: Bearer '.$accessToken;
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $body = curl_exec($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($body === false || $curlError !== '' || $httpCode < 200 || $httpCode >= 300) {
        error_log('PayPal API request failed (HTTP '.$httpCode.'): '.$curlError);
        return false;
    }

    $data = json_decode($body, true);
    return is_array($data) ? $data : false;
}

if (!$sys_lanai->isUserLogin()) {
    paypalFailure('Please sign in again before confirming the payment.');
    return;
}

$orderId = isset($_GET['order_id']) ? trim((string) $_GET['order_id']) : '';
$cartId = isset($_GET['invoice']) ? (int) $_GET['invoice'] : 0;
if ($orderId === '' || !preg_match('/\A[A-Za-z0-9_-]+\z/', $orderId) || $cartId < 1) {
    paypalFailure('The PayPal confirmation is invalid.');
    return;
}

// Restrict payment confirmation to the currently logged-in customer.
$cart = $ezcart->getCartByIdForUser($cartId);
if ($cart === false || $cart->EOF || $cart->fields['crtStatus'] !== 'p') {
    paypalFailure('This order cannot be confirmed. It may already have been processed.');
    return;
}

$payitem = $ezcart->getPaymentMethod((int) $cart->fields['payId']);
if ($payitem === false || $payitem->EOF || $payitem->fields['payModule'] !== 'paypal') {
    paypalFailure('The payment method for this order is invalid.');
    return;
}

$clientId = trim((string) $payitem->fields['payToken']);
$secret = trim((string) $payitem->fields['paySecret']);
$currency = strtoupper(trim((string) $payitem->fields['currency']));
if ($clientId === '' || $secret === '' || !preg_match('/\A[A-Z]{3}\z/', $currency)) {
    paypalFailure('PayPal is not configured correctly.');
    return;
}

$details = $ezcart->getCartDetailById($cartId);
$subtotal = 0.0;
$quantity = 0;
if ($details === false) {
    paypalFailure('Unable to load the order details.');
    return;
}
while (!$details->EOF) {
    $subtotal += (float) $details->fields['prdPrice'] * (int) $details->fields['crtQuantity'];
    $quantity += (int) $details->fields['crtQuantity'];
    $details->movenext();
}
if ($quantity < 1) {
    paypalFailure('The order has no items.');
    return;
}

$expectedAmount = number_format(
    $subtotal + (float) $ezcart->getShippingCost((int) $cart->fields['shpId'], $quantity),
    2,
    '.',
    ''
);
$expectedReference = sprintf('%010d', $cartId);
$baseUrl = !empty($payitem->fields['isSandbox'])
    ? 'https://api-m.sandbox.paypal.com'
    : 'https://api-m.paypal.com';

$tokenData = paypalRequest($baseUrl.'/v1/oauth2/token', $clientId, $secret);
if ($tokenData === false || empty($tokenData['access_token'])) {
    paypalFailure('Unable to authenticate with PayPal. Check the configured credentials and mode.');
    return;
}

$orderData = paypalRequest(
    $baseUrl.'/v2/checkout/orders/'.rawurlencode($orderId),
    $clientId,
    $secret,
    $tokenData['access_token']
);
if ($orderData === false) {
    paypalFailure('Unable to verify the payment with PayPal.');
    return;
}

$unit = isset($orderData['purchase_units'][0]) ? $orderData['purchase_units'][0] : array();
$amount = isset($unit['amount']) ? $unit['amount'] : array();
$captures = isset($unit['payments']['captures']) ? $unit['payments']['captures'] : array();
$captureCompleted = false;
foreach ($captures as $capture) {
    if (isset($capture['status']) && $capture['status'] === 'COMPLETED') {
        $captureCompleted = true;
        break;
    }
}

$verified = isset($orderData['id'], $orderData['status'], $unit['reference_id'], $amount['value'], $amount['currency_code'])
    && hash_equals($orderId, (string) $orderData['id'])
    && $orderData['status'] === 'COMPLETED'
    && $captureCompleted
    && hash_equals($expectedReference, (string) $unit['reference_id'])
    && hash_equals($expectedAmount, number_format((float) $amount['value'], 2, '.', ''))
    && hash_equals($currency, strtoupper((string) $amount['currency_code']));

if (!$verified) {
    error_log('PayPal verification mismatch for cart '.$cartId.' and order '.$orderId);
    paypalFailure('PayPal returned payment details that do not match this order.');
    return;
}

if (!$ezcart->setPaidOrderStatusForUser($cartId)) {
    paypalFailure('Payment was verified, but the order status could not be updated. Please contact support.');
    return;
}
?>
<div class="container mt-4">
    <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Payment Successful!</h4>
        <p><strong>Invoice:</strong> <?=htmlspecialchars($expectedReference, ENT_QUOTES, 'UTF-8');?></p>
        <p><strong>Transaction ID:</strong> <?=htmlspecialchars($orderData['id'], ENT_QUOTES, 'UTF-8');?></p>
        <p><strong>Amount:</strong> <?=htmlspecialchars($expectedAmount, ENT_QUOTES, 'UTF-8');?> <?=htmlspecialchars($currency, ENT_QUOTES, 'UTF-8');?></p>
    </div>
</div>
