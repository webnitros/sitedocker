<?php
include_once 'setting.inc.php';

$_lang['bxsender'] = 'bxSender';
$_lang['bxsender_menu_desc'] = 'Управление подписками';

$_lang['bxsender_subscribers'] = 'Подписчики';
$_lang['bxsender_subscribers_intro'] = 'Список подписчиков';

$_lang['bxsender_unsubscribeds'] = 'Отписавшиеся';
$_lang['bxsender_unsubscribeds_intro'] = 'Список отписавшихся подписчиков';


$_lang['bxsender_subscriber_remove_unactivesubscribe'] = 'Удалить не подтвержденные e-mail';
$_lang['bxsender_subscriber_mass_actions'] = 'Массовые действия';
$_lang['bxsender_subscribe_intro'] = 'Вы можете подписаться на рассылку «[[+name]]»!';
$_lang['bxsender_unsubscribe_intro'] = 'Вы уже подписаны на рассылку «[[+name]]». Хотите отписаться?';
$_lang['bxsender_subscriber_confirmed'] = 'E-mail подтвержден';


$_lang['bxsender_subscribe_activate_subject'] = 'Подтвердите ваш email!';
$_lang['bxsender_subscribe_err_already'] = 'Этот email уже подписан на рассылку.';
$_lang['bxsender_subscribe_err_email_wrong'] = 'Неверный email.';
$_lang['bxsender_subscribe_err_email_ns'] = 'Нужно указать email.';
$_lang['bxsender_subscribe_err_email_send'] = 'Не могу отправить email.';


$_lang['bxsender_subscriber_removeunconfirmed'] = 'Удалить не подтвержденные e-mail';
$_lang['bxsender_subscriber_removeunconfirmed_confirm'] = 'Вы уверены что хотите удалить все неподтвержденные e-mail адреса?. Будут удалены все E-mail адреса подписавшиеся с фронтенда и не активировавшие подписку.';

/**
 ***********************
 * GRID SUBSCRIBE
 ***********************
 */
$_lang['bxsender_subscriber_tab_main'] = 'Основные';
$_lang['bxsender_subscriber_tab_members'] = 'Сегменты';


$_lang['bxsender_subscriber_id'] = 'ID';
$_lang['bxsender_subscriber_user_id'] = 'Пользователь';
$_lang['bxsender_subscriber_user_id_desc'] = 'Указывать не обязательно. Необходимо в случае если требуется получить какие то поля пользователя';
$_lang['bxsender_subscriber_email'] = 'Е-mail получателя';
$_lang['bxsender_subscriber_email_desc'] = 'На этот e-mail адрес будет происходить отправка сообщения';
$_lang['bxsender_subscriber_fullname'] = 'Имя получателя';
$_lang['bxsender_subscriber_fullname_desc'] = 'Имя получателя можно указать в разной форме к примеру "Иванов Иван Иванович"';
$_lang['bxsender_subscriber_segment'] = 'Сегменты';
$_lang['bxsender_subscriber_segment_desc'] = 'Вы можете указать сегмент для которой в дальнейшем будет создаваться рассылка';
$_lang['bxsender_subscriber_hash'] = 'hash';
$_lang['bxsender_subscriber_state'] = 'Состояние';
$_lang['bxsender_subscriber_segments_count'] = 'Сегментов';
$_lang['bxsender_subscriber_createdon'] = 'Дата создания';
$_lang['bxsender_subscriber_filter_active'] = 'Активные';
$_lang['bxsender_subscriber_opens'] = 'Открытий';
$_lang['bxsender_subscriber_updatedon'] = 'Обновлено состояние';
$_lang['bxsender_subscriber_undeliverable'] = 'Ошибка доставки';
$_lang['bxsender_subscriber_unsubscribed'] = 'Отписался';
$_lang['bxsender_subscriber_active'] = 'Активна';
$_lang['bxsender_subscriber_segment_subject'] = 'Тема подписки';
$_lang['bxsender_subscriber_segment_id'] = 'Id подписки';
$_lang['bxsender_subscriber_none_name'] = 'отсутствует';


