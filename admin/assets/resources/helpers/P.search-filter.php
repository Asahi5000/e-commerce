<?php

// Search filter (by name or brand)
if (!empty($search)) {
    $sql .= " AND (cars.name LIKE :search OR cars.brand LIKE :search)";
    $params[':search'] = "%$search%";
}