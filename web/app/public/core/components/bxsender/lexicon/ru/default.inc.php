<?php

include_once 'setting.inc.php';

$_lang['bxsender'] = 'bxSender';
$_lang['bxsender_menu_desc'] = 'Управление подписками';

$_lang['bxsender_triggers'] = 'Триггеры';
$_lang['bxsender_menu_triggers_desc'] = 'Управление триггерами';

$_lang['bxsender_segments'] = 'Подписки';
$_lang['bxsender_segment'] = 'Подписка';
$_lang['bxsender_segments_intro'] = 'На этой странице вы создаёте и редактируете ваши рассылки.';

$_lang['bxsender_queues'] = 'Очередь';
$_lang['bxsender_menu_queues_desc'] = 'Список сообщений находящихся в очереди';

$_lang['bxsender_subscriptions'] = 'Подписчики и Сегменты';
$_lang['bxsender_menu_subscriptions_desc'] = 'управление подписчиками';

$_lang['bxsender_sendings'] = 'Рассылка';
$_lang['bxsender_sendings_desc'] = 'Рассылки, Отчеты, Настройки';

$_lang['bxsender_settings'] = 'Настройки';
$_lang['bxsender_menu_settings_desc'] = 'Отправители, Обратные пути';

$_lang['bxsender_subscribers'] = 'Подписчики';
$_lang['bxsender_subscribers_intro'] = 'Оформленные подписки пользователей';

$_lang['bxsender_btn_create'] = 'Создать';
$_lang['bxsender_btn_subscribe'] = 'Подписаться!';
$_lang['bxsender_btn_unsubscribe'] = 'Отписаться';
$_lang['bxsender_btn_remove_all'] = 'Удалить все';

$_lang['bxsender_select_user'] = 'Добавить пользователя';
$_lang['bxsender_select_group'] = 'Добавить группу';
$_lang['bxsender_select_segment'] = 'Добавить письма в очередь рассылки';
$_lang['bxsender_select_segment_reset'] = 'Сбросить даты отправки';

$_lang['bxsender_segments_remove'] = 'Удалить подписки';
$_lang['bxsender_segments_remove_confirm'] = 'Вы уверены, что хотите удалить выбранные подписки?';
$_lang['bxsender_segment_create'] = 'Создать подписку';
$_lang['bxsender_segment_update'] = 'Изменить подписку';


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


// Confirm
$_lang['bxsender_subscriber_all_dispatch_confirm'] = 'Вы уверены что хотите проверить все подписки?';
$_lang['bxsender_subscriber_all_back_confirm'] = 'Вы уверены что хотите откатить даты у выбранной подписки на сутки назад?';
$_lang['bxsender_subscriber_all_stream_confirm'] = 'Вы уверены что хотите отправить сообщения в очередь по выбранной подписки?';

// actions
$_lang['bxsender_action_streamSubscriber'] = 'Разрешить создавать сообщени?';
$_lang['bxsender_action_showMessage'] = 'Показать информацию';
$_lang['bxsender_action_testingSubscriber'] = 'Создать тестовое сообщение';

$_lang['bxsender_action_backSubscriber'] = 'Установить предыдущую дату';
$_lang['bxsender_action_forwardSubscriber'] = 'Установить следующую дату';


$_lang['bxsender_action_actionQuery'] = 'Создание письма';
$_lang['bxsender_action_actionContent'] = 'Показать сообщение';
$_lang['bxsender_action_actionSend'] = 'Отправить сообщение в сервис рассылок';
$_lang['bxsender_action_actionState'] = 'Проверить состояние письма';

// state
$_lang['bxsender_mandrill_state_rejected'] = 'Попадание в черный список';
$_lang['bxsender_mandrill_state_rejected_desc'] = 'Когда получатель отскакивает или помещает ваше сообщение в качестве спама, Mandrill отказывается отправлять больше электронной почты этому получателю в течение определенного периода времени, исходя из того, сколько раз получатель получал или жаловался, и насколько серьезной была проблема. Вы можете удалить людей из своего черного списка, но будьте осторожны. Удаление электронных писем из вашего черного списка приведет к снижению вашей репутации, если вы сделаете это слишком много, что может привести к тому, что Mandrill начнет более активно регулировать вашу отправку.';

$_lang['bxsender_mandrill_state_sent'] = 'Сообщение отправлено';
$_lang['bxsender_mandrill_state_sent_desc'] = '';

