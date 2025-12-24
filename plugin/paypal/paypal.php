<?php
// ===============================
// PAYPAL CONFIG (SANDBOX)
// ===============================
$PAYPAL_CLIENT_ID = $payitem->fields['payToken'];
$CURRENCY = $payitem->fields['currency'];

// $amt, $prd, $inv come from checkout file
?>
<div style="max-width:400px;margin-top:20px">
    <div id="paypal-button-container"></div>
</div>

<script src="https://www.sandbox.paypal.com/sdk/js?client-id=<?=$PAYPAL_CLIENT_ID;?>&currency=<?=$CURRENCY;?>"></script>

<script>
paypal.Buttons({

    createOrder: function (data, actions) {
        return actions.order.create({
            purchase_units: [{
                reference_id: "<?= $inv ?>",
                description: "<?= $prd ?>",
                amount: {
                    value: "<?= $amt ?>",
                    currency_code: "<?= $CURRENCY ?>"
                }
            }]
        });
    },

    onApprove: function (data, actions) {
        return actions.order.capture().then(function (details) {
            window.location.href =
                "module.php?modname=ezshopingcart&mf=paypal_success&order_id=" + data.orderID +
                "&invoice=<?= $inv ?>&payId=<?= $_REQUEST['payId'] ?>";
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
