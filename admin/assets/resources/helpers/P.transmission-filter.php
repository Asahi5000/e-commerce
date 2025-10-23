<?php

// Transmission filter
if (!empty($transmission)) {
    $sql .= " AND cars.transmission = :transmission";
    $params[':transmission'] = $transmission;
}