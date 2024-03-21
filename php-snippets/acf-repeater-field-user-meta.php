<?php

/*
 * Update ACF repeater field using action hook for user meta on user registration.
 * Action Hook: fluentform/user_registration_completed
 *
 */

/*
 * Note:
 * - Replace $allowFormId with your form ID.
 * - Replace $repeaterFieldName with the responsible form repeater field name.
 * - Replace $acfUserRepeaterFieldName with the responsible ACF repeater field name.
 */


add_action('fluentform/user_registration_completed', function($userId, $feed, $entry, $form) {
    $allowFormId = 0;
    if ($form->id != $allowFormId) return;

    // Decode the form data
    $formData = json_decode($entry->response, true);
    $repeaterFieldName = 'repeater_field';

    // Check if the form data contains the repeater field
    if (isset($formData[$repeaterFieldName]) && function_exists('get_field_object')) {
        // Get the repeater field value
        $ffRepeaterValue = $formData[$repeaterFieldName];
        $acfUserRepeaterFieldName = 'acf_repeater_field_name';

        // Get the ACF field configuration using the current user ID
        $fieldConfig = get_field_object($acfUserRepeaterFieldName, 'user_' . $userId);

        // Ensure $fieldConfig is not falsy before proceeding
        if ($fieldConfig && $fieldConfig['type'] == 'repeater' && isset($fieldConfig['sub_fields'])) {
            $subFields = $fieldConfig['sub_fields'];

            // Prepare the repeater field value for ACF
            $itemValues = [];
            foreach ($ffRepeaterValue as $value) {
                if (count($value) !== count($subFields)) {
                    continue;
                }
                $item = [];
                foreach ($subFields as $subIndex => $subField) {
                    $item[$subField['name']] = $value[$subIndex];
                }
                $itemValues[] = $item;
            }

            // Update the ACF repeater field value
            if ($itemValues) {
                update_field($acfUserRepeaterFieldName, $itemValues, 'user_' . $userId);
            }
        }
    }
}, 10, 4);

