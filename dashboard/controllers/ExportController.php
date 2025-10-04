<?php

namespace dashboard\controllers;

use common\jobs\CarListingExportJob;
use common\models\CarListing;
use common\models\CarListingSearch;
use common\models\Export;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ExportController handles CSV export functionality for admins
 */
class ExportController extends Controller
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
        $dataProvider = new ActiveDataProvider([
            'query' => Export::find()->with('createdBy')->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    
    public function actionCreate()
    {
        $searchModel = new CarListingSearch();
        
        if (Yii::$app->request->isPost) {
            $filters = Yii::$app->request->post('CarListingSearch', []);
            
            // Create export record
            $export = new Export();
            $export->filename = 'pending_' . time() . '.csv';
            $export->file_path = 'pending'; // Temporary path until job completes
            $export->status = Export::STATUS_PENDING;
            $export->created_by = Yii::$app->user->id;
            $export->setFiltersArray($filters);
            
            if ($export->save()) {
                
                $job = new CarListingExportJob([
                    'exportId' => $export->id,
                    'filters' => $filters,
                    'outputDir' => Yii::getAlias('@dashboard/web') . '/exports',
                ]);
                
                Yii::$app->queue->push($job);
                
                Yii::$app->session->setFlash('success', 'Export job has been queued successfully. It will be processed in the background.');
                return $this->redirect(['index']);
            } else {
                $errors = $export->getErrors();
                $errorMessage = 'Failed to create export job.';
                if (!empty($errors)) {
                    $errorMessage .= ' Errors: ' . implode(', ', array_map(function($fieldErrors) {
                        return implode(', ', $fieldErrors);
                    }, $errors));
                }
                Yii::$app->session->setFlash('error', $errorMessage);
            }
        }

        return $this->render('create', [
            'searchModel' => $searchModel,
        ]);
    }

    
    public function actionDownload($id)
    {
        $export = $this->findModel($id);
        
        if (!$export->isCompleted()) {
            Yii::$app->session->setFlash('error', 'Export is not completed yet.');
            return $this->redirect(['index']);
        }
        
        if (!file_exists($export->file_path)) {
            Yii::$app->session->setFlash('error', 'Export file not found.');
            return $this->redirect(['index']);
        }
        
        return Yii::$app->response->sendFile($export->file_path, $export->filename);
    }
    
    public function actionDelete($id)
    {
        $export = $this->findModel($id);
        
        // Delete the file if it exists
        if (file_exists($export->file_path)) {
            unlink($export->file_path);
        }
        
        $export->delete();
        
        Yii::$app->session->setFlash('success', 'Export record deleted successfully.');
        return $this->redirect(['index']);
    }
    
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    protected function findModel($id)
    {
        if (($model = Export::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
