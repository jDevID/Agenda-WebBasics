<?php

function extract_form_data($fields): array
{
    $data = [];

    foreach ($fields as $field) {
        $data[$field] = $_POST[$field] ?? null;
    }

    return $data;
}
