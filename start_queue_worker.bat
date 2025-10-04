@echo off
echo Starting Car Store Queue Worker...
echo This will continuously process export jobs in the background.
echo Press Ctrl+C to stop.
echo.
php yii queue/listen
pause
