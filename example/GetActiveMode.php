<?php
include __DIR__.'/bootstrap.php';

use Viessmann\API\ViessmannApiException;

try {
    echo "Active mode for default circuit(): " . $viessmannApi->getActiveMode(5) . "\n";
} catch (ViessmannApiException $e) {
    echo "Message: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
    echo "Trace:" . $e->getTraceAsString() . "\n";
}
