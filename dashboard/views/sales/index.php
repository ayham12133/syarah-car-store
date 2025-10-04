<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Order;

/** @var yii\web\View $this */
/** @var float $totalSales */
/** @var int $totalOrders */
/** @var int $totalCarsSold */
/** @var int $totalCarsAvailable */
/** @var array $recentOrders */
/** @var array $popularModels */
/** @var array $salesByMonth */
/** @var array $topBuyers */

$this->title = 'Sales Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="sales-index">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <?= Html::a('Detailed Report', ['report'], ['class' => 'btn btn-primary']) ?>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">Total Sales</h4>
                            <h2>$<?= number_format($totalSales, 2) ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">Total Orders</h4>
                            <h2><?= number_format($totalOrders) ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">Cars Sold</h4>
                            <h2><?= number_format($totalCarsSold) ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-car fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">Available Cars</h4>
                            <h2><?= number_format($totalCarsAvailable) ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-car-side fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Orders -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Orders</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($recentOrders)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Car</th>
                                        <th>Buyer</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentOrders as $order): ?>
                                        <tr>
                                            <td>#<?= $order->id ?></td>
                                            <td><?= Html::encode($order->carListing->title) ?></td>
                                            <td><?= Html::encode($order->user->username) ?></td>
                                            <td>$<?= number_format($order->purchase_price, 2) ?></td>
                                            <td><?= date('M j, Y', strtotime($order->purchase_date)) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No recent orders found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Popular Models -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Most Popular Models</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($popularModels)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Make & Model</th>
                                        <th>Sales</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($popularModels as $model): ?>
                                        <tr>
                                            <td><?= Html::encode($model['make'] . ' ' . $model['model']) ?></td>
                                            <td><?= $model['sales_count'] ?></td>
                                            <td>$<?= number_format($model['total_revenue'], 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No sales data available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sales by Month -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Sales by Month (Last 12 Months)</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($salesByMonth)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Orders</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($salesByMonth as $month): ?>
                                        <tr>
                                            <td><?= date('M Y', strtotime($month['month'] . '-01')) ?></td>
                                            <td><?= $month['orders_count'] ?></td>
                                            <td>$<?= number_format($month['total_revenue'], 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No sales data available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Top Buyers -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top Buyers</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($topBuyers)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Purchases</th>
                                        <th>Total Spent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($topBuyers as $buyer): ?>
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong><?= Html::encode($buyer['username']) ?></strong><br>
                                                    <small class="text-muted"><?= Html::encode($buyer['email']) ?></small>
                                                </div>
                                            </td>
                                            <td><?= $buyer['purchase_count'] ?></td>
                                            <td>$<?= number_format($buyer['total_spent'], 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No buyer data available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
