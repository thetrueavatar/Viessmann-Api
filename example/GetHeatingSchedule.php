<?php
include __DIR__ . '/bootstrap.php';
echo json_encode($viessmannApi->getHeatingSchedule());