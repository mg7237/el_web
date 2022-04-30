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
        <meta name="keywords" content="property management maintenance Furnishing tenant acquisition">
        <meta name="author" content="Easyleases Technologies Pvt Ltd">
        <?= Html::csrfMetaTags() ?>
        <!--<title><?= Html::encode($this->title) ?></title>-->
        <?php if (!empty($this->params['head_title']) && !empty($this->params['meta_desc'])) { ?>
            <title><?= $this->params['head_title'] ?></title>
            <meta name="description" content="<?= $this->params['meta_desc'] ?>">
        <?php } else { ?>
            <title>Rental Property Management Services - Easyleases</title>
            <meta name="description" content="End-to-end management of your property | Furnishing, tenant acquisition, rent collection, maintenance & property audit | Real-time dashboard | Full transparency & no hidden charges">
        <?php } ?>
        <?php $this->head() ?>
    <!--link href="<?php echo Url::home(true); ?>css/applicant.css" rel="stylesheet"-->
    <!--link href="<?php echo Url::home(true); ?>css/all.css" rel="stylesheet"></head-->
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
    <body title="Rental Property Management Services">
<?php $this->beginBody() ?>
        <!--
        <header class="header">
                        <div class="container">
                        <div class="col-lg-12"><a href="<?php echo Url::home(true); ?>"><img alt="" src="<?php echo Url::home(true); ?>images/property_logo.png"></a></div>
                        </div>
                </header>
        -->
        <?php
        /* echo "<pre>";
          print_r(Yii::$app->user->identity);die; */
        /* NavBar::begin([
          'innerContainerOptions' => ['class' => 'container-fluid'],
          //  'brandLabel' => 'My Company',
          'brandLabel' => Html::img('@web/images/property_logo.png', ['alt' => Yii::$app->name]),
          'brandUrl' => Yii::$app->homeUrl,
          'options' => [
          'class' => 'navbar navbar-default navbar-fixed-top affix-top',
          ],
          ]); */
        ?>
        <!--div class="navbar-right">
        <?php
        /* echo Nav::widget([
          'options' => ['class' => 'nav navbar-nav menuright'],
          'items' => [
          ['label' => 'Home', 'url' => ['/site/index']],
          ['label' => 'Tenant', 'url' => ['/users/tenant']],
          ['label' => 'Property owner', 'url' => ['/users/owner']],
          ['label' => 'Property advisor', 'url' => ['/users/advisor']],

          Yii::$app->user->isGuest ? (
          ['label' => 'Login', 'url' => ['/site/login']]
          ) : '',
          Yii::$app->user->isGuest ? (
          ['label' => 'Sign Up', 'url' => ['/users/register']]
          ) : '',
          ],
          ]); */
        ?>
        </div-->
        <?php
        //NavBar::end();
        ?>

<?= $content ?>


<?php $this->endBody() ?>
        <script src="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'js/signup.js'); ?>"></script>
        <script src="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'js/validate.js'); ?>"></script>
        <footer class="mt-5">
            <div class="container">

                <div class="footer-links" style="float: left; width: 80%;">
                    <?php
                    echo Yii::$app->userdata->getFooter();
                    ?>
                </div>
                <div class="social-media-links" style="float: right; width: 20%;">
                    <?php
                    echo Yii::$app->userdata->getSocialMediaLinks();
                    ?>
                </div>

            </div>

        </footer>
        <!--copyright-->
        <div class="copyright">© 2018 Easyleases Technologies Private Limited</div>
        <script type="text/javascript">
            window.smartlook || (function (d) {
                var o = smartlook = function () {
                    o.api.push(arguments)
                }, h = d.getElementsByTagName('head')[0];
                var c = d.createElement('script');
                o.api = new Array();
                c.async = true;
                c.type = 'text/javascript';
                c.charset = 'utf-8';
                c.src = 'https://rec.smartlook.com/recorder.js';
                h.appendChild(c);
            })(document);
            smartlook('init', '760b710efd963e8e7d1e3163baf792ac99e4f5bb');
        </script>
    </body>
</html>
<?php $this->endPage() ?>
