<?php

use yii\helpers\Html;
use common\models\Export;

/** @var yii\web\View $this */
/** @var common\models\Export $model */

$this->title = 'Export Details';
$this->params['breadcrumbs'][] = ['label' => 'Export Management', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="export-view">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <div>
            <?php if ($model->isCompleted()): ?>
                <?= Html::a('Download File', ['download', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
            <?php endif; ?>
            <?= Html::a('Back to List', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Export Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Export ID:</strong></td>
                            <td><?= Html::encode($model->id) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Filename:</strong></td>
                            <td><?= Html::encode($model->filename) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge <?= $model->getStatusBadgeClass() ?>">
                                    <?= $model->getStatusLabel() ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Total Records:</strong></td>
                            <td><?= $model->total_records > 0 ? number_format($model->total_records) : '-' ?></td>
                        </tr>
                        <tr>
                            <td><strong>Created By:</strong></td>
                            <td><?= Html::encode($model->createdBy->username) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Created At:</strong></td>
                            <td><?= $model->getFormattedCreationDate() ?></td>
                        </tr>
                        <tr>
                            <td><strong>Completed At:</strong></td>
                            <td><?= $model->getFormattedCompletionDate() ?></td>
                        </tr>
                        <?php if ($model->isCompleted()): ?>
                        <tr>
                            <td><strong>File Size:</strong></td>
                            <td><?= $model->getFormattedFileSize() ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Export Filters</h5>
                </div>
                <div class="card-body">
                    <?php $filters = $model->getFiltersArray(); ?>
                    <?php if (empty($filters) || array_filter($filters) === []): ?>
                        <p class="text-muted">No filters applied - all car listings included</p>
                    <?php else: ?>
                        <ul class="list-unstyled">
                            <?php foreach ($filters as $key => $value): ?>
                                <?php if (!empty($value)): ?>
                                    <li><strong><?= ucfirst($key) ?>:</strong> <?= Html::encode($value) ?></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($model->isFailed() && $model->error_message): ?>
            <div class="card mt-3">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Error Details</h5>
                </div>
                <div class="card-body">
                    <p class="text-danger"><?= Html::encode($model->error_message) ?></p>
                </div>
            </div>
            <?php endif; ?>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <?php if ($model->isCompleted()): ?>
                        <?= Html::a('Download CSV File', ['download', 'id' => $model->id], [
                            'class' => 'btn btn-success btn-block mb-2'
                        ]) ?>
                    <?php elseif ($model->isFailed()): ?>
                        <?= Html::a('Retry Export', ['create'], [
                            'class' => 'btn btn-warning btn-block mb-2'
                        ]) ?>
                    <?php endif; ?>
                    
                    <?= Html::a('Delete Export', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger btn-block',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this export? This will also delete the file.',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Auto-refresh for pending/processing exports
if ($model->isPending() || $model->isProcessing()) {
    $this->registerJs("
        // Auto-refresh every 3 seconds for pending/processing exports
        setTimeout(function() {
            location.reload();
        }, 3000);
    ");
}
?>
