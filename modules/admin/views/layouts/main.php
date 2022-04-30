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
    <link href="<?php echo Url::home(true); ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo Url::home(true); ?>css/modal4.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="//fontawesome.io/assets/font-awesome/css/font-awesome.css">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php $this->head() ?>
    <style>
		.adminusermanagnewbox .table > tbody > tr > td{
			height: 94px !important;
			padding: 20px !important;
		}

	</style>
  <script src="<?php echo Url::home(true); ?>js/app/jquery.min.js"></script>
</head>

<body>
<nav id="mainNav" class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i> </button>
      <a class="navbar-brand page-scroll" href="<?php echo Url::home(true); ?>"><img src="<?php echo Url::home(true); ?>images/property_logo.png" alt=""></a> 
      
      </div>
      <?php echo '<a class="pull-right">'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    '<i class="fa fa-sign-out fa-2x" aria-hidden="true"></i><div style= "color: #26344B;">Logout</div>',
                    ['class' => 'btn btn-link logout','style'=>'color:#ffffff;']
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
      <ul class="nav in" id="side-menu">
        <li> <a href="<?php echo Url::home(true); ?>admin/users"><span><i class="fa fa-users" aria-hidden="true"></i>
</span>External user management</a> </li>
        <li> <a href="<?php echo Url::home(true); ?>admin/users/admin"><span><i class="fa fa-users" aria-hidden="true"></i>
</span>internal user management</a> </li>
        <li> <a href="<?php echo Url::home(true); ?>admin/regions"><span><i class="fa fa-suitcase" aria-hidden="true"></i>
</span>Branch management</a> </li>
<li> <a href="<?php echo Url::home(true); ?>admin/regions/cities"><span><i class="fa fa-suitcase" aria-hidden="true"></i>
</span>City management</a> </li>
<li> <a href="<?php echo Url::home(true); ?>admin/regions/states"><span><i class="fa fa-suitcase" aria-hidden="true"></i>
</span>State management</a> </li>
        <!-- <li> <a href="<?php // echo Url::home(true); ?>admin/regionassignments">Region Assignment</a> </li> -->
        <li> <a href="<?php echo Url::home(true); ?>admin/roles"><span><i class="fa fa-cogs" aria-hidden="true"></i>
</span>Role management</a> </li>
        <li> <a href="<?php echo Url::home(true); ?>admin/configurations"><span><i class="fa fa-wrench" aria-hidden="true"></i>
</span>Configuration Managment</a> </li>
        <li> <a href="<?php echo Url::home(true); ?>admin/pages"><span><i class="fa fa-file-text" aria-hidden="true"></i>
</span>Content Managment</a> </li>
		<li> <a href="<?php echo Url::home(true); ?>admin/advertisements"><span><i class="fa fa-file-text" aria-hidden="true"></i>
</span>Advertisement Managment</a> </li>
		<li> <a href="#"><span><i class="fa fa-file-text" aria-hidden="true"></i>
</span>Owner Payments</a> </li>
      </ul>
    </div>
    <!-- /.sidebar-collapse -->
  </div>
</aside>
<!-- Modal signup -->
<div id="page-wrapper">
  <div class="row">
	  
      
                <?= $content ?>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
<footer>
  <div class="container">
    <div class="col-md-3 menulist">
      <ul>
        <li><a href="javascritp:;">FAQ's</a></li>
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
        <li><a href="javascritp:;">Terms &amp; Conditions</a></li>
      </ul>
    </div>
  </div>
</footer>
<div class="copyright">&copy; 2017 property management system</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modalbg modalbgpopup" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close closetext" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title modaltitletextnew">Commission Amount</h4>
      </div>
      <div class="modal-body modaltextpargh">
        <table class="table table-bordered " cellpadding="5">
        <thead>
           <tr>
            <th>Tenant Code</th>
            <th>Tenant Name</th>
            <th>Agreement from date</th>
            <th>Agreement to date</th>
            <th>Rent paid by tenant</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">lorem</th>
            <td>lorem</td>
            <td>lorem</td>
            <td>lorem</td>
            <td>lorem</td>
         
          </tr>
   
      
        </tbody>
      </table>
      </div>
      
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- jQuery -->
    

    <script src="<?php echo Url::home(true); ?>js/app/bootstrap.min.js"></script>
    <script src="<?php echo Url::home(true); ?>js/admin.js"></script>
    <script src="<?php echo Url::home(true); ?>js/validate.js"></script>

</body>
</html>
<?php $this->endPage() ?>

