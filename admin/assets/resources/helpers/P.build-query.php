<?php

// Build query
$sql = "SELECT cars.*, car_categories.category_name 
        FROM cars 
        LEFT JOIN car_categories ON cars.category_id = car_categories.category_id 
        WHERE 1=1";

$params = [];