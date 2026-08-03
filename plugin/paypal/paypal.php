<?php
$PAYPAL_CLIENT_ID = $payitem->fields['payToken'];
$CURRENCY = strtoupper(trim((string) $payitem->fields['currency']));
$is_sandbox = $payitem->fields['isSandbox'];

if (empty($PAYPAL_CLIENT_ID) || !preg_match('/\A[A-Z]{3}\z/', $CURRENCY)) {
    echo '<div class="alert alert-danger" role="alert">PayPal is not configured correctly.</div>';
    return;
}

// The client ID determines sandbox versus live for the JavaScript SDK.
$paypal_sdk_url = 'https://www.paypal.com/sdk/js?'.http_build_query(array(
    'client-id' => $PAYPAL_CLIENT_ID,
    'currency' => $CURRENCY,
    'intent' => 'capture'
), '', '&', PHP_QUERY_RFC3986);
?>
<div style="max-width:400px;margin-top:20px">
    <div id="paypal-button-container"></div>
</div>

<script src="<?=htmlspecialchars($paypal_sdk_url, ENT_QUOTES, 'UTF-8');?>"></script>

<script>
paypal.Buttons({

    createOrder: function (data, actions) {
        return actions.order.create({
            purchase_units: [{
                reference_id: <?=json_encode($inv);?>,
                description: <?=json_encode($prd);?>,
                amount: {
                    value: <?=json_encode($amt);?>,
                    currency_code: <?=json_encode($CURRENCY);?>
                }
            }]
        });
    },

    onApprove: function (data, actions) {
        return actions.order.capture().then(function (details) {
            window.location.href =
                "module.php?modname=ezshopingcart&mf=paypal_success&order_id=" +
                encodeURIComponent(data.orderID) + "&invoice=" + encodeURIComponent(<?=json_encode($inv);?>);
        });
    },

    onCancel: function () {
        alert("Payment cancelled");
    },

    onError: function (err) {
        console.log(err);
        alert("Payment error");
    }

}).render('#paypal-button-container');
</script>
