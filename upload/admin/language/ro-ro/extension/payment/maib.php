<?php
// Heading
$_['heading_title'] = 'maib';
$_['text_maib'] = '<img src="view/image/payment/maib.png" alt="maib" title="maib" />';

// Text
$_['text_extensions'] = 'Extensii';
$_['text_success'] = 'Succes: setările extensiei maib au fost modificate!';
$_['text_edit'] = 'Modificare setări extensie maib';

// Legend
$_['legend_setting'] = 'Setãri generale';
$_['legend_maibmerchants'] = 'Setãri <a href="https://maibmerchants.md" target="_blank">maibmerchants.md</a>';
$_['legend_status'] = 'Setãri stare comandã';

// Extension settings
$_['entry_title'] = 'Titlu';
$_['entry_title_help'] = 'Titlul metodei de plată pe care clientul îl va vedea pe pagina de checkout';
$_['entry_status'] = 'Stare';
$_['entry_debug'] = 'Depanare';
$_['entry_debug_help'] = 'Înregistrați informații detaliate de depanare în fișierul cu log-uri';
$_['entry_debug_file'] = 'Fișierul cu log-uri: ' . DIR_LOGS . 'maib.log';
$_['entry_sort_order'] = 'Ordinea de sortare';
$_['entry_geo_zone'] = 'Zona geografică';
$_['entry_total'] = 'Suma totalã';
$_['entry_total_help'] = 'Suma totalã a comenzii de la care aceastã metodă de plată devine activă';

// maibmerchants settings
$_['entry_project_id'] = 'Project ID';
$_['entry_project_id_help'] = 'Project ID din proiectul dvs. în maibmerchants';
$_['entry_project_secret'] = 'Project Secret';
$_['entry_project_secret_help'] = 'Project Secret din proiectul dvs. în maibmerchants. Este accesibil la activarea Proiectului.';
$_['entry_signature_key'] = 'Signature Key';
$_['entry_signature_key_help'] = 'Signature Key din proiectul dvs. în maibmerchants. Este accesibil la activarea Proiectului.';

$_['entry_ok_url'] = 'Ok URL';
$_['entry_ok_url_help'] = 'Adăugați acest link în câmpul Ok URL din setările Proiectului în maibmerchants';
$_['entry_fail_url'] = 'Fail URL';
$_['entry_fail_url_help'] = 'Adăugați acest link în câmpul Fail URL din setările Proiectului în maibmerchants';
$_['entry_callback_url'] = 'Callback URL';
$_['entry_callback_url_help'] = 'Adăugați acest link în câmpul Callback URL din setările Proiectului în maibmerchants';

// Order status settings
$_['entry_order_pending_status'] = 'Platã în așteptare';
$_['entry_order_success_status'] = 'Platã cu succes';
$_['entry_order_fail_status'] = 'Platã eșuatã';
$_['entry_order_refund_status'] = 'Platã returnatã';
$_['entry_order_refund_status_help'] = 'Pentru returnarea plății, actualizați starea comenzii la starea selectată. Suma va fi returnatã pe cardul clientului.';

// Errors
$_['error_permission'] = 'Nu aveți permisiunea de a modifica setările extensiei maib!';
$_['error_empty_field'] = 'Acest cîmp este obligatoriu pentru completare!';
?>