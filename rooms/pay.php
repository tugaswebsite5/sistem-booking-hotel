<?php require "../includes/header.php"; ?>
<?php require "../config/config.php"; ?>

    <div class="container">  
                    <!-- Replace "test" with your own sandbox Business account app client ID -->
                    <script src="https://www.paypal.com/sdk/js?client-id=AVIKAOhPLbjmswLT1FSZSbVlQC0ioVRF6H25WMTwrdHyis28zXW-ws4WEL_qGsNLkwDyHjd53Z1ue1FP&currency=USD"></script>
                    <!-- Set up a container element for the button -->
                    <div id="paypal-button-container"></div>
                    <script>
                        paypal.Buttons({
                        // Sets up the transaction when a payment button is clicked
                        createOrder: (data, actions) => {
                            return actions.order.create({
                            purchase_units: [{
                                amount: {
                                value: '<?php echo $_SESSION['price']; ?>' // Can also reference a variable or function
                                }
                            }]
                            });
                        },
                        // Finalize the transaction after payer approval
                        onApprove: (data, actions) => {
                            return actions.order.capture().then(function(orderData) {
                          
                             window.location.href='http://localhost/sistem-booking-hotel';
                            });
                        }
                        }).render('#paypal-button-container');
                    </script>
                  
     </div>
<?php require "../includes/footer.php"; ?>

