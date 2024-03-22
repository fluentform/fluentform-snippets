<?php

/*
 * Update ACF repeater field using action hook for user meta on user registration.
 * Action Hook: fluentform/user_registration_completed
 *
 */

/*
 * Note:
 * - Replace $targetFormId with your form ID.
 * - Replace $targetFormFieldName with the responsible form repeater field name.
 * - Replace $targetAcfUserRepeaterFieldName with the responsible ACF repeater field name.
 */


/**
 * Snipped 1 - For User Registration
 *
 * Read more about this hook
 * @link https://developers.fluentforms.com/hooks/actions/#fluentform_user_registration_completed
 *
 */
add_action('fluentform/user_registration_completed', function($userId, $feed, $entry, $form) {
    $targetFormId = 0;
    // Return if the form ID is not equal to target FormId
    if ($form->id != $targetFormId) return;

    // Decode the form data
    $formData = json_decode($entry->response, true);
    $targetFormFieldName = 'repeater_field';

    // Check if the form data contains the repeater field
    if (isset($formData[$targetFormFieldName]) && function_exists('get_field_object')) {
        // Get the repeater field value
        $ffRepeaterValue = $formData[$targetFormFieldName];
        $targetAcfUserRepeaterFieldName = 'acf_repeater_field_name';

        // Get the ACF field configuration using the current user ID
        $fieldConfig = get_field_object($targetAcfUserRepeaterFieldName, 'user_' . $userId);

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
                update_field($targetAcfUserRepeaterFieldName, $itemValues, 'user_' . $userId);
            }
        }
    }
}, 10, 4);


/**
 * Snipped 2 - For User Update
 *
 * Read more about this hook
 * @link https://developers.fluentforms.com/hooks/actions/#fluentform_user_update_completed
 *
 */
add_action('fluentform/user_update_completed', function($userId, $feed, $entry, $form) {
    $targetFormId = 0;
    // Return if the form ID is not equal to target FormId
    if ($form->id != $targetFormId) return;

    // Decode the form data
    $formData = json_decode($entry->response, true);
    $targetFormFieldName = 'repeater_field';

    // Check if the form data contains the repeater field
    if (isset($formData[$targetFormFieldName]) && function_exists('get_field_object')) {
        // Get the repeater field value
        $ffRepeaterValue = $formData[$targetFormFieldName];
        $targetAcfUserRepeaterFieldName = 'acf_repeater_field_name';

        // Get the ACF field configuration using the current user ID
        $fieldConfig = get_field_object($targetAcfUserRepeaterFieldName, 'user_' . $userId);

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
                update_field($targetAcfUserRepeaterFieldName, $itemValues, 'user_' . $userId);
            }
        }
    }
}, 10, 4);


/**
 * Snipped 3 - Default Repeater Value Mapping for User Update
 *
 * For some very old repeater filed use 'fluentform/render_item_input_repeat',
 * Repeater filed created from FF version 3 use input_repeat for element type
 * Repeater filed created after FF version 3 use repeater_field for element type
 *
 * Read more about this hook
 * @link https://developers.fluentforms.com/hooks/actions/#fluentform_render_item___item_element_
 *
 */
add_action('fluentform/render_item_repeater_field', function($field, $form) {
    // Return if the form ID is not equal to target FormId
    $targetFormId = 0;
    if ($form->id != $targetFormId) return;

    // Check acf field value get function exist
    if (!function_exists('get_field')) return;

    // Form repeater field name
    $targetFormFieldName = 'repeater_field';

    // Return if the form field is not equal to target field
    if ($field['attributes']['name'] != $targetFormFieldName) return;

    // Get the ACF meta value for the acf meta name
    $targetAcfUserRepeaterFieldName = 'acf_repeater_field_name';
    $userRepeaterFieldValue = get_field($targetAcfUserRepeaterFieldName, 'user_' . get_current_user_id());

    // Return if the meta value is empty and not an array
    if (!$userRepeaterFieldValue || !is_array($userRepeaterFieldValue)) {
        return;
    }

    // Normalize the array structure to ff repeater format
    foreach ($userRepeaterFieldValue as $index => &$subFieldValue) {
        if (!is_array($subFieldValue) || count($subFieldValue) !== count($field['fields'])) {
            unset($userRepeaterFieldValue[$index]);
            continue;
        }
        $subFieldValue = array_values($subFieldValue);
    }

    // If the array is empty after normalization, return
    if (!$userRepeaterFieldValue) {
        return;
    }

    // Pass the ACF values from PHP to JavaScript
    $acfValues = json_encode($userRepeaterFieldValue);
    ?>
    <script>
        // Pass the ACF values from PHP to JavaScript
        var acfUserMetaRepeaterValues = <?php echo $acfValues; ?>;

        // jQuery document ready function
        jQuery(document).ready(function($) {
            // Find the repeater table within the form
            var $repeaterTable = $('form[data-form_id="<?php echo $targetFormId?>"] table[data-root_name="<?php echo $targetFormFieldName?>"]');

            // Find the rows of the repeater table
            var $repeaterTableTr = $repeaterTable.find('tbody tr');

            // Check if the repeater table contains rows
            if (!$repeaterTableTr.length) {
                return;
            }

            // Loop through each ACF repeater row value
            acfUserMetaRepeaterValues.forEach(function(acfUserMetaRepeaterValue, acfUserMetaRepeaterValueIndex) {
                // Clone the first row of the repeater table
                var $repeaterTableTrFresh = $repeaterTableTr.eq(0).clone();

                // Update the cloned elements
                $repeaterTableTrFresh.find('td').each(function(tdIndex) {
                    var $el = $(this).find('.ff-el-form-control:last-child');
                    var newId = 'ffrpt-' + (new Date()).getTime() + tdIndex;
                    var itemProp = {
                        value: acfUserMetaRepeaterValue[tdIndex] || '', // Set value from ACF values or empty string
                        id: newId
                    };
                    $el.prop(itemProp);
                    if ($el.attr('data-mask')) {
                        $el.mask($el.attr('data-mask'));
                    }
                });

                // Insert the fresh copy after the original <tr>
                $repeaterTableTrFresh.insertAfter($repeaterTable.find('tbody tr').eq(acfUserMetaRepeaterValueIndex));
            });

            // Remove the first empty row
            $repeaterTable.find('tbody tr').eq(0).remove();
        });
    </script>
    <?php
}, 10, 2);
