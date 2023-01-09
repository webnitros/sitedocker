<?php
$_lang['bxsender_fn_subscribe_err_name_empty'] = 'Вы не заполнили имя';
$_lang['bxsender_fn_subscribe_err_email_empty'] = 'Вы не заполнили email';
$_lang['bxsender_fn_subscribe_err_segments_empty'] = 'Выберите хотябы одну рассылку для подписки';
$_lang['bxsender_fn_subscribe_err_form_message'] = 'Ошибки в форме';
$_lang['bxsender_fn_subscribe_err_email'] =  'E-mail адрес указан не верно';
$_lang['bxsender_fn_subscribe_err_send_message'] = 'Не удалось отправить сообщение';
$_lang['bxsender_fn_subscribe_success'] = 'Письмо для активации подписки отправлено на указанный E-mail';


$_lang['bxsender_fn_subscribe_confirm_subject'] = 'Подтверждение подписки на E-mail';
$_lang['bxsender_fn_subscribe_err_next_slots'] = 'Мы недавно отправили сообщение для подтверждения E-mail адреса. Проверьте почту. Следущая попытка подписаться возможна только через [[+minutes]] минут';
$_lang['bxsender_fn_subscribe_err_is_subscribe'] = 'На указанный e-mail адрес уже создана подписка. Для настройки подписки перейдите в раздел управление подписками';

$_lang['bxsender_fn_confirmationemail_err_empty_subscribe'] = 'Не удалост получить подписку с указанным HASH';
$_lang['bxsender_fn_confirmationemail_err'] = 'Произошла ошибка во время активации E-mail адреса. Обрабтитесь к администрации сайта для решения этой проблемы.';
$_lang['bxsender_fn_confirmationemail_err_log'] = 'Указанный hash "[[+hash]]" не существует ';
$_lang['bxsender_fn_confirmationemail_subscribe_success'] = 'Подписка успешно активирована!<br> Перейти к <a href="[[+link]]">управлению подпиской</a>';
$_lang['bxsender_fn_confirmationemail_is_subscribe_success'] = 'Вы уже активировали подписку!<br> Перейти к <a href="[[+link]]">управлению подпиской</a>';
$_lang['bxsender_fn_confirmationemail_is_not_subscribe_success'] = 'У вас уже созданая подписка но она не активирована. Перейдите в раздел управления подписками для настройки вашей подписки';

/**
 *********
 * unsubscribe
 *********
 */
$_lang['bxsender_fn_unsubscribe_err_hash'] = 'Указанный хеш не существует или в нем содержатся ошибки';

/**
 *********
 * manager
 *********
 */

$_lang['bxsender_fn_manager_err_access_closed'] = 'У вас нету доступа к подписке';
$_lang['bxsender_fn_manager_err_not_found_subscribe'] = 'Подписка не найдена';
$_lang['bxsender_fn_manager_err_confirm_subscribe_email'] = 'Вам необходимо подтвердить email адрес для управления подписокй';
$_lang['bxsender_fn_manager_err_no_action_segment'] = 'Нет активных рассылок для оформления подписки';
$_lang['bxsender_fn_manager_err_subscriber_id'] = 'Не указан ID подписки';
$_lang['bxsender_fn_manager_err_fullname'] = 'Должен содержать только Русские или Английские буквы';
$_lang['bxsender_fn_manager_err_status'] = 'Статус закупки может быть только subscribe или unsubscribed';
$_lang['bxsender_fn_manager_err_change_segment'] = 'Выберите хотябы одну рассылку для подписки. Либо выбирете статус "Отписан".';
$_lang['bxsender_fn_manager_err_msg'] = 'Произошла ошибка во время сохранения';
$_lang['bxsender_fn_manager_err_sub_not_found'] = 'Указанная подписка не найдена';
$_lang['bxsender_fn_manager_err_save'] = 'Не удалось сохранить подписку';
$_lang['bxsender_fn_manager_msg_success'] = 'Подписка успешно сохранена';
$_lang['bxsender_fn_manager_msg_success_unsubscribe'] = 'Вы успешно отписались от подписки';
$_lang['bxsender_fn_manager_err_token'] = 'Указан не верный токен';

/**
 *********
 * subscribe
 *********
 */

$_lang['bxsender_fn_subscribe_err_get_hash'] = 'Не удалось получить HASH подписки';
$_lang['bxsender_fn_subscribe_err_message'] = 'Произошла ошибка во время подписки';
$_lang['bxsender_fn_subscribe_confirmation_err_hash_valid'] = 'Вы использоваль не верных hash или email адрес для активации подписки';
$_lang['bxsender_fn_subscribe_confirmation_err_hash_valid_get'] = 'Указан не правильный hash';
$_lang['bxsender_fn_subscribe_confirmation_err_email_empty'] = 'email адрес для активации не указан';
$_lang['bxsender_fn_subscribe_confirmation_err_hash_empty'] = 'hash не указан';
$_lang['bxsender_fn_subscribe_err_save'] = 'Подписка для автивированного пользователя создана';
$_lang['bxsender_fn_subscribe_create'] = 'Не удалось сохранить пользователя';

/**
 *********
 * restore
 *********
 */
$_lang['bxsender_fn_subscribe_restore_error_messege'] = 'Произошла ошибка';
$_lang['bxsender_fn_subscribe_restore_success'] = 'Письмо для восстановления доступа успешно отправлено!';
$_lang['bxsender_fn_subscribe_restore_err_email'] = 'Подписка с указанным e-mail адресом не найдена';
$_lang['bxsender_fn_subscribe_restore_err_send_message'] = 'Не удалось отправить сообщение на e-mail [[+email]]. Попробуйте повторить позже или обратитесь к администрации сайта';
$_lang['bxsender_fn_subscribe_restore_err_next_slots'] = 'Мы недавно отправили сообщение для восстановления доступа к подписке. Проверьте почту. Следущая попытка подписаться возможна только через [[+minutes]] минут';
$_lang['bxsender_fn_subscribe_restore_success'] = 'Сообщение для восстановления доступ к подписке отправлено';