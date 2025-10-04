<?php

use yii\helpers\Html;
use common\models\Order;

/** @var yii\web\View $this */
/** @var Order $model */

$this->title = 'Order Details - ' . $model->carListing->title;
$this->params['breadcrumbs'][] = ['label' => 'My Purchased Cars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="orders-view">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h1 class="card-title">Order #<?= Html::encode($model->id) ?></h1>
                                <h4 class="card-subtitle mb-3 text-muted">
                                    <?= Html::encode($model->carListing->title) ?>
                                </h4>
                                
                                <div class="row mb-4">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <strong>Car:</strong><br>
                                            <span class="text-muted"><?= Html::encode($model->carListing->getFullCarName()) ?></span>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Year:</strong><br>
                                            <span class="text-muted"><?= Html::encode($model->carListing->year) ?></span>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Mileage:</strong><br>
                                            <span class="text-muted"><?= Html::encode($model->carListing->getFormattedMileage()) ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <strong>Purchase Date:</strong><br>
                                            <span class="text-muted"><?= Html::encode($model->getFormattedPurchaseDate()) ?></span>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Order Status:</strong><br>
                                            <span class="badge bg-success">Completed</span>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Order ID:</strong><br>
                                            <span class="text-muted">#<?= Html::encode($model->id) ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if ($model->carListing->description): ?>
                                    <div class="mb-4">
                                        <h5>Car Description</h5>
                                        <p class="text-muted"><?= Html::encode($model->carListing->description) ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($model->notes): ?>
                                    <div class="mb-4">
                                        <h5>Order Notes</h5>
                                        <p class="text-muted"><?= Html::encode($model->notes) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h3 class="text-success mb-3"><?= Html::encode($model->getFormattedPrice()) ?></h3>
                                        
                                        <p class="text-success mb-3">
                                            <i class="fas fa-check-circle"></i> Purchase Completed
                                        </p>
                                        
                                        <div class="mb-3">
                                            <small class="text-muted">Purchased on</small><br>
                                            <strong><?= Html::encode($model->getFormattedPurchaseDate()) ?></strong>
                                        </div>
                                        
                                        <hr>
                                        
                                        <div class="d-grid gap-2">
                                            <?= Html::a('View Car Details', ['/cars/view', 'id' => $model->carListing->id], [
                                                'class' => 'btn btn-primary'
                                            ]) ?>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <?= Html::a('← Back to My Cars', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>

