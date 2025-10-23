<?php

// Category filter
if (!empty($category)) {
    $sql .= " AND cars.category_id = :category";
    $params[':category'] = $category;
}