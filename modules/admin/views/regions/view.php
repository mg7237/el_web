<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Roles */

$this->title = $model->role_name;
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
     <div class="row">
                    <div class="col-lg-12">
									

								<h3><?= Html::encode($this->title) ?></h3>

    <p>
		<?= Html::a('Create', ['create'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'role_name',
        ],
    ]) ?>






                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->



