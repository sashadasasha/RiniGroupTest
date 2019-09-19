<?php
#скрипт для скачивания csv файла
$filename = './report.csv';

header("Content-type: application/x-download");
header("Content-Disposition: attachment; filename=$filename");
readfile($filename);