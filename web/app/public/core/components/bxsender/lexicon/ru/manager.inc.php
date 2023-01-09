<?php
include_once 'setting.inc.php';

$_lang['bxsender_error'] = 'Произошла ошибка';
$_lang['bxsender_select_segment'] = 'Выберите сегмент';
$_lang['bxsender_select_mailing'] = 'Выберите рассылку';
$_lang['bxsender_select_user'] = 'Выберите пользователя (Не обязательно)';
$_lang['bxsender_select_subscriber'] = 'Выберите подписку';

$_lang['bxsender_rank'] = 'Позиция';
$_lang['bxsender_id'] = 'ID';
$_lang['bxsender_window_create'] = 'Создание';
$_lang['bxsender_window_update'] = 'Обновление';

$_lang['bxsender_action_enable'] = 'Включить';
$_lang['bxsender_action_disable'] = 'Отключить';
$_lang['bxsender_action_check_connection'] = 'Проверить соединение';
$_lang['bxsender_action_reset_error'] = 'Сброс ошибок отправок';


/**
 * STATE
 */
$_lang['bxsender_queue_state_all'] = 'Все состояния';
$_lang['bxsender_queue_state_sent'] = 'Отправлено';
$_lang['bxsender_queue_state_queue'] = 'Генерируются';
$_lang['bxsender_queue_state_no_message'] = 'Нет сообщения';
$_lang['bxsender_queue_state_error'] = 'Ошибка';
$_lang['bxsender_queue_state_waiting'] = 'Ожидает';
$_lang['bxsender_queue_state_prepare'] = 'Подготовка';
$_lang['bxsender_queue_state_undeliverable'] = 'Исключения';


//actions
$_lang['bxsender_actions'] = 'Действия';

$_lang['bxsender_action_enable'] = 'Включить';
$_lang['bxsender_action_disable'] = 'Отключить';
$_lang['bxsender_action_remove'] = 'Удалить';
$_lang['bxsender_action_stream'] = 'Отправить сообщение';
$_lang['bxsender_action_testing'] = 'Создать тестовое сообщение';
$_lang['bxsender_action_combo_sort'] = 'Сортировать по теме';


$_lang['bxsender_action_update'] = 'Обновить';
$_lang['bxsender_action_copy'] = 'Копировать';
$_lang['bxsender_action_getting'] = 'Получение отскоков';
$_lang['bxsender_action_reading'] = 'Чтение сообщений с отскоками';




$_lang['bxsender_btn_save'] = 'Сохранить';
$_lang['bxsender_btn_connect'] = 'Проверить соединение';
$_lang['bxsender_btn_connect_send'] = 'Отправить письмо';
/**
 ********************
 * FORM RETURN-PATH
 *******************
 */
$_lang['bxsender_returnpath'] = 'Обратный путь';
$_lang['bxsender_returnpath_intro'] = 'Адрес Обратного путь используется для автоматического получения и обработки отказов (bounces) почтовым сервером получателя. Мы рекомендуем вам создать специальный адрес для получения таких отказов, это позволит вам получать объективную статистику доставки письма в отчете о рассылке.';

$_lang['bxsender_settings_returnpath_undeliverable_getting'] = 'Проверка почты';
$_lang['bxsender_settings_returnpath_undeliverable_getting_confirm'] = 'Вы уверены что хотите проверить почту?';

$_lang['bxsender_settings_returnpath_undeliverable_reading'] = 'Чтение сообщений с отскоками';
$_lang['bxsender_settings_returnpath_undeliverable_reading_confirm'] = 'Вы уверены что хотите проверить сообщения?';

$_lang['bxsender_settings_returnpath_сonnection'] = 'Проверка соединения';
$_lang['bxsender_settings_returnpath_сonnection_confirm'] = 'Вы уверены что хотите проверить соединение?';

$_lang['bxsender_settings_returnpath_pop_desc'] = 'Настройки POP3 смотрите у вашего почтового провайдера.<br> <em>Для некоторых сервисов POP3 может быть отключен в настройках почтовых ящиков.</em>';

$_lang['bxsender_returnpath_enable'] = 'Обрантный путь';
$_lang['bxsender_returnpath_enable_check'] = 'Включить';
$_lang['bxsender_returnpath_enable_desc'] = '<em>Вы можете указать email адрес куда будут поступать сообщения об ошибках доставки сообщений. Для этих целей необходимо создать новый email адрес который будет использоваться только для получения отчетов доставки</em><br>Обратный путь не будет работать при отправке сообщений через SMTP сервер если email отправителя отличается. Все сообщения с ошибкой доставки будут возвращаться на отправителя';


/**
 ********************
 * FORM MAILSENDER
 *******************
 */
$_lang['bxsender_mailsender_intro'] = '';
$_lang['bxsender_mailsender_fieldset_frequency'] = 'Частота отправки сообщений';
$_lang['bxsender_mailsender_fieldset_smtp'] = 'Настройки соединения SMTP';


$_lang['bxsender_mailsender_transport_smtp_desc'] = '<em>Рекомендуется использовать email корпаративной почты в виде <b>postmaster@site.ru</b></em>';
$_lang['bxsender_mailsender_transport_server_desc'] = '<em>Письма будут отправляться с помощью сервера с указанного E-mail адреса</em>';
$_lang['bxsender_mailsender_transport_system_desc'] = '<em>Письма будут отправляться с указанием системных настроек почты MODX <br> <small>E-mail отправителя: emailsender, Имя отправителя: site_name, Ответный E-mail: emailsender</small></em>';


