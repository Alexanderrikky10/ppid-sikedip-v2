<?php

return [
    'default_log_name' => 'default',
    'default_auth_driver' => null,
    'subject_returns_soft_deleted_models' => false,
    'activity_model' => \Spatie\Activitylog\Models\Activity::class,
    'table_name' => env('ACTIVITY_LOG_TABLE', 'activity_log'),
    'database_connection' => env('ACTIVITY_LOG_DB_CONNECTION', null),
    'clean_oldest_records_before' => 365, // simpan 1 tahun
];
