<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Export;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Export Management';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="export-index">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <?= Html::a('Create New Export', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-hover'],
        'columns' => [
            [
                'attribute' => 'id',
                'label' => 'Export ID',
                'options' => ['style' => 'width: 80px;'],
            ],
            [
                'attribute' => 'filename',
                'label' => 'Filename',
                'value' => function ($model) {
                    return Html::encode($model->filename);
                },
            ],
            [
                'attribute' => 'status',
                'label' => 'Status',
                'value' => function ($model) {
                    $class = $model->getStatusBadgeClass();
                    $label = $model->getStatusLabel();
                    return '<span class="badge ' . $class . '">' . $label . '</span>';
                },
                'format' => 'raw',
                'options' => ['style' => 'width: 120px;'],
            ],
            [
                'attribute' => 'total_records',
                'label' => 'Records',
                'value' => function ($model) {
                    return $model->total_records > 0 ? number_format($model->total_records) : '-';
                },
                'options' => ['style' => 'width: 100px;'],
            ],
            [
                'attribute' => 'createdBy.username',
                'label' => 'Created By',
                'value' => function ($model) {
                    return Html::encode($model->createdBy->username);
                },
            ],
            [
                'attribute' => 'created_at',
                'label' => 'Created',
                'value' => function ($model) {
                    return $model->getFormattedCreationDate();
                },
                'options' => ['style' => 'width: 150px;'],
            ],
            [
                'attribute' => 'completed_at',
                'label' => 'Completed',
                'value' => function ($model) {
                    return $model->getFormattedCompletionDate();
                },
                'options' => ['style' => 'width: 150px;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'template' => '{view} {download} {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-eye"></i>', $url, [
                            'title' => 'View Details',
                            'class' => 'btn btn-sm btn-outline-primary',
                        ]);
                    },
                    'download' => function ($url, $model, $key) {
                        if (!$model->isCompleted()) {
                            return '';
                        }
                        return Html::a('<i class="fas fa-download"></i>', $url, [
                            'title' => 'Download',
                            'class' => 'btn btn-sm btn-outline-success',
                        ]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-trash"></i>', $url, [
                            'title' => 'Delete',
                            'class' => 'btn btn-sm btn-outline-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this export? This will also delete the file.',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>

<?php
// Auto-refresh for pending/processing exports
$pendingExports = Export::find()
    ->where(['status' => [Export::STATUS_PENDING, Export::STATUS_PROCESSING]])
    ->count();

if ($pendingExports > 0) {
    $this->registerJs("
        // Auto-refresh every 5 seconds for pending/processing exports
        setTimeout(function() {
            location.reload();
        }, 5000);
    ");
}
?>