$_lang['bxsender_mailsender_spf'] = 'Подпись SPF';
$_lang['bxsender_mailsender_spf_desc'] = '<em>Для повышения количества доставляемых писем, рекомендуется настроить записи SPF для вашего доменного имени.</em> <br>SPF устанавливаются с помощью DNS, в зависимости от треборваний вашего провайдера хостинга и доменного имени';


$_lang['bxsender_mailsender'] = 'Транспорт отправителя';
$_lang['bxsender_mailsender_intro'] = 'Настройки отправителя, через которого планируется отправлять письма';


$_lang['bxsender_mailsender_from'] = 'E-mail отправителя<br><em>исходящая почта будет отправляться от указанного e-mail адреса.</em>';
$_lang['bxsender_mailsender_from_name'] = 'Имя отправителя<br><em>Можно написать email отправителя или имя сайта</em>';
$_lang['bxsender_mailsender_transport'] = 'Транспорт';

$_lang['bxsender_mailsender_transport_system'] = 'Системные настройки MODX';
$_lang['bxsender_mailsender_transport_server'] = 'Отправлять с сервера';
$_lang['bxsender_mailsender_transport_smtp'] = 'SMTP';


$_lang['bxsender_menu_create'] = 'Новая запись';
$_lang['bxsender_mailsender_prefix_not'] = 'Нет';
$_lang['bxsender_mailsender_prefix_ssl'] = 'SSL';
$_lang['bxsender_mailsender_prefix_tls'] = 'TLS';


$_lang['bxsender_mailsender_frequency_interval_select'] = 'Выберите интервал';
$_lang['bxsender_mailsender_frequency_interval_minute'] = 'каждую минуту';
$_lang['bxsender_mailsender_frequency_interval_minute_2'] = 'каждые 2 минуты';
$_lang['bxsender_mailsender_frequency_interval_minute_5'] = 'каждые 5 минут (рекомендуется)';
$_lang['bxsender_mailsender_frequency_interval_minute_10'] = 'каждые 10 минут';
$_lang['bxsender_mailsender_frequency_interval_minute_15'] = 'каждые 15 минут';
$_lang['bxsender_mailsender_frequency_interval_minute_30'] = 'каждые 30 минут';
$_lang['bxsender_mailsender_frequency_message'] = 'писем';
$_lang['bxsender_mailsender_frequency_day'] = 'Это <b>{daily_emails}</b> писем в день';


$_lang['bxsender_mailsender_method'] = 'Запуск задания';
$_lang['bxsender_mailsender_method_ajax'] = 'ajax';
$_lang['bxsender_mailsender_method_ajax_desc'] = '<em>Рассылка будут запускатся каждые 60 секунд, когда пользовтатель находится в разделе "Рассылка" через ajax</em>';
$_lang['bxsender_mailsender_method_crontab'] = 'crontab';
$_lang['bxsender_mailsender_method_crontab_desc'] = '<em>Рассылка будут запускаться с помощью crontab задания:</em> [[+task]]<small><em>Внимание!! Текст задания для crontab может выглядеть по другому. Уточняйте информация у вашего хостинг провайдена</em></small>';


$_lang['bxsender_btn_mailsender_create'] = 'Добавить Отправителя';
$_lang['bxsender_mailsender_create'] = 'Добавление Отправителя';
$_lang['bxsender_mailsender_sent_counter'] = 'Отправлено';


$_lang['bxsender_mailsender_started'] = 'Пос. запуск';
$_lang['bxsender_mailsender_sent'] = 'Сообщений отправлено';
$_lang['bxsender_mailsender_status'] = 'Статус';

$_lang['bxsender_mailing_delete_after_sending'] = 'удалить письмо после отправки';
$_lang['bxsender_mailing_not_used'] = 'не используется';


$_lang['bxsender_mailsender_message_verification_empty'] = 'например: info@site.ru';
$_lang['bxsender_mailsender_message_verification'] = 'Отправить проверочное письмо';
$_lang['bxsender_mailsender_message_verification_desc'] = '<em>Чтобы убедиться в возможности отправки сообщений, отправьте себе тестовое сообщение</em>';



/**
 ****************
 * MAILSENDER:::: testing message
 ****************
 */
$_lang['bxsender_settings_mailsender_testing_message'] = 'Отправка тестового сообщения';
$_lang['bxsender_settings_mailsender_testing_message_confirm'] = 'Вы уверены что хотите отправить тестовое сообщение?';


$_lang['bxsender_settings_mailsender_testing_message_email_subject'] = 'Тестовое сообщение';
$_lang['bxsender_settings_mailsender_testing_message_email_body'] = 'Соединение установлено!';
$_lang['bxsender_settings_mailsender_testing_message_email_success'] = 'Тестовое сообщение успешно отправлено! Проверьте почту';


$_lang['bxsender_settings_returnpath_connect_error'] = 'Соединение не установлено. Проверьте настройки соединения и почтовго сервера.';
$_lang['bxsender_settings_returnpath_connect_success'] = 'Соединение установлено';


/**
 ****************
 * Confirm
 ****************
 */


$_lang['bxsender_settings_mailsender_сonnection'] = 'Проверка соединения';
$_lang['bxsender_settings_mailsender_сonnection_confirm'] = 'Вы уверены что хотите проверить соединение?';


// Confirm | COPY
$_lang['bxsender_mailsender_copy'] = 'Копировать отправителя';
$_lang['bxsender_mailsender_copy_confirm'] = 'Вы уверены что хотите скопировать этого отправителя?';
$_lang['bxsender_mailsender_copy_confirm'] = "";

