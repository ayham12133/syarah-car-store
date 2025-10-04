<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\CarListing $model */
/** @var ActiveForm $form */

$this->title = 'Create Car Listing';
$this->params['breadcrumbs'][] = ['label' => 'Cars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cars-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-8">
            <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data']
            ]); ?>

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'make')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'year')->textInput(['type' => 'number', 'min' => 1900, 'max' => date('Y') + 1]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'price')->textInput(['type' => 'number', 'step' => '0.01', 'min' => 0]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'mileage')->textInput(['type' => 'number', 'min' => 0]) ?>
                </div>
            </div>

            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'status')->dropDownList(\common\models\CarListing::getStatusOptions()) ?>

            <div class="form-group">
                <label class="form-label">Car Images (Maximum 3 images)</label>
                
                <div id="image-uploads-container">
                    <div class="row mb-3" id="image-upload-1">
                        <div class="col-md-8">
                            <label class="form-label">Image 1</label>
                            <?= Html::fileInput('CarListing[imageFiles][]', null, [
                                'class' => 'form-control',
                                'accept' => 'image/*',
                                'id' => 'image-1',
                                'onchange' => 'previewImage(this, 1)'
                            ]) ?>
                        </div>
                        <div class="col-md-4">
                            <div id="preview-1" class="mt-4"></div>
                        </div>
                    </div>
                </div>
                
                <div id="add-image-btn-container" class="mb-3" style="display: none;">
                    <button type="button" class="btn btn-outline-primary" onclick="addImageUpload()">
                        <i class="fas fa-plus"></i> Add Another Image
                    </button>
                </div>
                
                <div class="form-text">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        You can upload up to 3 images. Supported formats: JPG, PNG, GIF. Max size: 5MB per image.
                    </small>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-secondary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>

