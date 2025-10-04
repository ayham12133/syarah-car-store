<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\CarListing;

/** @var yii\web\View $this */
/** @var common\models\CarListingSearch $searchModel */

$this->title = 'Create Export';
$this->params['breadcrumbs'][] = ['label' => 'Export Management', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="export-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Export Filters</h5>
            <p class="text-muted mb-0">Configure filters to determine which car listings to include in the export. Leave fields empty to export all listings.</p>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'id' => 'export-form',
                'options' => ['class' => 'needs-validation', 'novalidate' => true]
            ]); ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($searchModel, 'title')->textInput(['placeholder' => 'Car title (e.g., "2019 Toyota Camry")']) ?>
                </div>
                
                <div class="col-md-6">
                    <?= $form->field($searchModel, 'make')->textInput(['placeholder' => 'Make (e.g., Toyota, Honda)']) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($searchModel, 'model')->textInput(['placeholder' => 'Model (e.g., Camry, Accord)']) ?>
                </div>
                
                <div class="col-md-6">
                    <?= $form->field($searchModel, 'year')->textInput(['type' => 'number', 'placeholder' => 'Year (e.g., 2020)']) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($searchModel, 'status')->dropDownList([
                        '' => 'All Status',
                        CarListing::STATUS_AVAILABLE => 'Available',
                        CarListing::STATUS_SOLD => 'Sold'
                    ], ['class' => 'form-select']) ?>
                </div>
                
                
            </div>

            

            <div class="form-group">
                <?= Html::submitButton('Create Export', [
                    'class' => 'btn btn-primary btn-lg',
                    'id' => 'create-export-btn'
                ]) ?>
                <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-secondary btn-lg']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$this->registerJs("
    $('#export-form').on('submit', function(e) {
        var btn = $('#create-export-btn');
        btn.prop('disabled', true).html('<i class=\"fas fa-spinner fa-spin\"></i> Creating Export...');
        
        // Re-enable button after 5 seconds in case of errors
        setTimeout(function() {
            btn.prop('disabled', false).html('Create Export');
        }, 5000);
    });
");
?>
