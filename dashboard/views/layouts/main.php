<?php

/** @var \yii\web\View $this */
/** @var string $content */

use dashboard\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
?>
<?php $this->beginPage() ?>
<?php AppAsset::register($this); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: #343a40;
            color: white;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid #495057;
            background-color: #212529;
        }
        
        .sidebar-header h4 {
            margin: 0;
            color: white;
        }
        
        .sidebar-menu {
            padding: 0;
            margin: 0;
            list-style: none;
        }
        
        .sidebar-menu li {
            border-bottom: 1px solid #495057;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 1rem;
            color: #adb5bd;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover {
            background-color: #495057;
            color: white;
        }
        
        .sidebar-menu .active > a {
            background-color: #007bff;
            color: white;
        }
        
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        
        .top-bar {
            background-color: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .content-area {
            padding: 2rem;
        }
        
        .user-info {
            float: right;
        }
        
        .user-info .btn {
            margin-left: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-menu-btn {
                display: block;
            }
        }
        
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #495057;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php $this->beginBody() ?>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h4><?= Html::encode(Yii::$app->name) ?></h4>
    </div>
    
        <ul class="sidebar-menu">
            <li class="<?= $this->context->id == 'site' ? 'active' : '' ?>">
                <?= Html::a('<i class="fas fa-home"></i> Dashboard', ['/site/index']) ?>
            </li>
            <li class="<?= $this->context->id == 'cars' ? 'active' : '' ?>">
                <?= Html::a('<i class="fas fa-car"></i> Cars', ['/cars/index']) ?>
            </li>
            <li class="<?= $this->context->id == 'users' ? 'active' : '' ?>">
                <?= Html::a('<i class="fas fa-users"></i> Users', ['/users/index']) ?>
            </li>
            <li class="<?= $this->context->id == 'sales' ? 'active' : '' ?>">
                <?= Html::a('<i class="fas fa-chart-line"></i> Sales', ['/sales/index']) ?>
            </li>
            <li class="<?= $this->context->id == 'export' ? 'active' : '' ?>">
                <?= Html::a('<i class="fas fa-download"></i> Export', ['/export/index']) ?>
            </li>
        </ul>
</div>

<div class="main-content">
    <div class="top-bar">
        <button class="mobile-menu-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        
        <div class="user-info">
            <?php if (Yii::$app->user->isGuest): ?>
                <?= Html::a('Login', ['/site/login'], ['class' => 'btn btn-primary btn-sm']) ?>
            <?php else: ?>
                <span class="text-muted">Welcome, <?= Html::encode(Yii::$app->user->identity->username) ?></span>
                <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'd-inline'])
                    . Html::submitButton('Logout', ['class' => 'btn btn-outline-secondary btn-sm'])
                    . Html::endForm() ?>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="content-area">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
}
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
