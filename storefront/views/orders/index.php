<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use common\models\Order;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'My Purchased Cars';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="orders-index">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-5"><?= Html::encode($this->title) ?></h1>
                
                <!-- Results Summary -->
                <div class="mb-3">
                    <p class="text-muted">
                        You have purchased <?= $dataProvider->getTotalCount() ?> car(s)
                    </p>
                </div>

                <!-- Orders Grid -->
                <div class="row">
                    <?php if ($dataProvider->getCount() > 0): ?>
                        <?php foreach ($dataProvider->getModels() as $order): ?>
                            <div class="col-lg-6 col-md-6 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="card-title"><?= Html::encode($order->carListing->title) ?></h5>
                                            <span class="badge bg-success">Purchased</span>
                                        </div>
                                        
                                        <h6 class="card-subtitle mb-3 text-muted">
                                            <?= Html::encode($order->carListing->getFullCarName()) ?>
                                        </h6>
                                        
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <small class="text-muted">Year:</small><br>
                                                <strong><?= Html::encode($order->carListing->year) ?></strong>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Mileage:</small><br>
                                                <strong><?= Html::encode($order->carListing->getFormattedMileage()) ?></strong>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <small class="text-muted">Purchase Date:</small><br>
                                                <strong><?= Html::encode($order->getFormattedPurchaseDate()) ?></strong>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Purchase Price:</small><br>
                                                <strong class="text-success"><?= Html::encode($order->getFormattedPrice()) ?></strong>
                                            </div>
                                        </div>
                                        
                                        <?php if ($order->notes): ?>
                                            <div class="mb-3">
                                                <small class="text-muted">Notes:</small><br>
                                                <p class="card-text small"><?= Html::encode($order->notes) ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="card-footer bg-transparent">
                                        <div class="d-flex gap-2">
                                            <?= Html::a('View Details', ['view', 'id' => $order->id], [
                                                'class' => 'btn btn-primary btn-sm flex-fill'
                                            ]) ?>
                                            <?= Html::a('View Car', ['/cars/view', 'id' => $order->carListing->id], [
                                                'class' => 'btn btn-outline-secondary btn-sm flex-fill'
                                            ]) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <h4 class="text-muted">No purchased cars found</h4>
                                <p class="text-muted">You haven't purchased any cars yet.</p>
                                <?= Html::a('Browse Cars', ['/site/index'], ['class' => 'btn btn-primary']) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if ($dataProvider->getPagination()->pageCount > 1): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <?= LinkPager::widget([
                            'pagination' => $dataProvider->getPagination(),
                            'options' => ['class' => 'pagination'],
                            'linkOptions' => ['class' => 'page-link'],
                            'pageCssClass' => 'page-item',
                            'activePageCssClass' => 'active',
                            'disabledPageCssClass' => 'disabled',
                        ]) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
