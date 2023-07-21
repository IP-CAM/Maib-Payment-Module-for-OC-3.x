<?php
// Heading
$_['heading_title'] = 'maib';
$_['text_maib'] = '<img src="view/image/payment/maib.png" alt="maib" title="maib" />';

// Text
$_['text_extensions'] = 'Extensions';
$_['text_success'] = 'Success: extension settings changed!';
$_['text_edit'] = 'Edit <b>maib</b> extension settings';

// Legend
$_['legend_setting'] = 'Extension settings';
$_['legend_maibmerchants'] = '<a href="https://maibmerchants.md" target="_blank">maibmerchants.md</a> settings';
$_['legend_status'] = 'Order status settings';

// Extension settings
$_['entry_title'] = 'Title';
$_['entry_title_help'] = 'Payment method title that the customer will see during checkout';
$_['entry_status'] = 'Status';
$_['entry_debug'] = 'Debug';
$_['entry_debug_help'] = 'Record detailed debug info to the log file';
$_['entry_debug_file'] = 'Log file: ' . DIR_LOGS . 'maib.log';
$_['entry_sort_order'] = 'Sort Order';
$_['entry_geo_zone'] = 'Geo Zone';
$_['entry_total'] = 'Total';
$_['entry_total_help'] = 'The total amount of the order for this payment method to become active';

// maibmerchants settings
$_['entry_project_id'] = 'Project ID';
$_['entry_project_id_help'] = 'Project ID from maibmerchants';
$_['entry_project_secret'] = 'Project Secret';
$_['entry_project_secret_help'] = 'Project Secret from maibmerchants. It is available on Project activation.';
$_['entry_signature_key'] = 'Signature Key';
$_['entry_signature_key_help'] = 'Signature Key from maibmerchants. It is available on Project activation.';

$_['entry_ok_url'] = 'Ok URL';
$_['entry_ok_url_help'] = 'Add this link to the Ok URL field in the maibmerchants Project settings';
$_['entry_fail_url'] = 'Fail URL';
$_['entry_fail_url_help'] = 'Add this link to the Fail URL field in the maibmerchants Project settings';
$_['entry_callback_url'] = 'Callback URL';
$_['entry_callback_url_help'] = 'Add this link to the Callback URL field in the maibmerchants Project settings';

// Order status settings
$_['entry_order_pending_status'] = 'Pending payment';
$_['entry_order_success_status'] = 'Completed payment';
$_['entry_order_fail_status'] = 'Failed payment';
$_['entry_order_refund_status'] = 'Refunded payment';
$_['entry_order_refund_status_help'] = 'For payment refund, update the order status to the selected status. The funds will be returned to the customer card.';

// Errors
$_['error_permission'] = 'You do not have permission to modify maib extension!';
$_['error_empty_field'] = 'This field must not be empty!';
?>