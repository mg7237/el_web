<html>
    <head>
        <title>Easyleases Checkout Page</title>
    </head>
    <body>
    <center><h1>Please do not refresh this page...</h1></center>
    <form method='post' action='<?php echo $transactionURL; ?>' name='f1'>
        <?php
        foreach ($paytmParams as $name => $value) {
            echo '<input type="hidden" name="' . $name . '" value="' . $value . '">';
        }
        ?>
        <input type="hidden" name="CHECKSUMHASH" value="<?php echo $paytmChecksum ?>">
    </form>
    <script type="text/javascript">
        document.f1.submit();
    </script>
</body>
</html>
<?php exit; ?>