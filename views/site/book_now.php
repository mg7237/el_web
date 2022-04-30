<html>
    <head></head>
    <body>
        <form name="billdesk" method="post" action="<?php echo $url ?>">
            <input type="hidden" name="msg" value="<?php echo $msg ?>" />
        </form>
    </body>
    <script>document.billdesk.submit();</script>
</html>
<?php exit; ?>