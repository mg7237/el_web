<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <link rel="canonical" href="<?= Yii::$app->request->absoluteUrl; ?>" />
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
        <link href="<?php echo Url::home(true); ?>css/all.css" rel="stylesheet"></head>
    <link rel="stylesheet" type="text/css" href="http://fontawesome.io/assets/font-awesome/css/font-awesome.css">
    <?php $this->head() ?>
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
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
</head>
<body>
<?php $this->beginBody() ?>

    <nav id="mainNav" class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand page-scroll" href="<?php echo Url::home(true); ?>"><img src="<?php echo Url::home(true); ?>images/property_logo.png" alt=""></a>
            </div>
            <div class="navbar-right butlogout">
                <?php
                echo '<a>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                        '<div style= "color: #26344B;">Logout</div>', ['class' => 'btn btn-link logout', 'style' => 'color:#000;']
                )
                . Html::endForm()
                . '</a>'
                ?>

            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
    <div class="clearfix"> </div>


<?= $content ?>
    <br />
    <div class="clearfix"> </div>
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
<?php $this->endBody() ?>
    <script src="<?php echo Url::home(true); ?>js/app/bootstrap.min.js"></script>

</body>
</html>
<?php $this->endPage() ?>
