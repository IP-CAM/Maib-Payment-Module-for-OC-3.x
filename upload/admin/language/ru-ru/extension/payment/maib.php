<?php
// Heading
$_['heading_title'] = 'maib';
$_['text_maib'] = '<img src="view/image/payment/maib.png" alt="maib" title="maib" />';

// Text
$_['text_extensions'] = 'Расширения';
$_['text_success'] = 'Успех: настройки расширения maib изменены!';
$_['text_edit'] = 'Изменить настройки расширения maib';

// Legend
$_['legend_setting'] = 'Настройки расширения';
$_['legend_maibmerchants'] = 'Настройки <a href="https://maibmerchants.md" target="_blank">maibmerchants.md</a>';
$_['legend_status'] = 'Настройки статуса заказа';

// Extension settings
$_['entry_title'] = 'Заголовок';
$_['entry_title_help'] = 'Название способа оплаты, которое покупатель увидит при оформлении заказа';
$_['entry_status'] = 'Статус';
$_['entry_debug'] = 'Отладка';
$_['entry_debug_help'] = 'Записывать подробную информацию об отладке в файл с логами';
$_['entry_debug_file'] = 'Файл с логами: ' . DIR_LOGS . 'maib.log';
$_['entry_sort_order'] = 'Порядок сортировки';
$_['entry_geo_zone'] = 'Гео зона';
$_['entry_total'] = 'Общая сумма';
$_['entry_total_help'] = 'Общая сумма заказа, с которого этот способ оплаты становится активным';

// maibmerchants settings
$_['entry_project_id'] = 'Project ID';
$_['entry_project_id_help'] = 'Project ID из вашего проекта в maibmerchants';
$_['entry_project_secret'] = 'Project Secret';
$_['entry_project_secret_help'] = 'Project Secret из вашего проекта в maibmerchants. Доступен при активации проекта.';
$_['entry_signature_key'] = 'Signature Key';
$_['entry_signature_key_help'] = 'Signature Key из вашего проекта в maibmerchants. Доступен при активации проекта.';

$_['entry_ok_url'] = 'Ok URL';
$_['entry_ok_url_help'] = 'Добавьте эту ссылку в поле Ok URL в настройках проекта в maibmerchants.';
$_['entry_fail_url'] = 'Fail URL';
$_['entry_fail_url_help'] = 'Добавьте эту ссылку в поле Fail URL в настройках проекта в maibmerchants.';
$_['entry_callback_url'] = 'Callback URL';
$_['entry_callback_url_help'] = 'Добавьте эту ссылку в поле Callback URL в настройках проекта в maibmerchants.';

// Order status settings
$_['entry_order_pending_status'] = 'Платеж в ожидании';
$_['entry_order_success_status'] = 'Успешная оплата';
$_['entry_order_fail_status'] = 'Неуспешная оплата';
$_['entry_order_refund_status'] = 'Возврат платежа';
$_['entry_order_refund_status_help'] = 'Для возврата платежа обновите статус заказа на выбранного статуса. Средства будут возвращены на карту клиента.';

// Errors
$_['error_permission'] = 'У вас нет разрешения на изменение расширения maib!';
$_['error_empty_field'] = 'Это поле не должно быть пустым!';
?>