$_lang['bxsender_mandrill_state_error'] = 'Ошибка отправки сообщения';
$_lang['bxsender_mandrill_state_error_desc'] = '';

$_lang['bxsender_mandrill_state_undeliverable'] = 'Ошибка доставки';
$_lang['bxsender_mandrill_state_undeliverable_desc'] = '';

$_lang['bxsender_mailing_name_modx'] = 'Сообщения из MODX (Технический)';

// Error
$_lang['message_err_save'] = 'Произошла ошибка во время добавления сообщения в очередь. Возможно сообщение уже находится в очереди.';
$_lang['bxsender_segments_err_ns_back_date'] = 'Запрещено переходить на дату меньше чем дата создания подписки';
$_lang['bxsender_segments_err_ns_forward_date'] = 'Запрещено переходить на дату меньше чем текущая дата';

$_lang['err_invalid_string'] = 'Ввод только латиницы и букв';
$_lang['err_invalid_email'] = 'E-mail адрес указан не верно';
$_lang['preventBlank'] = 'Это поле обязательно для заполнения.';
$_lang['bxMailing_err_save'] = 'Произошла ошибка во время сохранения';
$_lang['bxsender_validator_err_hash_queue'] = 'Cообщение уже создано и находится в очереди для отправки';
$_lang['bxsender_validator_err_subject'] = 'Укажите тему письма';
$_lang['bxsender_validator_err_message'] = 'Укажите текст шаблона';


$_lang['bxsender_mailing_message_desc'] = '
<h3>Плейсхолдеры подписки и пользователя</h3>
<b>{$subscriber_fullname}</b> - имя получателя <br>
<b>{$subscriber_email}</b> - email получателя<br>
<b>{$user_id}</b> - id пользователя<br>
<b>{$username}</b> - имя пользователя<br>
<b>{$profile}</b> - профиль пользователя<br>
<br>
<h3>Плейсхолдеры сайта</h3>
<b>{$emailsender}</b> - email сайта<br>
<b>{$site_url}</b> - URL сайта (http://site.ru)<br>
<b>{$assets_url}</b> - URL к активам (http://site.ru/assets)<br>
<b>{\'site_name\' | option}</b> - имя сайта и другие опции<br><br>

<h3>Плейсхолдеры технические</h3>
<b>{$unsubscribe_page}</b> - ссылка на страницу быстрой отписки<br>
<b>{$subscribe_manager_page}</b> - ссылка на страницу управление подписками<br>
<b>{$open_browser_link}</b> - ссылка открыть в браузере<br>
<b>{$imageviewcount}</b> - учет прочтения собщения<br>
<b>{$queue_hash}</b> - hash сообщения<br>
<b>{$utm_enable}</b> - Включение UTM (true|false)<br>
<b>{$utm_source}</b> - UTM Источник (string)<br>
<b>{$utm_medium}</b> - UTM Канал (string)<br>
<b>{$utm_campaign}</b> - UTM Компания (string)<br>
<b>{$mailing_service}</b> - Сервис рассылки (bxsender)<br>
<br>
<h3>Шаблоны сообщений</h3>
<em>Директория расположения шаблонов core/elements/</em>

<br><br>
<b>Текстовое сообщение</b><br>
<pre>
Здравствуйте {$subscriber_fullname} мы отправили вам письмо, 
так как ваш емаил адрес {$subscriber_email} подписан на рассылку!
</pre><br>

<b>Подключение шаблона</b><br>
<pre>
{include \'file:mailing/template1/index.tpl\'}
</pre>
<br>

<b>Подключение шаблона с расширением блоков</b><br>
<pre>
{extends \'file:mailing/template2/default.tpl\'}
{block \'title\'}Новый заголовок{/block}
</pre>
';


$_lang['bxsender_mailing_error_content_template'] = '<html><head><title>ОШИБКА</title><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="/assets/components/bxsender/css/web/main.css" type="text/css"></head>
<body class="bxsender_body"><div class="bxsender_form"><h3 class="bxsender_error_red">Не удалось получить шаблон рассылки</h3>Возможно шаблон не существует.<br><br><pre class="bxsender_error_pre">[[+content]]</pre></div></body></html>';

$_lang['bxsender_minishop_tab_title'] = 'Mail лог';
