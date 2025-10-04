<?php

namespace storefront\controllers;

use common\models\CarListing;
use common\models\Order;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * CarsController handles car listing display for storefront
 */
class CarsController extends Controller
{
    
    public function actionView($id)
    {
        $model = CarListing::findOne($id);
        
        if (!$model) {
            throw new NotFoundHttpException('The requested car listing does not exist.');
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionPurchase($id)
    {
        // Check if user is logged in
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'You must be logged in to purchase a car.');
            return $this->redirect(['/site/login']);
        }

        $car = CarListing::findOne($id);
        
        if (!$car) {
            throw new NotFoundHttpException('The requested car listing does not exist.');
        }

        if (!$car->isAvailable()) {
            Yii::$app->session->setFlash('error', 'This car is no longer available for purchase.');
            return $this->redirect(['view', 'id' => $id]);
        }

        if (Yii::$app->request->isPost) {
            // Get the logged-in user ID
            $userId = Yii::$app->user->id;
            
            // Create the order
            $order = new Order();
            $order->car_listing_id = $car->id;
            $order->user_id = $userId;
            $order->purchase_price = $car->price;
            $order->notes = 'Purchase completed via website';
            
            if ($order->save()) {
                // Mark the car as sold
                $car->status = CarListing::STATUS_SOLD;
                $car->save();
                
                Yii::$app->session->setFlash('success', 'Congratulations! You have successfully purchased this car.');
                return $this->redirect(['view', 'id' => $id]);
            } else {
                Yii::$app->session->setFlash('error', 'There was an error processing your purchase. Please try again.');
            }
        }

        return $this->render('purchase', [
            'car' => $car,
        ]);
    }
}
