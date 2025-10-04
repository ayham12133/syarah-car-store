<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Order;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Detailed Sales Report';
$this->params['breadcrumbs'][] = ['label' => 'Sales Dashboard', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="sales-report">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <?= Html::a('← Back to Dashboard', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-hover'],
        'columns' => [
            [
                'attribute' => 'id',
                'label' => 'Order ID',
                'options' => ['style' => 'width: 80px;'],
            ],
            [
                'attribute' => 'carListing.title',
                'label' => 'Car',
                'value' => function ($model) {
                    return Html::encode($model->carListing->title);
                },
            ],
            [
                'attribute' => 'carListing.make',
                'label' => 'Make',
                'value' => function ($model) {
                    return Html::encode($model->carListing->make);
                },
            ],
            [
                'attribute' => 'carListing.model',
                'label' => 'Model',
                'value' => function ($model) {
                    return Html::encode($model->carListing->model);
                },
            ],
            [
                'attribute' => 'carListing.year',
                'label' => 'Year',
                'value' => function ($model) {
                    return Html::encode($model->carListing->year);
                },
                'options' => ['style' => 'width: 80px;'],
            ],
            [
                'attribute' => 'user.username',
                'label' => 'Buyer',
                'value' => function ($model) {
                    return Html::encode($model->user->username);
                },
            ],
            [
                'attribute' => 'purchase_price',
                'label' => 'Sale Price',
                'value' => function ($model) {
                    return '$' . number_format($model->purchase_price, 2);
                },
                'options' => ['style' => 'width: 120px;'],
            ],
            [
                'attribute' => 'purchase_date',
                'label' => 'Sale Date',
                'value' => function ($model) {
                    return date('M j, Y', strtotime($model->purchase_date));
                },
                'options' => ['style' => 'width: 120px;'],
            ],
            [
                'attribute' => 'notes',
                'label' => 'Notes',
                'value' => function ($model) {
                    return Html::encode($model->notes ?: 'N/A');
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-eye"></i>', ['/cars/view', 'id' => $model->carListing->id], [
                            'title' => 'View Car',
                            'class' => 'btn btn-sm btn-outline-primary',
                            'target' => '_blank',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
