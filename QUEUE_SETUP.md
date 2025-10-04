# Export Queue Setup

## Current Status: ✅ BACKGROUND PROCESSING ENABLED

Export jobs are processed in the background by a continuous queue worker.

## How It Works

1. **Create Export**: Jobs are queued when you create an export
2. **Background Processing**: Queue worker processes jobs automatically
3. **Status Updates**: Export status changes from "Pending" to "Completed" when processed
4. **Download Ready**: Files become available for download when completed

## Test the Background Process

1. Go to Dashboard → Export → Create New Export
2. Fill out the form and click "Create Export" 
3. You should see "Export job has been queued successfully. It will be processed in the background."
4. Back to the export list, your export will show "Pending" status initially
5. Wait a few seconds and refresh - status should change to "Completed"
6. Download button will become available when completed

## For Production Environments (Optional)

If you want even more robust background processing, you can run:

### Windows:
```bash
# Double-click this file or run in command prompt:
start_queue_worker.bat

# OR manually:
php yii queue/listen
```

### Linux/Mac:
```bash
# Start continuous worker:
php yii queue/listen

# OR run in background:
nohup php yii queue/listen &

# OR run as systemd service:
# Create service file for automatic startup
```

## Queue Management Commands

```bash
# Check queue status
php yii queue/status

# Process jobs once and exit
php yii queue/run

# Start continuous listener
php yii queue/listen

# Clear all pending jobs
php yii queue/clear
```

## Troubleshooting

### Export Stays Pending
- Try refreshing the page first
- If still pending, run: `php yii queue/run`

### Error Messages
- Check application logs in `dashboard/runtime/logs/`
- Run: `php yii queue/status`

### Queue Not Working
- Verify runtime directory exists: `dashboard/runtime/queue/`
- Check file permissions on runtime directory

## System Requirements

- PHP 7.4+ 
- Extensions: zip, json, mysqli
- Write permissions on runtime directories
- ~50MB disk space for exports

## File Locations

- **Export files**: `dashboard/web/exports/`
- **Queue data**: `dashboard/runtime/queue/`
- **Logs**: `dashboard/runtime/logs/`
- **Worker script**: `start_queue_worker.bat`
