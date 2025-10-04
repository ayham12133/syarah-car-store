<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CarListing;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var common\models\CarListingSearch $searchModel */

?>

<div id="cars-grid-container">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-hover'],
        'layout' => '{items}{pager}{summary}',
        'pager' => [
            'class' => 'yii\bootstrap5\LinkPager',
            'options' => ['class' => 'pagination justify-content-center'],
        ],
        'columns' => [
            [
                'attribute' => 'id',
                'options' => ['style' => 'width: 60px;'],
            ],
            [
                'attribute' => 'title',
                'enableSorting' => true,
            ],
            [
                'attribute' => 'make',
                'enableSorting' => true,
            ],
            [
                'attribute' => 'model',
                'enableSorting' => true,
            ],
            [
                'attribute' => 'year',
                'enableSorting' => true,
                'options' => ['style' => 'width: 80px;'],
            ],
            [
                'attribute' => 'price',
                'enableSorting' => true,
                'value' => function ($model) {
                    return $model->getFormattedPrice();
                },
                'options' => ['style' => 'width: 120px;'],
            ],
            [
                'attribute' => 'mileage',
                'enableSorting' => true,
                'value' => function ($model) {
                    return $model->getFormattedMileage();
                },
                'options' => ['style' => 'width: 120px;'],
            ],
            [
                'attribute' => 'status',
                'enableSorting' => true,
                'value' => function ($model) {
                    $class = $model->isAvailable() ? 'badge bg-success' : 'badge bg-secondary';
                    return '<span class="' . $class . '">' . $model->getStatusLabel() . '</span>';
                },
                'format' => 'raw',
                'options' => ['style' => 'width: 100px;'],
            ],
            [
                'attribute' => 'created_at',
                'enableSorting' => true,
                'value' => function ($model) {
                    return Yii::$app->formatter->asDatetime($model->created_at);
                },
                'options' => ['style' => 'width: 150px;'],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-eye"></i>', $url, [
                            'title' => 'View',
                            'class' => 'btn btn-sm btn-outline-primary',
                        ]);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-edit"></i>', $url, [
                            'title' => 'Update',
                            'class' => 'btn btn-sm btn-outline-warning',
                        ]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-trash"></i>', $url, [
                            'title' => 'Delete',
                            'class' => 'btn btn-sm btn-outline-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this car listing?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
