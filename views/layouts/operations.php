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
        <script>(function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({'gtm.start':
                            new Date().getTime(), event: 'gtm.js'});
                var f = d.getElementsByTagName(s)[0],
                        j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);

            })(window, document, 'script', 'dataLayer', 'GTM-WHMGT8D');</script>

        <meta name="google-site-verification" content="uKtGStTmPBl5f6s9kJQ-o4C6Gk31Im0BtbHhBhhLGnI" />

        <meta name="geo.region" content="IN-KA" />
        <meta name="geo.placename" content="Bengaluru, Karnataka, India" />
        <meta name="geo.position" content="77.6617983; 13.0013648" />
        <meta name="robots" CONTENT="INDEX, FOLLOW"> 
        <meta name="revisit-after" content="1 days"/> 
        <meta name="author" content="Easyleases"> 
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <!-- Bootstrap Core CSS -->
        <link href="<?php echo Url::home(true); ?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo Url::home(true); ?>css/modal2.css" rel="stylesheet">
        <link href="<?php echo Url::home(true); ?>css/jquery.datetimepicker.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="http://fontawesome.io/assets/font-awesome/css/font-awesome.css">

        <?php $this->head() ?>
        <link href="<?php echo Url::home(true); ?>css/sales.css" rel="stylesheet">
        <link href="<?php echo Url::home(true); ?>css/all.css" rel="stylesheet"></head>
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />


</head>

<body>
    <?php $this->beginBody() ?>

    <nav id="mainNav" class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i> </button>
                <a class="navbar-brand page-scroll" href="<?php echo Url::home(true); ?>"><img src="<?php echo Url::home(true); ?>images/property_logo.png" alt=""></a> 

            </div>
            <?php
            echo '<a class="pull-right">'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                    '<div style= "color: #26344B;">Logout</div>', ['class' => 'btn btn-link logout', 'style' => 'color:#000;']
            )
            . Html::endForm()
            . '</a>'
            ?>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
    <!--leftside-->
    <aside id="left-panel">
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">

                <?php
                echo Nav::widget([
                    'options' => ['class' => 'nav in', 'id' => 'side-menu'],
                    'encodeLabels' => false,
                    'items' => [
                        ['label' => '<span><i class="fa fa-tachometer" aria-hidden="true"></i></span>Dashboard', 'url' => ['/sales'], 'visible' => Yii::$app->userdata->usertype == 6],
                        ['label' => '<span><i class="fa fa-male" aria-hidden="true"></i></span>My Profile', 'url' => ['/sales/myprofile'], 'visible' => Yii::$app->userdata->usertype == 6],
                        ['label' => '<span><i class="fa fa-male" aria-hidden="true"></i></span>Applicant Leads', 'url' => ['/sales/applicants'], 'visible' => Yii::$app->userdata->usertype == 6],
                        ['label' => '<span><i class="fa fa-male" aria-hidden="true"></i></span>Property Owner Leads', 'url' => ['/sales/owners'], 'visible' => Yii::$app->userdata->usertype == 6],
                        ['label' => '<span><i class="fa fa-male" aria-hidden="true"></i></span>Advisor Leads', 'url' => ['/sales/advisers'], 'visible' => Yii::$app->userdata->usertype == 6],
                        ['label' => '<span><i class="fa fa-male" aria-hidden="true"></i></span>Hot Leads/Follows', 'url' => ['/sales/hotleads'], 'visible' => Yii::$app->userdata->usertype == 6],
                    ],
                ]);
                ?>


            </div>
            <!-- /.sidebar-collapse -->
        </div>
    </aside>


    <?= $content ?>

    <footer>
        <div class="container">
            <div class="menulist">
                <ul class="footer-links">
                    <!--li><a href="javascritp:;">FAQ's</a></li>
                    <li><a href="javascritp:;">Privacy Policy</a></li>
                  </ul>
                </div>
                <div class="col-md-3 menulist">
                  <ul>
                    <li><a href="javascritp:;">Site Usage Terms</a></li>
                    <li><a href="javascritp:;">About Us</a></li>
                  </ul>
                </div>
                <div class="col-md-3 menulist">
                  <ul>
                    <li><a href="javascritp:;">Contact Us</a></li>
                    <li><a href="javascritp:;">Social Media Links</a></li>
                  </ul>
                </div>
                <div class="col-md-3 menulist">
                  <ul>
                    <li><a href="javascritp:;">Terms &amp; Conditions</a></li-->
                    <?php
                    echo Yii::$app->userdata->getFooter();
                    ?>
                </ul>
            </div>
        </div>
    </footer>
    <div class="copyright">&copy; 2018 Easyleases Technologies Private Limited</div>

</div><!-- /.modal -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!-- jQuery -->
<script src="<?php echo Url::home(true); ?>js/app/jquery.min.js"></script>

<script src="<?php echo Url::home(true); ?>js/app/bootstrap.min.js"></script>
<script src="<?php echo Url::home(true); ?>js/admin.js"></script>
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<!--
         <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
-->
<script src="<?php echo Url::home(true); ?>js/sales.js"></script>
<script src="<?php echo Url::home(true); ?>js/jquery.datetimepicker.js"></script>

<script>
            $(document).ready(function () {
                $('#example-table').dataTable();
                $('#datetimepicker1').datetimepicker({
                    datepicker: true,
                    minDate: '-1970/01/1',
                    format: 'd-m-Y H:i:s',
                    step: 30,
                    minDateTime: new Date()
                });

            });
            $(document.body).on('hidden.bs.modal', function () {
                $('#myModal').removeData('bs.modal');
            });



</script>
<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>

