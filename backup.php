<?php

require_once __DIR__ . '/vendor/autoload.php';

$psh = new Platformsh\ConfigReader\Config();

if($psh->isAvailable()) {
    $sql_filename = time() . '.sql';
    $database = $psh->relationships['database'][0];
    $backup_path = getenv('PLATFORM_DIR') . "/backups/$sql_filename";
    putenv("PGPASSWORD={$database['password']}");
    exec("pg_dump -U {$database['username']} -h {$database['host']} {$database['path']} > $backup_path");

    $s3 = new Aws\S3\S3Client([
        'version' => 'latest',
        'region'  => 'us-east-1'
    ]);

    $s3->putObject([
        'Bucket' => 'ibd-backups',
        'Key' => "database/$sql_filename",
        'Body' => fopen($backup_path, 'r')
    ]);
}
