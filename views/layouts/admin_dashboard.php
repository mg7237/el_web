<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAssetLogin;
use yii\helpers\Url;

AppAssetLogin::register($this);
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
        <?php $this->head() ?>
        <link href="<?php echo Url::home(true); ?>css/adviser.css" rel="stylesheet"></head>
    <link href="<?php echo Url::home(true); ?>css/all.css" rel="stylesheet"></head>
<link rel="stylesheet" type="text/css" href="http://fontawesome.io/assets/font-awesome/css/font-awesome.css">
<script src="<?php echo Url::home(true); ?>js/app/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
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
//  echo '<a>'
// . Html::beginForm(['/site/logout'], 'post')
// . Html::submitButton(
//     '<img src="'.Url::home(true).'images/logout_but.png" alt="">',
//     ['class' => 'btn btn-link logout','style'=>'color:#000;']
// )
// . Html::endForm()
// . '</a>'
                ?>
                <?php
                echo Nav::widget([
                    'options' => ['class' => 'dropdown-menu userdropdown'],
                    'items' => [
                        ['label' => 'My Dashboard', 'url' => ['/admin/users'], 'visible' => Yii::$app->userdata->usertype == 1],
                        Yii::$app->user->isGuest ? (
                                ['label' => 'Login', 'url' => ['/site/login']]
                                ) : (
                                '<li>'
                                . Html::beginForm(['/site/logout'], 'post')
                                . Html::submitButton(
                                        'Logout', ['class' => 'btn btn-link logout', 'style' => 'color:#000;']
                                )
                                . Html::endForm()
                                . '</li>'
                                )
                    ],
                ]);
                ?>
            </div>
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
                        //~ ['label' => 'Dashboard', 'url' => ['/advisers'],'visible' => Yii::$app->userdata->usertype == 5],

                        ['label' => 'External User Management', 'url' => ['/admin/externalUsers'], 'visible' => Yii::$app->userdata->usertype == 1],
                    // ['label' => 'Refer property owner', 'url' => ['/advisers/owner'],'visible' => Yii::$app->userdata->usertype == 5],
                    // ['label' => 'Refer Tenant', 'url' => ['/advisers/applicant'],'visible' => Yii::$app->userdata->usertype == 5],
                    // ['label' => 'Property Owner and Tenant Status', 'url' => ['/advisers/myreferred'],'visible' => Yii::$app->userdata->usertype == 5],
                    // ['label' => 'Property Owner Details', 'url' => ['/advisers/ownerreferred'],'visible' => Yii::$app->userdata->usertype == 5],
                    // ['label' => 'Tenant Details', 'url' => ['/advisers/tenantreferred'],'visible' => Yii::$app->userdata->usertype == 5],
                    // ['label' => 'Fees Details', 'url' => ['/advisers/tenantreferred'],'visible' => Yii::$app->userdata->usertype == 5],
                    //~ ['label' => 'Change Password', 'url' => ['/users/advisorpassword'],'visible' => Yii::$app->userdata->usertype == 5],
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
    <script src="<?php echo Url::home(true); ?>js/app/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="<?php echo Url::home(true); ?>js/adviser.js"></script>
    <script src="<?php echo Url::home(true); ?>js/validate.js"></script>


</body>
</html>
<?php $this->endPage() ?>
