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
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo Url::home(true); ?>bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?php echo Url::home(true); ?>css/metisMenu.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo Url::home(true); ?>css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="<?php echo Url::home(true); ?>css/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo Url::home(true); ?>font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php $this->head() ?>
</head>

<body>

    <div id="wrapper">

       <?php echo  Yii::$app->controller->renderPartial('//../modules/admin/views/layouts/sidebar'); ?>
       
        <div id="page-wrapper">
      
                <?= $content ?>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="<?php echo Url::home(true); ?>js/jquery.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?php echo Url::home(true); ?>js/sb-admin-2.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo Url::home(true); ?>bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo Url::home(true); ?>js/metisMenu.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="<?php echo Url::home(true); ?>js/raphael.js"></script>
    <script src="<?php echo Url::home(true); ?>js/morris.js"></script>
    <script src="<?php echo Url::home(true); ?>js/morris-data.js"></script>


</body>
</html>
<?php $this->endPage() ?>

