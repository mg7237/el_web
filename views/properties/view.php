<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Properties */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Properties', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="properties-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'property_code',
            'rent',
            'deposit',
            'token_amount',
            'maintenance',
            'maintenance_included',
            'status',
            'no_of_rooms',
            'is_room',
            'owner_id',
            'property_type',
            'flat_bhk',
            'flat_area',
            'flat_type',
            'availability_from',
            'property_description:ntext',
            'created_by',
            'updated_by',
            'created_time',
            'update_time',
            'no_of_beds',
        ],
    ])
    ?>

</div>