$_lang['bxsender_subscriber_err_save'] = 'Не удалось сохранить подписку';
$_lang['bxsender_subscriber_err_user_id'] = 'Указанный пользователь не найден';
$_lang['bxsender_subscriber_err_email'] = 'Некоректный email адрес';
$_lang['bxsender_subscriber_err_segment'] = 'Необходимо выбрать хотябы один сегмент для подписки';
$_lang['bxsender_subscriber_err_email_segment'] = 'Вы уже подписаны на этот емаил для выбранный подписки';

$_lang['bxsender_subscriber_list'] = 'Список пользователей';
$_lang['bxsender_subscriber_list_desc'] = 'Добавление пользователей через строку в виде: <b>имя <span><</span>info@bustep.ru<span>></span></b>';


// Confirm
$_lang['bxsender_subscriber_all_remove_confirm'] = 'Вы уверены что хотите удалить всех подписчиков?.';
$_lang['bxsender_subscriber_sync_confirm'] = 'Вы уверены что хотите загрузить подсписчиков с основного севера? Все текущие подписчика будут удалены.';
$_lang['bxsender_subscriber_all_dispatch_confirm'] = 'Вы уверены что хотите проверить все подписки?';
$_lang['bxsender_subscriber_all_back_confirm'] = 'Вы уверены что хотите откатить даты у выбранной подписки на сутки назад?';
$_lang['bxsender_subscriber_all_stream_confirm'] = 'Вы уверены что хотите отправить сообщения в очередь по выбранной подписки?';


// actions
$_lang['bxsender_action_disableSubscriber'] = 'Отключить подписку';
$_lang['bxsender_action_enableSubscriber'] = 'Включить подписку';
$_lang['bxsender_action_streamSubscriber'] = 'Разрешить создавать сообщени?';
$_lang['bxsender_action_showMessage'] = 'Показать информацию';
$_lang['bxsender_action_testingSubscriber'] = 'Создать тестовое сообщение';

$_lang['bxsender_action_actionQuery'] = 'Создание письма';
$_lang['bxsender_action_actionContent'] = 'Показать сообщение';
$_lang['bxsender_action_actionSend'] = 'Отправить сообщение в сервис рассылок';
$_lang['bxsender_action_actionState'] = 'Проверить состояние письма';

// Actions
$_lang['bxsender_segment_action_disable'] = 'Отключить подписку';
$_lang['bxsender_segment_action_enable'] = 'Включить подписку';
$_lang['bxsender_segment_action_copy'] = 'Копировать подписку';
$_lang['bxsender_segment_action_update'] = 'Изменить рассылку';
$_lang['bxsender_segment_action_remove'] = 'Удалить рассылку';

// Confirm | COPY
$_lang['bxsender_segment_copy'] = 'Копировать подписку';
$_lang['bxsender_segment_copy_confirm'] = 'Вы уверены что хотите скопировать эту подписаку?';

// Confirm | COPY
$_lang['bxsender_segment_remove'] = 'Удаление рассылок';
$_lang['bxsender_segment_remove_confirm'] = 'Вы уверены что хотите удалить эту рассылку?';


// btn
$_lang['bxsender_subscriber_btn_dispatch'] = 'Проверить не отрпавленные письма';
$_lang['bxsender_subscriber_btn_update_date'] = 'Массовое изменение дат';
$_lang['bxsender_subscriber_btn_sync'] = 'Синхронизация подписчиков с основным сервером';
$_lang['bxsender_subscriber_btn_remove'] = 'Удалить выбранных подписчиков';


$_lang['bxsender_subscriber_btn_create'] = 'Добавить подписчика';
$_lang['bxsender_subscriber_btn_bulk_add_addresses'] = 'Загрузить из MODX';
$_lang['bxsender_subscriber_btn_bulk_add_addresses_csv'] = 'Добавление подписчиков из CSV';


