<?php

namespace storefront\controllers;

use common\models\Order;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * OrdersController handles order display for buyers
 */
class OrdersController extends Controller
{
    /**
     * Displays user's purchased cars
     */
    public function actionIndex()
    {
        // Check if user is logged in
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'You must be logged in to view your purchased cars.');
            return $this->redirect(['/site/login']);
        }

        // Get the logged-in user ID
        $userId = Yii::$app->user->id;
        
        $dataProvider = new ActiveDataProvider([
            'query' => Order::findByUserId($userId),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single order
     */
    public function actionView($id)
    {
        // Check if user is logged in
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'You must be logged in to view order details.');
            return $this->redirect(['/site/login']);
        }

        // Get the logged-in user ID
        $userId = Yii::$app->user->id;
        
        $model = Order::find()
            ->where(['id' => $id, 'user_id' => $userId])
            ->one();
        
        if (!$model) {
            throw new NotFoundHttpException('The requested order does not exist.');
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }
}
