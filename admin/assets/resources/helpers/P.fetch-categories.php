<?php

// Fetch categories for filter dropdown
$categories = $conn->query("SELECT * FROM car_categories ORDER BY category_name ASC")->fetchAll(PDO::FETCH_ASSOC);