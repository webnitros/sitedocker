<?php
include_once 'setting.inc.php';

$_lang['bxsender_queues'] = 'Очередь сообщений';
$_lang['bxsender_queue'] = 'Отчет рассылок';
$_lang['bxsender_queue_intro'] = 'Здесь отображается статистика рассылки сообщений. <b>Внимание</b> статистика по рассылке может формироваться в течение суток и более! Для новых рассылок рекомендуется просматривать статистику через 1 сутки минимум.';




$_lang['bxsender_queue_err_nf'] = 'Письмо не найдено.';
$_lang['bxsender_queue_err_ns'] = 'Не указаны идентификаторы писем.';

$_lang['bxsender_queue_id'] = 'id';
$_lang['bxsender_queue_segment_id'] = 'id подписки';
$_lang['bxsender_queue_subscriber_id'] = 'id подписчика';
$_lang['bxsender_queue_email_to'] = 'Кому';
$_lang['bxsender_queue_email_subject'] = 'Тема письма';
$_lang['bxsender_queue_opens'] = 'Прочитано';
$_lang['bxsender_queue_clicks'] = 'Переходов';
$_lang['bxsender_queue_action'] = 'Действие';
$_lang['bxsender_queue_createdon'] = 'Дата создания';
$_lang['bxsender_queue_state'] = 'Состояние';
$_lang['bxsender_queue_email_body'] = 'Тело письма';
$_lang['bxsender_queue_updatedon'] = 'Дата обновления';
$_lang['bxsender_queue_datesent'] = 'Дата отправки';

$_lang['bxsender_queue_update'] = 'Изменить письмо';
$_lang['bxsender_queue_send'] = 'Отправить письмо';
$_lang['bxsender_queues_send'] = 'Отправить письма';
$_lang['bxsender_queues_send_confirm'] = 'Вы действительно хотите отправить эти письма?';
$_lang['bxsender_queues_remove'] = 'Удалить письма';
$_lang['bxsender_queues_remove_confirm'] = 'Вы действительно хотите удалить эти письма?';
$_lang['bxsender_queues_send_all'] = 'Отправить все письма';
$_lang['bxsender_queues_send_all_confirm'] = 'Вы действительно хотите отправить все письма?';
$_lang['bxsender_queues_all_query_confirm'] = 'Вы действительно хотите сделать запрос для всех писем?';
$_lang['bxsender_queues_remove_all'] = 'Удалить все письма';
$_lang['bxsender_queues_remove_all_confirm'] = 'Вы действительно хотите удалить все письма?';






/**
 * **********
 * FORM CHART
 *************
 */
$_lang['bxsender_chart_all_message'] = 'Все сообщения';
$_lang['bxsender_chart_all_message_desc'] = 'Покажет все отправленные сообщения';
$_lang['bxsender_queue_chart_unsubscribed'] = 'Отписались';
$_lang['bxsender_queue_chart_unsubscribed_desc'] = 'Сообщения из которых пользователи отписались от рассылки';
$_lang['bxsender_queue_chart_opens'] = 'Прочитали';
$_lang['bxsender_queue_chart_opens_desc'] = 'Сообщения которые были прочитаны пользователем в почтовых клиентах';
$_lang['bxsender_queue_chart_clicks'] = 'Открыли';
$_lang['bxsender_queue_chart_clicks_desc'] = 'Сообщения из которых пользователь перешел на страницу сайта, по ссылке указанной в письме';
$_lang['bxsender_queue_chart_undeliverable'] = 'Отказы';
$_lang['bxsender_queue_chart_undeliverable_desc'] = 'Сообщения которые не удалось доставить. Почтовый сервер получателя или отправителя вернул сообщение об ошибке доставки';
$_lang['bxsender_queue_chart_unknown'] = 'Неизвестно';
$_lang['bxsender_queue_chart_unknown_desc'] = 'Сообщения у которые нету статистики';
$_lang['bxsender_queue_sent'] = 'Отправлено';
$_lang['bxsender_queue_chart_sent'] = 'Отправлено';
$_lang['bxsender_queue_chart_sent_desc'] = 'Количество отправленных писем';
$_lang['bxsender_queue_chart_failure'] = 'Ошибка отправки';
$_lang['bxsender_queue_chart_failure_desc'] = 'Отправка сообщения не удалась';

$_lang['bxsender_btn_refresh'] = 'Получить статистику';
$_lang['bxsender_btn_clear_filter'] = 'Сброс фильтров';
$_lang['bxsender_chart_date_start'] = 'Начало рассылки';
$_lang['bxsender_chart_date_end'] = 'Окончание рассылки';
$_lang['bxsender_chart_form_search'] = 'Поиск пользователя';
$_lang['bxsender_queue_form_reset'] = 'Сбросить';
$_lang['bxsender_queue_form_submit'] = 'Получить';



/**
 * **********
 * GRID QUEUE
 *************
 */
$_lang['bxsender_queue_unsubscribed'] = 'Отписался';
$_lang['bxsender_queue_unsubscribedon'] = 'Дата отписки';
$_lang['bxsender_queue_failure'] = 'Отказ';
$_lang['bxsender_queue_undeliverable'] = 'Ошибка доставки';
$_lang['bxsender_queue_chart_percent'] = 'Процент';
$_lang['bxsender_queue_chart_mailing_statistics'] = 'Статистика рассылки';
$_lang['bxsender_queue_total_count'] = 'Всего сообщений';
$_lang['bxsender_chart_form_status'] = 'Статус сообщения';




/* ACTIONS */
$_lang['bxsender_queues_action_actionState'] = 'Состояние';
$_lang['bxsender_queues_action_showMessage'] = 'Информация';
$_lang['bxsender_queues_action_actionContent'] = 'Сообщение';
$_lang['bxsender_queues_action_removeQueue'] = 'Удалить сообщение';
$_lang['bxsender_queues_action_testingQueue'] = 'Начать отправку заново';








// btn
$_lang['bxsender_subscriber_btn_dispatch'] = 'Проверить не отрпавленные письма';
$_lang['bxsender_subscriber_btn_update_date'] = 'Массовое изменение дат';


$_lang['bxsender_action_removeSubscriber'] = 'Отписать пользователя';
$_lang['bxsender_action_removeQueue'] = 'Удалить письмо';
$_lang['bxsender_action_actionSend'] = 'Отправить письмо';
$_lang['bxsender_action_actionQuery'] = 'Сформировать письмо';
$_lang['bxsender_action_sendQueue'] = 'Отправить письмо';




/**
 * **********
 * GRID QUEUE FORM
 *************
 */
$_lang['bxsender_queue_form_mailing_label'] = 'Рассылка';
$_lang['bxsender_queue_form_mailing_empty'] = 'Выберите рассылку';
$_lang['bxsender_queue_form_state_label'] = 'Состояние';
$_lang['bxsender_queue_form_state_empty'] = 'Все состояния';


$_lang['bxsender_queue_stat_empty'] = '<em><small>Неизвестно</small></em>';
$_lang['bxsender_queue_stat_StatOpens'] = 'Письмо открыто в';
$_lang['bxsender_queue_stat_StatClicks'] = 'Переходов по ссылкам из сообщения';
$_lang['bxsender_queue_stat_StatUnSubscribed'] = 'Отписался из сообщения в';
$_lang['bxsender_queue_stat_StatUnDeliverable'] = 'Получен отскок доставки сообщения в';