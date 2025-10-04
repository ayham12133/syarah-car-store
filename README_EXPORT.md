# CSV Export System

This document describes the CSV export system for the Car Store application.

## Features

- **Background Processing**: Uses Yii2 Queue extension for background CSV generation
- **Filtering**: Apply filters to export specific car listings
- **Status Tracking**: Real-time status updates (pending, processing, completed, failed)
- **File Management**: Automatic file cleanup and download functionality
- **Admin Interface**: Complete admin panel for managing exports

## Components

### 1. Database Table
- `exports` table stores export job information
- Tracks status, filters, file paths, and metadata

### 2. Models
- `Export` model for managing export records
- `CarListingExportJob` job class for background processing

### 3. Controllers
- `ExportController` for admin interface
- `QueueController` for managing background jobs

### 4. Views
- Export list with status indicators
- Create export form with filtering options
- Export details view

## Usage

### Starting the Queue Worker

To process background jobs, run the queue worker:

```bash
# Listen for new jobs (recommended for production)
php yii queue/listen

# Run jobs once and exit
php yii queue/run
```

### Creating Exports

1. Navigate to Dashboard → Export
2. Click "Create New Export"
3. Configure filters (optional)
4. Click "Create Export"
5. Monitor status in the export list

### Downloading Files

1. Go to Export Management
2. Find completed exports
3. Click the download button
4. File will be downloaded to your computer

## File Structure

```
dashboard/
├── controllers/
│   └── ExportController.php
├── views/
│   └── export/
│       ├── index.php
│       ├── create.php
│       └── view.php
└── web/
    └── exports/          # Generated CSV files

common/
├── models/
│   └── Export.php
└── jobs/
    └── CarListingExportJob.php

console/
├── controllers/
│   └── QueueController.php
└── migrations/
    └── m251003_035734_create_exports_table.php
```

## Configuration

### Queue Component
Configured in `common/config/main.php`:

```php
'queue' => [
    'class' => \yii\queue\file\Queue::class,
    'path' => '@runtime/queue',
],
```

### Export Directory
CSV files are stored in `dashboard/web/exports/` directory.

## Status Indicators

- **Pending**: Export job queued, waiting to be processed
- **Processing**: Export job currently running
- **Completed**: Export finished successfully, file ready for download
- **Failed**: Export encountered an error

## Security

- Admin-only access to export functionality
- Files stored outside web root for security
- Automatic cleanup of old export files
- Input validation and sanitization

## Troubleshooting

### Queue Not Processing
1. Check if queue worker is running: `php yii queue/status`
2. Start queue worker: `php yii queue/listen`
3. Check runtime directory permissions

### Export Fails
1. Check export details for error message
2. Verify database connection
3. Check file system permissions
4. Review application logs

### File Not Found
1. Check if export is completed
2. Verify file exists in exports directory
3. Check file permissions

## Maintenance

### Cleanup Old Exports
Exports older than 30 days should be cleaned up periodically:

```sql
DELETE FROM exports WHERE created_at < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 DAY));
```

### Monitor Queue
Regularly check queue status and restart workers if needed:

```bash
php yii queue/status
php yii queue/listen
```
