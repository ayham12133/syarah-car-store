<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\CarListing $model */
/** @var ActiveForm $form */

$this->title = 'Update Car: ' . $model->getFullCarName();
$this->params['breadcrumbs'][] = ['label' => 'Cars', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->getFullCarName(), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cars-update">

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
                    <?php 
                    $existingImages = $model->getImagesArray(); // Use the model method to get array
                    $imageCount = count($existingImages);
                    ?>
                    
                    <?php for ($i = 0; $i < max(1, $imageCount); $i++): ?>
                        <div class="row mb-3" id="image-upload-<?= $i + 1 ?>">
                            <div class="col-md-8">
                                <label class="form-label">Image <?= $i + 1 ?></label>
                                <?php if (!empty($existingImages[$i])): ?>
                                    <div class="mb-2">
                                        <img src="<?= Yii::getAlias('@uploadsUrl/cars/' . $existingImages[$i]) ?>" class="img-thumbnail" style="width: 100%; height: 120px; object-fit: cover;" alt="Current Image <?= $i + 1 ?>">
                                        <small class="text-muted d-block">Current Image <?= $i + 1 ?></small>
                                        <div class="mt-2">
                                            <?= Html::a('Delete Image', ['delete-image', 'id' => $model->id, 'imageIndex' => $i], [
                                                'class' => 'btn btn-danger btn-sm',
                                                'data' => [
                                                    'confirm' => 'Are you sure you want to delete this image?',
                                                    'method' => 'post',
                                                ],
                                            ]) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?= Html::fileInput('CarListing[imageFiles][]', null, [
                                    'class' => 'form-control',
                                    'accept' => 'image/*',
                                    'id' => 'image-' . ($i + 1),
                                    'onchange' => 'previewImage(this, ' . ($i + 1) . ')'
                                ]) ?>
                            </div>
                            <div class="col-md-4">
                                <div id="preview-<?= $i + 1 ?>" class="mt-4"></div>
                            </div>
                        </div>
                    <?php endfor; ?>
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
                <?= Html::a('Cancel', ['view', 'id' => $model->id], ['class' => 'btn btn-secondary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>

