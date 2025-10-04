<?php

/** @var yii\web\View $this */
/** @var common\models\CarListingSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Car Store - Quality Cars for Sale';
?>

<div class="site-index">
    <!-- Hero Section -->
    <div class="p-5 mb-4 bg-primary text-white rounded-3">
        <div class="container-fluid py-5 text-center">
            <h1 class="display-4">Welcome to Car Store!</h1>
            <p class="fs-5 fw-light">Your premium destination for quality cars.</p>
        </div>
    </div>

    <!-- Search Form -->
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Search Cars</h5>
                        <?= Html::beginForm(['/site/index'], 'get', ['class' => 'row g-3']) ?>
                            <div class="col-md-2">
                                <?= Html::textInput('CarListingSearch[make]', $searchModel->make, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Make (e.g., Toyota)...'
                                ]) ?>
                            </div>
                            <div class="col-md-2">
                                <?= Html::textInput('CarListingSearch[model]', $searchModel->model, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Model (e.g., Camry)...'
                                ]) ?>
                            </div>
                            <div class="col-md-1">
                                <?= Html::textInput('CarListingSearch[minYear]', $searchModel->minYear, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Min Year',
                                    'type' => 'number',
                                    'min' => '1900',
                                    'max' => date('Y') + 1
                                ]) ?>
                            </div>
                            <div class="col-md-1">
                                <?= Html::textInput('CarListingSearch[maxYear]', $searchModel->maxYear, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Max Year',
                                    'type' => 'number',
                                    'min' => '1900',
                                    'max' => date('Y') + 1
                                ]) ?>
                            </div>
                            <div class="col-md-2">
                                <?= Html::textInput('CarListingSearch[minPrice]', $searchModel->minPrice, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Min Price ($)',
                                    'type' => 'number',
                                    'min' => '0',
                                    'step' => '100'
                                ]) ?>
                            </div>
                            <div class="col-md-2">
                                <?= Html::textInput('CarListingSearch[maxPrice]', $searchModel->maxPrice, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Max Price ($)',
                                    'type' => 'number',
                                    'min' => '0',
                                    'step' => '100'
                                ]) ?>
                            </div>
                            <div class="col-md-1">
                                <?= Html::submitButton('Search', ['class' => 'btn btn-primary w-100']) ?>
                            </div>
                            <div class="col-md-1">
                                <?= Html::a('Clear', ['/site/index'], ['class' => 'btn btn-secondary w-100']) ?>
                            </div>
                        <?= Html::endForm() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cars Listing -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Available Cars</h2>
                
                <?php if ($dataProvider->getCount() > 0): ?>
                    <div class="row">
                        <?php foreach ($dataProvider->getModels() as $car): ?>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100">
                                    <?php if ($car->images): ?>
                                        <?php $images = $car->getImagesArray(); ?>
                                        <?php if (!empty($images[0])): ?>
                                            <img src="<?= Yii::getAlias('@uploadsUrl/cars/' . $images[0]) ?>" 
                                                 class="card-img-top" 
                                                 style="height: 200px; object-fit: cover;" 
                                                 alt="<?= Html::encode($car->title) ?>">
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <i class="fas fa-car fa-3x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title"><?= Html::encode($car->title) ?></h5>
                                        <p class="card-text">
                                            <strong><?= Html::encode($car->make) ?> <?= Html::encode($car->model) ?></strong><br>
                                            Year: <?= Html::encode($car->year) ?><br>
                                            Mileage: <?= number_format($car->mileage) ?> miles<br>
                                            <span class="text-success fs-5 fw-bold">$<?= number_format($car->price) ?></span>
                                        </p>
                                        <div class="mt-auto">
                                            <?= Html::a('View Details', ['/cars/view', 'id' => $car->id], [
                                                'class' => 'btn btn-primary w-100'
                                            ]) ?>
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer">
                                        <small class="text-muted">
                                            Status: 
                                            <span class="badge <?= $car->isAvailable() ? 'bg-success' : 'bg-danger' ?>">
                                                <?= $car->isAvailable() ? 'Available' : 'Sold' ?>
                                            </span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        <?= LinkPager::widget([
                            'pagination' => $dataProvider->pagination,
                            'options' => ['class' => 'pagination'],
                            'linkOptions' => ['class' => 'page-link'],
                            'activePageCssClass' => 'active',
                            'disabledPageCssClass' => 'disabled',
                        ]) ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-car fa-5x text-muted mb-3"></i>
                        <h3 class="text-muted">No cars found</h3>
                        <p class="text-muted">Try adjusting your search criteria or check back later for new listings.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
