<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-view">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php if (!$model->isAdmin()): ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this user?',
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            [
                'attribute' => 'status',
                'value' => $model->status == 10 ? 'Active' : 'Inactive',
            ],
            [
                'attribute' => 'is_admin',
                'label' => 'Role',
                'value' => function ($model) {
                    $class = $model->isAdmin() ? 'badge bg-danger' : 'badge bg-primary';
                    $text = $model->isAdmin() ? 'Admin' : 'User';
                    return '<span class="' . $class . '">' . $text . '</span>';
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

</div>
