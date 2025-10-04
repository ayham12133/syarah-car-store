<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\models\CarListing;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var common\models\CarListing $searchModel */

$this->title = 'Car Listings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cars-index">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <?= Html::a('Add New Car', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Filter & Search</h5>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'id' => 'filter-form',
                'action' => ['index'],
                'method' => 'get',
                'options' => ['class' => 'row g-3']
            ]); ?>
            
            <div class="col-md-3">
                <?= $form->field($searchModel, 'make')->textInput(['placeholder' => 'Make (e.g., Toyota)'])->label(false) ?>
            </div>
            
            <div class="col-md-3">
                <?= $form->field($searchModel, 'model')->textInput(['placeholder' => 'Model (e.g., Camry)'])->label(false) ?>
            </div>
            
            <div class="col-md-2">
                <?= $form->field($searchModel, 'year')->textInput(['type' => 'number', 'placeholder' => 'Year'])->label(false) ?>
            </div>
            
            <div class="col-md-2">
                <?= $form->field($searchModel, 'status')->dropDownList([
                    '' => 'All Status',
                    CarListing::STATUS_AVAILABLE => 'Available',
                    CarListing::STATUS_SOLD => 'Sold'
                ], ['class' => 'form-select'])->label(false) ?>
            </div>
            
            <div class="col-md-2">
                <div class="d-flex gap-2">
                    <?= Html::button('Clear', ['id' => 'clear-filter', 'class' => 'btn btn-outline-secondary']) ?>
                </div>
            </div>
            
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    

    <div id="cars-grid-container">
        <!-- Grid will be loaded via AJAX -->
    </div>

    <!-- Loading indicator -->
    <div id="loading-indicator" class="text-center mb-3" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <span class="ms-2">Loading...</span>
    </div>

</div>

