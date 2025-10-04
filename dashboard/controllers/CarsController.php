<?php

namespace dashboard\controllers;

use common\models\CarListing;
use common\models\CarListingSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * CarsController implements the CRUD actions for CarListing model.
 */
class CarsController extends Controller
{

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

    public function actionIndex()
    {

        $searchModel = new CarListingSearch();


        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('_ajax_response', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new CarListing();

        if ($model->load(Yii::$app->request->post())) {
            // Get uploaded files
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');

            if ($model->save()) {
                if (!empty($model->imageFiles)) {
                    if ($model->imageFiles && $model->uploadImages()) {
                        $model->save(false);
                        Yii::$app->session->setFlash('success', 'Car listing created successfully.');
                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        Yii::info('Image upload failed', 'image-upload');
                        Yii::$app->session->setFlash('warning', 'Car listing created but some images failed to upload.');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } else {
                    Yii::$app->session->setFlash('success', 'Car listing created successfully.');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                Yii::error('Model save failed: ' . json_encode($model->getErrors()), 'image-upload');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');

            if ($model->save()) {
                if (!empty($model->imageFiles)) {
                    if ($model->imageFiles && $model->uploadImagesWithReplacement()) {
                        $model->save(false);
                        Yii::$app->session->setFlash('success', 'Car listing updated successfully.');
                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        Yii::$app->session->setFlash('warning', 'Car listing updated but some images failed to upload.');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } else {
                    // if ($model->images == '[]') {
                    //     $model->images = null;
                    //     $model->save(false);
                    // }
                    Yii::$app->session->setFlash('success', 'Car listing updated successfully.');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Car listing deleted successfully.');

        return $this->redirect(['index']);
    }

    /**
     * Delete a specific image from a car listing
     */
    public function actionDeleteImage($id, $imageIndex)
    {
        $model = $this->findModel($id);
        $filename = $model->getImageByIndex($imageIndex);

        if ($filename && $model->deleteImage($filename)) {
            Yii::$app->session->setFlash('success', 'Image deleted successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to delete image.');
        }

        // if($model->images == '[]'){
        //     $model->images = null;
        //     $model->save(false);
        // }

        return $this->redirect(['update', 'id' => $id]);
    }

    protected function findModel($id)
    {
        if (($model = CarListing::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