$_lang['bxsender_subscriber_of_users'] = 'Добавить всех активных пользователей';
$_lang['bxsender_subscriber_of_group'] = 'Группа пользователей';
$_lang['bxsender_subscriber_of_group'] = 'Группа пользователей';
$_lang['bxsender_subscriber_of_sendex'] = 'Подписка из Sendex';
$_lang['bxsender_subscriber_of_list'] = 'Добавить в ручную';


$_lang['bxsender_select_sendex_newsletter'] = 'Выбрать подписку';


/**
 ***********************
 * GRID NEWSLETTER
 ***********************
 */
/// window


$_lang['bxsender_segment_btn_create'] = 'Добавить сегмент';

$_lang['bxsender_segments'] = 'Сегменты';
$_lang['bxsender_segments_intro'] = 'Здесь вы можете создать новый сегмент на который возможно подписаться';

$_lang['bxsender_segment_id'] = 'id';
$_lang['bxsender_segment_name'] = 'Название сегмента';
$_lang['bxsender_segment_description'] = 'Описание';
$_lang['bxsender_segment_active'] = 'Включен';
$_lang['bxsender_segment_allow_subscription'] = 'Разрешить подписыватся';
$_lang['bxsender_segment_subscribers'] = 'Подписчиков';
$_lang['bxsender_segment_rank'] = 'Позиция';


$_lang['bxsender_segment_tab_main'] = 'Основное';
$_lang['bxsender_segment_tab_members'] = 'Подписчики';


$_lang['bxsender_segment_err_ae'] = 'Сегмент с таким именем уже существует.';
$_lang['bxsender_segment_err_nf'] = 'Сегмент не найдена.';
$_lang['bxsender_segment_err_ns'] = 'Сегмент не указана.';
$_lang['bxsender_segments_err_ns'] = 'Сегменты не указаны.';
$_lang['bxsender_segment_err_disabled'] = 'Этот сегмент неактивен.';
$_lang['bxsender_segment_err_remove'] = 'Ошибка при удалении сегмента.';
$_lang['bxsender_segment_err_save'] = 'Ошибка при сохранении сегмента.';
$_lang['bxsender_segment_err_no_subscribers'] = 'У этой рассылки нет подписчиков.';
$_lang['bxsender_segment_err_no_template'] = 'У этой рассылки нет шаблона.';
$_lang['bxsender_segment_err_template'] = 'Вы должны выбрать шаблон.';


/**
 ***********************
 * GRID UNSUBSCRIBED
 ***********************
 */
$_lang['bxsender_unsubscribed_subscriber'] = 'Отписать';
$_lang['bxsender_unsubscribed_subscriber_desc'] = 'Выберите подписчика которого необходимо отписать от рассылки';
$_lang['bxsender_unsubscribed_btn_create'] = 'Отписать email от рассылок';
$_lang['bxsender_unsubscribed_subscriber_email'] = 'E-mail';
$_lang['bxsender_unsubscribed_segment_name'] = 'Сегмент';
$_lang['bxsender_unsubscribed_createdon'] = 'Дата отписки';
$_lang['bxsender_unsubscribed_empty_subscribe'] = '<small>не найден</small>';
$_lang['bxsender_unsubscribed_subscriber_err_could_not_found'] = 'Не удалось найти указанную подписку';
$_lang['bxsender_unsubscribed_subscriber_err_email_unscriber'] = 'Этот умаил уже отписан';
$_lang['bxsender_unsubscribed_subscriber_err_already_exists'] = 'Выбранные подписчик уже отписался от рассылки!';


$_lang['bxsender_subscriber_loader_success'] = 'Подписчики успешно добавлены';
$_lang['bxsender_subscriber_loader_failure_segment_id'] = 'Указаная подписка не существует';


$_lang['bxsender_subscribers'] = 'Подписчики';
$_lang['bxsender_subscribers_intro'] = 'Оформленные подписки пользователей';
$_lang['bxsender_subscriber'] = 'Подписчик';

