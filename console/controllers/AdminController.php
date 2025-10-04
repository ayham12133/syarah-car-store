<?php

namespace console\controllers;

use common\models\User;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Admin user creation controller
 * Creates admin users through console commands only
 */
class AdminController extends Controller
{
    /**
     * Creates an admin user
     * 
     * @param string $username The username for the admin
     * @param string $email The email address for the admin
     * @param string $password The password for the admin
     */
    public function actionCreate($username, $email, $password)
    {
        if (empty($username) || empty($email) || empty($password)) {
            $this->stdout("Error: Username, email, and password are required.\n");
            $this->stdout("Usage: php yii admin/create <username> <email> <password>\n");
            return 1;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->stdout("Error: Invalid email format.\n", Console::FG_RED);
            return 1;
        }

        if (User::findByUsername($username)) {
            $this->stdout("Error: Username '{$username}' already exists.\n", Console::FG_RED);
            return 1;
        }

        if (User::findByEmail($email)) {
            $this->stdout("Error: Email '{$email}' already exists.\n", Console::FG_RED);
            return 1;
        }

        try {
            $user = new User();
            $user->username = $username;
            $user->email = $email;
            $user->status = User::STATUS_ACTIVE;
            $user->is_admin = true;
            $user->setPassword($password);
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();
            $user->verification_token = null;
            
            if ($user->save()) {
                $this->stdout("Admin user '{$username}' created successfully!\n", Console::FG_GREEN);
                return 0;
            } else {
                $this->stdout("Error: Failed to create admin user.\n", Console::FG_RED);
                return 1;
            }
        } catch (\Exception $e) {
            $this->stdout("Error: " . $e->getMessage() . "\n", Console::FG_RED);
            return 1;
        }
    }
}
