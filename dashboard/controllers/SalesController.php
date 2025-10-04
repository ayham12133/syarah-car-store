<?php

namespace dashboard\controllers;

use common\models\CarListing;
use common\models\Order;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * SalesController handles sales dashboard for admins
 */
class SalesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            /** @var \common\models\User $identity */
                            $identity = Yii::$app->user->identity;
                            return !Yii::$app->user->isGuest && $identity instanceof \common\models\User && $identity->isAdmin();
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays sales dashboard
     */
    public function actionIndex()
    {
        // Get sales statistics
        $totalSales = Order::find()->sum('purchase_price') ?: 0;
        $totalOrders = $totalCarsSold = Order::find()->count();
        $totalCarsAvailable = CarListing::find()->where(['status' => CarListing::STATUS_AVAILABLE])->count();
        
        // Get recent orders
        $recentOrders = Order::find()
            ->with(['carListing', 'user'])
            ->orderBy(['purchase_date' => SORT_DESC])
            ->limit(10)
            ->all();

        // Get most popular car models
        $popularModels = Order::find()
            ->select([
                'car_listing.make',
                'car_listing.model',
                'COUNT(*) as sales_count',
                'SUM(purchase_price) as total_revenue'
            ])
            ->joinWith('carListing')
            ->groupBy(['car_listing.make', 'car_listing.model'])
            ->orderBy(['sales_count' => SORT_DESC])
            ->limit(10)
            ->asArray()
            ->all();

        // Get sales by month (last 12 months)
        $salesByMonth = Order::find()
            ->select([
                'DATE_FORMAT(purchase_date, "%Y-%m") as month',
                'COUNT(*) as orders_count',
                'SUM(purchase_price) as total_revenue'
            ])
            ->where(['>=', 'purchase_date', date('Y-m-d', strtotime('-12 months'))])
            ->groupBy('month')
            ->orderBy(['month' => SORT_ASC])
            ->asArray()
            ->all();

        // Get top buyers
        $topBuyers = Order::find()
            ->select([
                'user.username',
                'user.email',
                'COUNT(*) as purchase_count',
                'SUM(purchase_price) as total_spent'
            ])
            ->joinWith('user')
            ->groupBy('user.id')
            ->orderBy(['total_spent' => SORT_DESC])
            ->limit(10)
            ->asArray()
            ->all();

        return $this->render('index', [
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrders,
            'totalCarsSold' => $totalCarsSold,
            'totalCarsAvailable' => $totalCarsAvailable,
            'recentOrders' => $recentOrders,
            'popularModels' => $popularModels,
            'salesByMonth' => $salesByMonth,
            'topBuyers' => $topBuyers,
        ]);
    }

    /**
     * Displays detailed sales report
     */
    public function actionReport()
    {
        $query = Order::find()->with(['carListing', 'user']);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'purchase_date' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('report', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