$_lang['bxsender_subscriber_err_ae'] = 'Этот пользователь уже подписан.';
$_lang['bxsender_subscriber_err_nf'] = 'Подписчик не найден.';
$_lang['bxsender_subscriber_err_ns'] = 'Подписчик не указан.';
$_lang['bxsender_subscribers_err_ns'] = 'Подписчики не указаны.';
$_lang['bxsender_subscriber_err_remove'] = 'Ошибка при удалении подписчика.';
$_lang['bxsender_subscriber_err_save'] = 'Ошибка при сохранении подписчика.';
$_lang['bxsender_subscriber_err_email'] = 'Не указан email пописчика.';
$_lang['bxsender_subscriber_err_group'] = 'Вы должны указать группу для добавления подписчиков.';

$_lang['bxsender_subscriber_create'] = 'Создать подписчка';
$_lang['bxsender_subscriber_update'] = 'Изменить подписчика';

$_lang['bxsender_subscriber_id'] = 'id';
$_lang['bxsender_subscriber_username'] = 'Псевдоним';
$_lang['bxsender_subscriber_fullname'] = 'Полное имя';
$_lang['bxsender_subscriber_email'] = 'Email';
$_lang['bxsender_subscribers_remove'] = 'Удалить подписчиков';
$_lang['bxsender_subscribers_remove_confirm'] = 'Вы действительно хотите отписать выбранных пользователей от этой подписки?';


$_lang['bxsender_err_auth_req'] = 'Вы должны быть авторизованы для работы с подписками.';

// Error
$_lang['bxsender_segments_err_ns_back_date'] = 'Запрещено переходить на дату меньше чем дата создания подписки';
$_lang['bxsender_segments_err_ns_forward_date'] = 'Запрещено переходить на дату меньше чем текущая дата';

$_lang['bxsender_action_addqueues'] = 'Добавить собщения в очередь';


$_lang['bxsender_subscriber_replace_fullname'] = 'Заменить имя';
$_lang['bxsender_subscriber_replace_fullname_desc'] = 'В случае если подписка уже сущетсвует, то новое имя получателя будет перезаписано';
$_lang['bxsender_subscriber_replace_user_id'] = 'Заменить id пользователя';
$_lang['bxsender_subscriber_replace_user_id_desc'] = 'Если поле хранящее ID пользователя в базе данных, то вы можете присвоить ID пользователя для подписки';
$_lang['bxsender_subscriber_search_user'] = 'Искать пользователя по E-mail адресу';
$_lang['bxsender_subscriber_search_user_desc'] = 'По указанному E-mail адресу будет найден пользователь в базе, для присвоения владельца подписки';


/**
 ***********************
 * FILE CSV
 ***********************
 */

$_lang['bxsender_subscriber_import_csv_title'] = 'Загрузка подписчиков из CSV';
$_lang['bxsender_subscriber_import_csv_btn'] = 'Загрузить из CSV';
$_lang['bxsender_subscriber_import_csv_msg'] = 'Выберите файл CSV для импорта';
$_lang['bxsender_subscriber_import_csv_btn_upload'] = 'Выбрать';
$_lang['bxsender_subscriber_import_csv_fields'] = 'Поля колонок через запятую (email,fullname)';
$_lang['bxsender_subscriber_import_csv_fields_desc'] = 'Для пропуска колонок, оставляйте запятые (пример: email,,,,fullname)';
$_lang['bxsender_subscriber_import_csv_change'] = 'Файл CSV';
$_lang['bxsender_subscriber_import_csv_offset'] = 'Пропустить строк с верху';
$_lang['bxsender_subscriber_import_csv_fields_empty'] = 'Не указаны поля для импорта';
$_lang['bxsender_subscriber_import_csv_fields_empty_email'] = 'Вы не указали email';
$_lang['bxsender_subscriber_import_csv_fields_empty_fullname'] = 'Вы не указали имя';
$_lang['bxsender_subscriber_import_csv_error'] = 'Ошибка парсинга CSV';
