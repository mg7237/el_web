<?php
/* @var $this \yii\web\View */
/* @var $content string */

use \yii\web\Request;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);

use yii\helpers\Url;
?>


<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-121786083-1"></script>
        <?php if (YII_ENV == 'prod') { ?>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag() {
                    dataLayer.push(arguments);
                }
                gtag('js', new Date());
                gtag('config', 'UA-121786083-1');
            </script>
        <?php } ?>
        <!-- Bootstrap Core CSS -->
        <link href="<?php echo Url::home(true); ?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo Url::home(true); ?>css/modal2.css" rel="stylesheet">
        <link href="<?php echo Url::home(true); ?>css/jquery.datetimepicker.css" rel="stylesheet">
        <link href="<?php echo Url::home(true); ?>css/all.css" rel="stylesheet"></head>
    <link rel="stylesheet" type="text/css" href="http://fontawesome.io/assets/font-awesome/css/font-awesome.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php $this->head() ?>
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />

</head>

<body>


    <?= $content ?>

</div><!-- /.modal -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!-- jQuery -->

<script src="<?php echo Url::home(true); ?>js/app/jquery.min.js"></script>
<script src="<?php echo Url::home(true); ?>js/app/bootstrap.min.js"></script>
<script src="<?php echo Url::home(true); ?>js/jquery.datetimepicker.js"></script>

<script src="<?php echo Url::home(true); ?>js/validate.js"></script>
<script>
            $(document).ready(function () {
                $('.datetimepicker1').datetimepicker({
                    datepicker: true,
                    minDate: new Date(),
                    format: 'd-M-Y H:i:s',
                    step: 30,
                    minDateTime: new Date()
                });

            });
            $(document.body).on('hidden.bs.modal', function () {
                $('#myModal').removeData('bs.modal');
            });

</script>


</body>
</html>
<?php $this->endPage() ?>

