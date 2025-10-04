<?php

namespace common\jobs;

use common\models\CarListing;
use common\models\CarListingSearch;
use common\models\Export;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

/**
 * Background job for exporting car listings to CSV
 */
class CarListingExportJob extends BaseObject implements JobInterface
{

    public $exportId;

    public $filters = [];

    public $outputDir;

    public function execute($queue)
    {
        $export = Export::findOne($this->exportId);

        if (!$export) {
            \Yii::error("Export job failed: Export ID {$this->exportId} not found", __METHOD__);
            return;
        }

        try {

            $export->markProcessing();

            if (!is_dir($this->outputDir)) {
                mkdir($this->outputDir, 0755, true);
            }

            $filename = 'car_listings_export_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.csv';
            $filePath = $this->outputDir . DIRECTORY_SEPARATOR . $filename;


            $searchModel = new CarListingSearch();
            $searchModel->load($this->filters, '');

            $query = CarListing::find();

            // check search model filter
            $query->andFilterWhere([
                'year' => $searchModel->year,
                'status' => $searchModel->status,
            ]);

            $query->andFilterWhere(['like', 'make', $searchModel->make])
                ->andFilterWhere(['like', 'model', $searchModel->model])
                ->andFilterWhere(['like', 'title', $searchModel->title]);

                
            $cars = $query->all();
            $totalRecords = count($cars); // total records in csv


            $this->generateCsvFile($filePath, $cars); // put data in csv


            $export->filename = $filename;
            $export->file_path = $filePath;
            $export->markCompleted($totalRecords);

            \Yii::info("Export completed successfully: {$filename} ({$totalRecords} records)", __METHOD__);
        } catch (\Exception $e) {
            \Yii::error("Export job failed: " . $e->getMessage(), __METHOD__);

            if ($export) {
                $export->markFailed($e->getMessage());
            }
        }
    }

    protected function generateCsvFile($filePath, $cars)
    {
        $handle = fopen($filePath, 'w');

        if (!$handle) {
            throw new \Exception("Cannot create file: {$filePath}");
        }

        try {

            $headers = [
                'ID',
                'Title',
                'Make',
                'Model',
                'Year',
                'Price',
                'Mileage',
                'Description',
                'Status',
                'Created At',
                'Updated At'
            ];

            fputcsv($handle, $headers);


            foreach ($cars as $car) {
                $row = [
                    $car->id,
                    $car->title,
                    $car->make,
                    $car->model,
                    $car->year,
                    $car->price,
                    $car->mileage,
                    $car->description,
                    $car->status,
                    date('Y-m-d H:i:s', $car->created_at),
                    date('Y-m-d H:i:s', $car->updated_at)
                ];

                fputcsv($handle, $row);
            }
        } finally {
            fclose($handle);
        }
    }
}
