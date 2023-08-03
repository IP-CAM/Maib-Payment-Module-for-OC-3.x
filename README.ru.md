[![N|Solid](https://www.maib.md/images/logo.svg)](https://www.maib.md)

# Модуль Maib Payment Gateway для Opencart v. 3.x
Этот плагин позволяет интегрировать ваш Opencart интернет-магазин с **maib e-commerce API ** для приема онлайн-платежей (Visa/Mastercard/Google Pay/Apple Pay).

## Описание
Для использования этого плагина вы должны быть зарегистрированы на платформе [maibmerchants.md](https://maibmerchants.md).

Сразу после регистрации вы сможете совершать платежи в тестовой среде, используя данные из Тестового проекта.

Чтобы совершать реальные платежи, вы должны совершить хотя бы одну успешную транзакцию в тестовой среде и выполнить необходимые шаги для активации Производственного проекта.

### Шаги для активации Производственного проекта
1. Заполненный профиль в maibmerchants
2. Проверенный профиль
3. Контракт электронной коммерции

## Функционал
**Платежи онлайн**: Visa / Mastercard / Apple Pay / Google Pay.

**Три валюты**: MDL / USD / EUR (в зависимости от настроек вашего проекта).

**Возврат платежа**: Для возврата платежа необходимо обновить статус заказа (см. _return.png_) на статус, выбранный для _Возврат платежа_ в настройках расширения **maib** (см. _settings.png_). Сумма платежа будет возвращена на карту клиента.

## Требования
- Регистрация на платформе maibmerchants.md
- Опенкарт v.3.x
- Расширения _curl_ и _json_ включены

## Установка
1. Загрузите файл расширения с Github или из репозитория Opencart (_maib3.ocmod.zip_).
2. В админ панели Opencart перейдите в раздел _Расширения > Установщик_.
3. Нажмите кнопку _Загрузить_ и выберите файл расширения. После завершения загрузки OpenCart начнет процесс установки.
4. Перейдите в _Расширения > Модификации_ и нажмите кнопку _Обновить_.
5. Перейдите в _Расширения > Расширения_ и выберите тип расширения _Оплата_. Вы должны увидеть расширение **maib** в списке.
6. Нажмите кнопку _Установить_.
7. Нажмите кнопку _Изменить_ для настроек расширения.

## Settings
1. Title - title of the payment method displayed to the customer on the checkout page.
2. Status - enable/disable extension.
3. Debug - enable/disable extension logs in _maib.log_ file.
4. Sort Order - payment method sort order on the checkout page.
5. Geo Zone - select the geographic regions to which the payment method will apply.
6. Project ID - Project ID from maibmerchants.md
7. Project Secret - Project Secret from maibmerchants.md. It is available after project activation.
8. Signature Key - Signature Key for validating notifications on Callback URL. It is available after project activation.
9. Ok URL / Fail URL / Callback URL - add links in the respective fields of the Project settings in maibmerchants.
10. Order status settings: Pending payment - Order status when payment is in pending.
11. Order status settings: Completed payment - Order status when payment is successfully completed.
12. Order status settings: Failed payment - Order status when payment failed.
13. Order status settings: Refunded payment - Order status when payment is refunded. For payment refund, update the order status to the this selected status (see _refund.png_).

## Troubleshooting
Enable debug mode in the plugin settings and access the log file.

If you require further assistance, please don't hesitate to contact the **maib** ecommerce support team by sending an email to ecom@maib.md. 

In your email, make sure to include the following information:
- Merchant name
- Project ID
- Date and time of the transaction with errors
- Errors from log file

