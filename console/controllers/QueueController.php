<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Queue worker controller for processing background jobs
 */
class QueueController extends Controller
{
    
    public function actionRun($verbose = 0)
    {
        $this->stdout("Starting queue worker...\n", Console::FG_GREEN);
        
        try {
            // Process jobs with proper parameters
            $processed = Yii::$app->queue->run(false, 1000, 5); //false just one loop , 1000 max exec time,5 number of jobs if failed to process
            $this->stdout("Processed {$processed} jobs.\n", Console::FG_GREEN);
        } catch (\Exception $e) {
            $this->stdout("Queue worker error: " . $e->getMessage() . "\n", Console::FG_RED);
            return 1;
        }
        
        $this->stdout("Queue worker finished.\n", Console::FG_GREEN);
        return 0;
    }

    /**
     * Listens for new jobs and processes them
     * 
     * @param int $verbose Verbosity level (0-3)
     */
    public function actionListen($verbose = 0)
    {
        $this->stdout("Starting queue listener...\n", Console::FG_GREEN);
        $this->stdout("Press Ctrl+C to stop.\n", Console::FG_YELLOW);
        
        try {
            Yii::$app->queue->listen();
        } catch (\Exception $e) {
            $this->stdout("Queue listener error: " . $e->getMessage() . "\n", Console::FG_RED);
            return 1;
        }
        
        return 0;
    }

    /**
     * Shows queue status
     */
    public function actionStatus()
    {
        $this->stdout("Queue Status:\n", Console::FG_GREEN);
        
        try {
            $queue = Yii::$app->queue;
            $this->stdout("Queue class: " . get_class($queue) . "\n");
            
            if (method_exists($queue, 'getStatistics')) {
                $stats = $queue->getStatistics();
                $this->stdout("Statistics: " . json_encode($stats, JSON_PRETTY_PRINT) . "\n");
            }
            
        } catch (\Exception $e) {
            $this->stdout("Error getting queue status: " . $e->getMessage() . "\n", Console::FG_RED);
            return 1;
        }
        
        return 0;
    }

    /**
     * Clears all jobs from the queue
     */
    public function actionClear()
    {
        $this->stdout("Clearing queue...\n", Console::FG_YELLOW);
        
        try {
            Yii::$app->queue->clear();
            $this->stdout("Queue cleared successfully.\n", Console::FG_GREEN);
        } catch (\Exception $e) {
            $this->stdout("Error clearing queue: " . $e->getMessage() . "\n", Console::FG_RED);
            return 1;
        }
        
        return 0;
    }
}
