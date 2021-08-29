#!/bin/bash

php ../vendor/bin/openapi --format json --bootstrap ./swagger-constants.php --output ../docker/swagger/openapi.json ./swagger-v1.php ../app/Http/Controllers
