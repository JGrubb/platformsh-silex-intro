<?php

require_once __DIR__ . '/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
$home_dir = getenv('PLATFORM_DIR');
$logger = new Logger('backup_logger');
$logger->pushHandler(new StreamHandler($home_dir . '/backups/log/backup.log', Logger::DEBUG));

$psh = new Platformsh\ConfigReader\Config();
if($psh->isAvailable() && getenv('PLATFORM_BRANCH') === 'master') {

    try {
        $sql_filename = date('Y-m-d') . '.sql';
        $database = $psh->relationships['database'][0];
        $backup_path = $home_dir . "/backups/$sql_filename";
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
        $logger->info("Successfully backed up $sql_filename");
    } catch (Exception $e) {
        $logger->error("error: " . $e->getMessage());
    }
}
