<?php

use yii\helpers\Html;
use common\models\CarListing;

/** @var yii\web\View $this */
/** @var CarListing $model */

$this->title = $model->title;
// $this->params['breadcrumbs'][] = ['label' => 'Home', 'url' => ['/site/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="cars-view">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h1 class="card-title"><?= Html::encode($model->title) ?></h1>
                                <h4 class="card-subtitle mb-3 text-muted">
                                    <?= Html::encode($model->getFullCarName()) ?>
                                </h4>
                                
                                <div class="row mb-4">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <strong>Year:</strong><br>
                                            <span class="text-muted"><?= Html::encode($model->year) ?></span>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Make:</strong><br>
                                            <span class="text-muted"><?= Html::encode($model->make) ?></span>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Model:</strong><br>
                                            <span class="text-muted"><?= Html::encode($model->model) ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <strong>Mileage:</strong><br>
                                            <span class="text-muted"><?= Html::encode($model->getFormattedMileage()) ?></span>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Status:</strong><br>
                                            <?php if ($model->isAvailable()): ?>
                                                <span class="badge bg-success">Available</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Sold</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Listed:</strong><br>
                                            <span class="text-muted"><?= Yii::$app->formatter->asDate($model->created_at) ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if ($model->description): ?>
                                    <div class="mb-4">
                                        <h5>Description</h5>
                                        <p class="text-muted"><?= Html::encode($model->description) ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($model->images): ?>
                                    <div class="mb-4">
                                        <h5>Car Images</h5>
                                        <div class="row">
                                            <?php foreach ($model->getImageUrls() as $index => $image): ?>
                                                <div class="col-md-6 mb-3">
                                                    <img src="<?= $image ?>" class="img-fluid rounded" style="height: 200px; object-fit: cover; width: 100%;" alt="Car Image <?= $index + 1 ?>">
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h3 class="text-primary mb-3"><?= Html::encode($model->getFormattedPrice()) ?></h3>
                                        
                                        <?php if ($model->isAvailable()): ?>
                                            <p class="text-success mb-3">
                                                <i class="fas fa-check-circle"></i> This car is available for purchase
                                            </p>
                                            
                                            <?php if (Yii::$app->user->isGuest): ?>
                                                <p class="text-warning mb-3">
                                                    <i class="fas fa-info-circle"></i> You must be logged in to purchase
                                                </p>
                                                <?= Html::a('<i class="fas fa-sign-in-alt"></i> Login to Purchase', ['/site/login'], [
                                                    'class' => 'btn btn-success btn-lg w-100 mb-3'
                                                ]) ?>
                                            <?php else: ?>
                                                <?= Html::a('<i class="fas fa-shopping-cart"></i> Purchase Now', ['purchase', 'id' => $model->id], [
                                                    'class' => 'btn btn-success btn-lg w-100 mb-3',
                                                    'data' => [
                                                        'confirm' => 'Are you sure you want to purchase this car?',
                                                        'method' => 'post',
                                                    ]
                                                ]) ?>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <p class="text-muted mb-3">
                                                <i class="fas fa-times-circle"></i> This car has been sold
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <?= Html::a('← Back to home', ['/site/index'], ['class' => 'btn btn-outline-secondary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>

