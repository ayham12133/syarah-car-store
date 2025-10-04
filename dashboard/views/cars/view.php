<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\CarListing $model */

$this->title = $model->getFullCarName();
$this->params['breadcrumbs'][] = ['label' => 'Cars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cars-view">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this car listing?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'make',
            'model',
            'year',
            [
                'attribute' => 'price',
                'value' => $model->getFormattedPrice(),
            ],
            [
                'attribute' => 'mileage',
                'value' => $model->getFormattedMileage(),
            ],
            'description:ntext',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    $class = $model->isAvailable() ? 'badge bg-success' : 'badge bg-secondary';
                    return '<span class="' . $class . '">' . $model->getStatusLabel() . '</span>';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'created_at',
                'value' => Yii::$app->formatter->asDatetime($model->created_at),
            ],
            [
                'attribute' => 'updated_at',
                'value' => Yii::$app->formatter->asDatetime($model->updated_at),
            ],
        ],
    ]) ?>

    <?php if ($model->images): ?>
        <div class="mt-4">
            <h4>Car Images</h4>
            <div class="row">
                <?php foreach ($model->getImageUrls() as $index => $image): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <img src="<?= $image ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Car Image <?= $index + 1 ?>">
                            <div class="card-body p-2">
                                <small class="text-muted">Image <?= $index + 1 ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

</div>
