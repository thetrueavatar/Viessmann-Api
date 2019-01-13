<?php
include __DIR__ . '/bootstrap.php';
$schedule = "{
        \"mon\": [
          {
            \"start\": \"05:30\",
            \"end\": \"22:00\",
            \"mode\": \"normal\",
            \"position\": 0
          }
        ],
        \"tue\": [
          {
            \"start\": \"05:50\",
            \"end\": \"22:00\",
            \"mode\": \"normal\",
            \"position\": 0
          }
        ],
        \"wed\": [
          {
            \"start\": \"05:50\",
            \"end\": \"22:00\",
            \"mode\": \"normal\",
            \"position\": 0
          }
        ],
        \"thu\": [
          {
            \"start\": \"05:50\",
            \"end\": \"22:00\",
            \"mode\": \"normal\",
            \"position\": 0
          }
        ],
        \"fri\": [
          {
            \"start\": \"05:50\",
            \"end\": \"08:00\",
            \"mode\": \"normal\",
            \"position\": 0
          },
          {
            \"start\": \"16:00\",
            \"end\": \"22:00\",
            \"mode\": \"normal\",
            \"position\": 1
          }
        ],
        \"sat\": [
          {
            \"start\": \"07:00\",
            \"end\": \"22:00\",
            \"mode\": \"normal\",
            \"position\": 0
          }
        ],
        \"sun\": [
          {
            \"start\": \"05:50\",
            \"end\": \"12:00\",
            \"mode\": \"normal\",
            \"position\": 0
          },
          {
            \"start\": \"18:00\",
            \"end\": \"22:00\",
            \"mode\": \"normal\",
            \"position\": 1
          }
        ]
      }";
$viessmannApi->setRawHeatingSchedule($schedule);
print_r($viessmannApi->getHeatingSchedule());
