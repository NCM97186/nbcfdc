## Description

This module adds an extra validation to each webform fields, allowing you to
specify validation rules for your webform components. You can create one or more
of the predefined validation with respective fields.

The following validation rules are currently included:

- Equal values on multiple fields
- Compare two fields
- Require at least one of two fields
- Require at least one of several fields

## Installation

1. Place the module folder in your modules folder
2. Make sure you have the webform module enabled
3. Activate the module via admin/modules

## Usage

Once you have installed the module, an extra validation block should be
available in following fields ('date', 'email', 'hidden', 'number', 'select',
'textarea', 'textfield','webform_time'). This extra validation is labeled "Form
extra validation". Upon selecting this, you can choose to add one of the
available validation against your field. Make sure you have added the webform
components you wish to validate before adding the validation rule. Before adding
validation, you can understand the following details for your rule:

- Source Field which is your are going to edit and adding the validation.
- Destination Field which are the fields you going to select against your Source
field
- one or more webform components that should be validated against this rule
(depending on the chosen field, you will have to select a specific number of
components for the validation rule to work properly).

Depending on the chosen rule, more form fields will be available on the rules
form:

- optionally a custom error message textfield

Once you have configured your desired validation rules for the selected webform
components, every time a user fills in the webform, the validation will be
triggered for the selected components, and show the user a standard form error
message when entered data doesn't pass the validation rule you have set up.

## Adding custom validation rules

The following steps will let you add custom validators through your module:

1. Implement hook hook_form_alter(). This hook will alter the existing webform
element form and insert some extra validations which will not handled in form.
It implements the extra validation rules against your selected fields. The form
should have the following extra validation rules such as:
   * Equal values: It will match n no of fields and against your selected
   fields.
   * Compare with: It will compare the fields one to one
   * Several components: It will match n no of fields and against your selected
   fields.
2. Implement validateBackendComponents function to validate the backend form
validation.
3. Implement hook hook_webform_submission_form_alter($form, $formState,
$formid). This hook gets passed 3 parameters, which will allow you to react to
your custom validator (or any other validator for that matter). See function
validate() in WebformValidateConstraint.php for front end validates one each
components.

## Author

- Arghajit Lodh  (https://www.drupal.org/user/3621154)
- Gopikrishna Ganesh (https://www.drupal.org/user/3617307)

The author can be contacted for paid customizations of this module as well as
Drupal consulting and development.
