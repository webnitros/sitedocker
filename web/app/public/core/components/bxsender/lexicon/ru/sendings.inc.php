<?php
include_once 'setting.inc.php';


$_lang['bxsender_mailing'] = 'Рассылки';
$_lang['bxsender_mailing_intro'] = 'Создание новых рассылок и просмотр статуса отправки рассылки. Автоматическая отправка происходит каждые 60 секунд при методе отправки "ajax"';


$_lang['bxsender_undeliverables'] = 'Ошибки доставки';
$_lang['bxsender_undeliverables_intro'] = 'Список e-mail адресов, для которых вернулась ошибка о доставке сообщений';



/* mailing */
$_lang['bxsender_mailing_btn_create'] = 'Новая рассылка';


/**
 * GRID mailing
 */
$_lang['bxsender_mailing_tab_message'] = 'Сообщение';
$_lang['bxsender_mailing_tab_report'] = 'Отчет рассылки';
$_lang['bxsender_mailing_tab_setting'] = 'Настройки';
$_lang['bxsender_mailing_field_email_subject'] = 'Новая рассылка';
$_lang['bxsender_mailing_message'] = 'Сообщение';
$_lang['bxsender_mailing_utm'] = 'Включить UTM-метки (для «Яндекс.Метрики» или Google Analytics) ';
$_lang['bxsender_mailing_start_by_time'] = 'Отложенная рассылка';
$_lang['bxsender_mailing_returnpath'] = 'Обратный путь';
$_lang['bxsender_mailing_returnpath_desc'] = 'Адрес Обратного пути используется для автоматического получения и обработки отказов (bounces) почтовым сервером получателя. Мы рекомендуем вам создать специальный адрес для получения таких отказов, это позволит вам получать объективную статистику доставки письма в отчете о рассылке.
<br>Внимание!!! При использовании отправителя через SMTP, email для обратного пути должен совпадать с отправителем';

$_lang['bxsender_mailing_start_mailing'] = 'Запуск рассылки';
$_lang['bxsender_mailing_start_mailing_desc'] = 'Время начала рассылки';

$_lang['bxsender_mailing_start_by_timedon'] = 'Разрешить отправку после';
$_lang['bxsender_mailing_start_by_timedon_desc'] = 'Выберите дату и время после которого запуститься процедура закупка рассылки';


$_lang['bxsender_mailing_utm_title'] = 'UTM метки';
$_lang['bxsender_mailing_utm_source'] = 'Источник (utm_source)';
$_lang['bxsender_mailing_utm_source_desc'] = 'Тип ресурса, который создает вам приток посетителей, напр., segment.';
$_lang['bxsender_mailing_utm_medium'] = 'Канал (utm_medium)';
$_lang['bxsender_mailing_utm_medium_desc'] = 'Маркетинговый инструмент, которым является ваша рассылка, напр., email.';
$_lang['bxsender_mailing_utm_campaign'] = 'Компания (utm_campaign)';
$_lang['bxsender_mailing_utm_campaign_desc'] = 'Название вашей маркетинговой кампании, напр., тема письма.';

$_lang['bxsender_mailing_mailsender'] = 'Отправитель';
$_lang['bxsender_mailing_mailsender_desc'] = 'Рассылка будет производиться от имени выбранного отправителя';
$_lang['bxsender_select_returnpath'] = 'Выбрать';

$_lang['bxsender_mailing_active'] = 'Включить';
$_lang['bxsender_mailing_description'] = 'Дополнительное описание';
$_lang['bxsender_mailing_testing_title'] = 'Тестирование отправки';
$_lang['bxsender_mailing_testing_send_emails'] = 'Дополнительные список email адресов';
$_lang['bxsender_mailing_testing_btn'] = 'Отправить тестовое письмо';
$_lang['bxsender_mailing_openbrowse_btn'] = 'Открыть в браузере';
$_lang['bxsender_mailing_testing_user'] = 'отправить себе: [[+fullname]] <b><span><</span>[[+email]]<span>></span></b>';



$_lang['bxsender_mailing_copy'] = 'Копировать рассылку';
$_lang['bxsender_mailing_copy_confirm'] = 'Вы уверены что хотите создать компию этой рассылки?';


// TopBar
$_lang['bxsender_field_undeliverable'] = 'Исключенные';
$_lang['bxsender_field_active'] = 'Активные';



$_lang['bxsender_queue_createdon'] = 'Дата создания';
$_lang['bxsender_actions_desc'] = 'Управление действиями';


/**
 * GRID UNDELIVERABLE
 */
$_lang['bxsender_undeliverable_returnpath_email'] = 'E-mail обратный путь';
$_lang['bxsender_undeliverable_email'] = 'E-mail с ошибкой';
$_lang['bxsender_undeliverable_hash_queue'] = 'hash сообщения';
$_lang['bxsender_undeliverable_subject'] = 'Тема';
$_lang['bxsender_undeliverable_cat'] = 'Категория';
$_lang['bxsender_undeliverable_type'] = 'Тип';
$_lang['bxsender_undeliverable_action'] = 'Action';
$_lang['bxsender_undeliverable_status'] = 'Статус';
$_lang['bxsender_undeliverable_createdon'] = 'Дата создания';
$_lang['bxsender_undeliverable_btn_check'] = 'Проверить почту';

$_lang['bxsender_undeliverable_check'] = 'Получить ошибки о доставке';
$_lang['bxsender_undeliverable_check_confirm'] = 'Вы уверены что хотите получить ошибки о доставке?';


/**
 * GRID Recipients
 */
$_lang['bxsender_recipients_name'] = 'Сигмент';
$_lang['bxsender_recipients_subscriber_count'] = 'Подписчиков';

/**
 ****************
 * Confirm
 ****************
 */

$_lang['bxsender_mailing_addqueues'] = 'Добавление сообщений в очередь';
$_lang['bxsender_mailing_addqueues_confirm'] = 'Вы уверены что хотите добавить сообщения в очередь?';