<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Roles */

$this->title = 'Create Roles';
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
         <div class="row">
                    <div class="col-lg-12">
													

								<h3><?= Html::encode($this->title) ?></h3>

							

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
												   
												   
								






                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->



