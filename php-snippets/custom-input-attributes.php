<?php
/*
 * In this example, Custom data attributes are being added to input text field
 * Use Case:You need custom data attributes
 */

$targetElement = 'input_text';
add_filter('fluentform/rendering_field_data_' . $targetElement, function ($data, $form) {
    $myAttributes = [
        'custom-attr'   => 'Test Value',
        'custom-attr-2' => 'Another Values'
    ];
    // Update your target field name
    $targetField = 'input_text';
    if ($data['attributes']['name'] != $targetField) {
        return $data;
    }
    $data['attributes'] = array_merge($data['attributes'], $myAttributes);
    
    return $data;
}, 10, 2);
