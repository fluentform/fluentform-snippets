<?php

/*
 * You can implement validate any input in your form easily.
 * This snippet will show how you can validate single line input text
 */

/*
 * Common Filter hook names
 * text/Mask: fluentform/validate_input_item_input_text
 * email: fluentform/validate_input_item_input_email
 * textarea: fluentform/validate_input_item_textarea
 * numeric: fluentform/validate_input_item_input_number
 * numeric: fluentform/validate_input_item_input_number
 * Range Slider: fluentform/validate_input_item_rangeslider
 * Range Slider: fluentform/validate_input_item_rangeslider
 * Address: fluentform/validate_input_item_address
 * Address: fluentform/validate_input_item_address
 * Country Select: fluentform/validate_input_item_select_country
 * Country Select: fluentform/validate_input_item_select_country
 * Select: fluentform/validate_input_item_select
 * Radio: fluentform/validate_input_item_input_radio
 * Checkbox: fluentform/validate_input_item_input_checkbox
 * Checkbox: fluentform/validate_input_item_input_checkbox
 * Website URL: fluentform/validate_input_item_input_input_url
 * Website URL: fluentform/validate_input_item_input_input_url
 * Date: fluentform/validate_input_item_input_input_date
 * Image Upload: fluentform/validate_input_item_input_image
 * File Upload: fluentform/validate_input_item_input_file
 * Phone Filed: fluentform/validate_input_item_phone
 * Phone Filed: fluentform/validate_input_item_phone
 * Color Picker: fluentform/validate_input_item_color_picker
 * Net Promoter Score: fluentform/validate_input_item_net_promoter_score
 * Password: fluentform/validate_input_item_input_password
 * Ratings: fluentform/validate_input_item_ratings
 * Ratings: fluentform/validate_input_item_ratings
 */

/*
 * Snippet: 1
 * This will apply for all the forms in your site
 * $errorMessage: String
 * $field: Array - Contains the fill field settings
 * $formData: Array - Contains all the user input values as key pair
 * $fields: Array - All fields of the form
 * $form: Object - The Form Object
 */
add_filter('fluentform/validate_input_item_input_text', function ($errorMessage, $field, $formData, $fields, $form) {
    $fieldName = $field['name'];
    if (empty($formData[$fieldName])) {
        return $errorMessage;
    }
    $value = $formData[$fieldName]; // This is the user input value

    /*
     * You can validate this value and return $errorMessage
     * If $error is empty then it's valid. Otherwise you can return the $errorMessage message as string
     */

    return $errorMessage;

}, 10, 5);


/*
 * Snippet: 2
 * This will apply for all the forms in your site
 */
add_filter('fluentform/validate_input_item_input_text', function ($errorMessage, $field, $formData, $fields, $form) {

    /*
     * Validate only for form id 12
     */
    $targetFormId = 12;
    if ($form->id != $targetFormId) {
        return $errorMessage;
    }

    $fieldName = $field['name'];
    if (empty($formData[$fieldName])) {
        return $errorMessage;
    }
    $value = $formData[$fieldName]; // This is the user input value

    /*
     * You can validate this value and return $errorMessage
     * If $error is empty then it's valid. Otherwise, you can return the $errorMessage message as string
     */

    return [$errorMessage];

}, 10, 5);
