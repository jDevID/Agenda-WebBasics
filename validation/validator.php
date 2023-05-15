<?php

function validate_required_fields($data): bool
{
    $required_fields = ['name', 'description', 'date', 'start_hour', 'end_hour'];

    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            return false;
        }
    }

    return true;
}

function validate_name_and_description($data): bool
{
    $name = $data['name'];
    $description = $data['description'];

    if (strlen($name) < 3 || strlen($name) > 35 || strlen($description) < 20 || strlen($description) > 300) {
        return false;
    }

    return true;
}

function validate_date_format($date) {
    return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date);
}

function validate_hour_format($hour) {
    return preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $hour);
}

function validate_hour_range($start_hour, $end_hour): bool {
    $start_time = strtotime($start_hour);
    $end_time = strtotime($end_hour);

    return $start_time < $end_time;
}
?>
