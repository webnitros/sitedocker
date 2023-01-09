<?php

$_lang['area_bxsender_main'] = 'Основные';
$_lang['area_bxsender_mailsender'] = 'Транспорт отправителя';
$_lang['area_bxsender_returnpath'] = 'Обрантый путь';
$_lang['area_bxsender_minishop2'] = 'MiniShop2';

$_lang['setting_bxsender_frontend_js'] = 'Скрипт js для фронтенд';
$_lang['setting_bxsender_frontend_js_desc'] = '';


$_lang['setting_bxsender_handler_mailing'] = 'Класс отправки сообщений';
$_lang['setting_bxsender_handler_mailing_desc'] = 'По умолчанию bxMailingHandler. Вы можете создать свой класс обработки сообщений';
$_lang['setting_bxsender_handler_query'] = 'Класс генерации сообщений';
$_lang['setting_bxsender_handler_query_desc'] = 'По умолчанию bxSenderQueryHandler. Вы можете создать свой класс обработки сообщений';

$_lang['setting_bxsender_page_unsubscribe'] = 'Страница быстрой отписки';
$_lang['setting_bxsender_page_unsubscribe_desc'] = 'Укажите ID страницы для быстрой отписки. Если оставить пустым то пользователь попадет на техническу страницу отписки.';

$_lang['setting_bxsender_page_subscribe_manager'] = 'Страница для управления подписками';
$_lang['setting_bxsender_page_subscribe_manager_desc'] = 'Укажите ID страницы для управление подписками пользователя с размещенным сниппетом [[!bxUnSubscribe]] отписки ';

$_lang['setting_bxsender_all_messages_system'] = 'Пересылать все email сообщения системы';
$_lang['setting_bxsender_all_messages_system_desc'] = 'По умолчанию "Нет". Если вы хотите чтобы все сообщения с сайта пересылались через компонент, установите "Да"';

$_lang['setting_bxsender_minishop_order_subscribe'] = 'Подписывать на сегменты во время оформления закза';
$_lang['setting_bxsender_minishop_order_subscribe_desc'] = 'Перечислите список ID сегментов на которые будет подписан пользователь при оформлении заказа через корзину minishop2';

$_lang['setting_bxsender_page_confirmationemail'] = 'Страница подтверждения E-mail';
$_lang['setting_bxsender_page_confirmationemail_desc'] = 'Укажите ID страницы подтверждения E-mail адреса пользователя пользователя с размещенным сниппетом [[!bxUnSubscribe]] отписки ';

$_lang['setting_bxsender_undeliverable_error_count'] = 'Количество ошибок доставки';
$_lang['setting_bxsender_undeliverable_error_count_desc'] = 'По умолчанию 2. Количество ошибок доставки после которого подписчик автоматически отписывается от рассылки. Установите 0 если не хотите продолжать отправлять письма на этот емаил';

$_lang['setting_bxsender_pdotools_elements_path'] = 'Путь к шаблонам';
$_lang['setting_bxsender_pdotools_elements_path_desc'] = 'Вы можете указать свой путь для pdoTools для хранения шаблонов';

$_lang['setting_bxsender_mailsender_from'] = 'E-mail Отправитель';
$_lang['setting_bxsender_mailsender_from_name'] = 'Имя отправителя';

$_lang['setting_bxsender_mailsender_reply_to'] = 'Ответный E-mail Reply-To';
$_lang['setting_bxsender_mailsender_frequency_emails'] = 'Лимит сообщений';
$_lang['setting_bxsender_mailsender_frequency_interval'] = 'Интервал отправки (мин)';
$_lang['setting_bxsender_mailsender_host'] = 'Имя хоста SMTP';
$_lang['setting_bxsender_mailsender_port'] = 'Порт (SMTP)';
$_lang['setting_bxsender_mailsender_username'] = 'Логин';
$_lang['setting_bxsender_mailsender_password'] = 'Пароль';
$_lang['setting_bxsender_mailsender_ssl'] = 'Безопасность (SSL)';
$_lang['setting_bxsender_mailsender_active'] = 'Включить';
$_lang['setting_bxsender_mailsender_frequency_emails_desc'] = 'Кол-во сообщений, за указанный интервал';
$_lang['setting_bxsender_mailsender_frequency_interval_desc'] = 'Интервальность рассылки сообщений';
$_lang['setting_bxsender_mailsender_prefix'] = 'Шифрование';
$_lang['setting_bxsender_mailsender_mailing_log'] = 'Лог рассылки';
$_lang['setting_bxsender_mailsender_transport'] = 'Транспорт';
$_lang['setting_bxsender_mailsender_method'] = 'Запуск заданий'; //cron
$_lang['setting_bxsender_mailsender_message_verification'] = 'Отправить проверочное письмо';

$_lang['setting_bxsender_returnpath_enable'] = 'Использовать обратный путь';
$_lang['setting_bxsender_returnpath'] = 'Обратные пути';
$_lang['setting_bxsender_returnpath_email'] = 'Email';
$_lang['setting_bxsender_returnpath_host'] = 'Имя хоста POP3';
$_lang['setting_bxsender_returnpath_port'] = 'Порт (POP3)';
$_lang['setting_bxsender_returnpath_username'] = 'Логин';
$_lang['setting_bxsender_returnpath_password'] = 'Пароль';
$_lang['setting_bxsender_returnpath_ssl'] = 'Безопасность (SSL)';
$_lang['setting_bxsender_returnpath_timeout'] = 'Таймаут соединения';
$_lang['setting_bxsender_do_not_send_messages'] = 'Запретить отправлять сообщения';
$_lang['setting_bxsender_do_not_send_messages_desc'] = 'По умолчанию Нет. Внимание!!! при включении этой опции сообщения не будут отсылатся на емаил а буду оставать в очереди, со статусом отправлен!';

$_lang['setting_bxsender_minishop2_prefix_service'] = 'Префикс для сортировки писем';
$_lang['setting_bxsender_minishop2_prefix_service_desc'] = 'По умолчанию "Minishop2". Префикс необходимо для того чтобы индитифицировать письмо с сервисом от которого будут рассылаться письма! Оставьте пусты чтобы не создавать рассылку для серсиса minishop2';

$_lang['setting_bxsender_minishop2_status_change'] = 'Включить смену статусов в Minishop2';
$_lang['setting_bxsender_minishop2_status_change_desc'] = 'По умолчанию Нет. Установите Да если хотите чтобы во время смены статуса заказа в minishop2 записывались логи отправки сообщений вместе со статусами';
