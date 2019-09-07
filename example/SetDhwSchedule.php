<?php
include __DIR__ . '/bootstrap.php';
$schedule = "{
    \"mon\": [
      {
        \"start\": \"05:00\",
        \"end\": \"05:40\",
        \"mode\": \"on\",
        \"position\": 0
      },
      {
        \"start\": \"05:20\",
        \"end\": \"05:40\",
        \"mode\": \"on\",
        \"position\": 3
      }
    ],
    \"tue\": [
      {
        \"start\": \"05:20\",
        \"end\": \"05:30\",
        \"mode\": \"on\",
        \"position\": 0
      }
    ],
    \"wed\": [
      {
        \"start\": \"05:20\",
        \"end\": \"05:30\",
        \"mode\": \"on\",
        \"position\": 0
      }
    ],
    \"thu\": [
      {
        \"start\": \"05:20\",
        \"end\": \"05:30\",
        \"mode\": \"on\",
        \"position\": 0
      }
    ],
    \"fri\": [
      {
        \"start\": \"05:20\",
        \"end\": \"05:30\",
        \"mode\": \"on\",
        \"position\": 0
      }
    ],
    \"sat\": [
      {
        \"start\": \"05:20\",
        \"end\": \"05:30\",
        \"mode\": \"on\",
        \"position\": 0
      }
    ],
    \"sun\": [
      {
        \"start\": \"05:20\",
        \"end\": \"05:30\",
        \"mode\": \"on\",
        \"position\": 0
      }
    ]
}";
$viessmannApi->setRawDhwSchedule($schedule);
print_r($viessmannApi->getDhwSchedule());
