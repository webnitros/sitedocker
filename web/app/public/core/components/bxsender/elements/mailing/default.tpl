<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>navodki.ru</title>
    <style type="text/css">
        body {
            background: #f1f1f1;
            margin: 0 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: Arial, serif;
            font-size: 14px;
            color: #293034;
        }

        table {
            border-spacing: 0;
            width: 100%;
        }

        table td {
            margin: 0;
        }

        body > table {
            width: 600px;
            margin: auto;
        }

        a {
            color: #22af75;
            outline: none;
            text-decoration: none;
        }

        p {
            font-size: 16px;
            line-height: 22px;
        }

        h1, .h1 {
            font-size: 28px;
            margin: 0 0 20px 0;
            font-weight: normal;
        }

        h1.no-margin {
            margin: 0;
        }

        .h3 {
            font-size: 20px;
            font-weight: normal;
            color: #000;
        }

        small {
            font-size: 14px;
            color: #999999;
        }

        .logo {
        }

        .logo {
            padding: 20px 0;
            text-align: center;
        }

        .logo img {
            width: 200px;
            height: 40px;
            border: 0;
        }

        hr {
            margin-top: 20px;
            margin-bottom: 20px;
            border: 0;
            border-top: 1px solid #eee;
        }

        .footer td {
            padding: 35px 0;
        }

        .footer .left {
            width: 150px;
            padding-left: 30px;
        }

        .footer .center a {
            vertical-align: middle;
            width: 30px;
            height: 30px;
            display: inline-block;
        }

        .footer .center img {
            width: 30px;
            height: 30px;
        }

        .footer .right {
            text-align: right;
            text-transform: uppercase;
        }

        .footer .right a {
            color: #999999;
            font-weight: bold;
            padding-right: 30px;
        }

        pre {
            background-color: #f1f1f1;
            padding: 0px 15px;
            border-radius: 5px;
        }

        pre h3 {
            margin: 0;
        }

        .content_td {
            vertical-align: top;
            background: #ffffff;
            border: 1px solid rgba(98, 98, 98, 0.12);
            padding: 25px 25px 25px 25px;
        }
    </style>
</head>
<body>
<table style="width: 600px; margin: auto;">
    <thead>
    <tr>
        <td class="logo">
            <a href="{$site_url}" target="_blank">
                <img src="{$site_url}assets/components/bxsender/images/logo.png" alt="{'site_name' | config}"/>
            </a>
        </td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style="height: 100px; vertical-align: top; background: #ffffff;  border: 1px solid rgba(98, 98, 98, 0.12); padding: 25px 25px 5px 25px;">
            <h1>{block 'title'}Демонстрация шаблона письма{/block}</h1>
            {block 'content_text'}
            <p>Уважаемый <b>{$subscriber_fullname}</b> заходите на наш сайт <a href="{$site_url}">{$site_name}</a> и
                подписывайтесь на интересные рассылки, мы еженедельном будем напоминать о наших новостях</p>
            {/block}

        </td>
    </tr>
    <tr>
        <td>
            <img width="600" src="{$site_url}assets/components/bxsender/images/stub.jpg"
                 alt="Доступ всем зарегистрированным пользователям">
        </td>
    </tr>
    <tr>
        <td class="content_td">
            <h2>CSS</h2>
            Верстку шаблона можно дедать через CSS, прописывать все стили в шаблоне добавлять классы к нужным тегам
            <pre>
    body {
        background: #f1f1f1;
        margin: 0 0;
        padding: 0;
        width: 100%;
        height: 100%;
        font-family: Arial, serif;
        font-size: 14px;
        color: #293034;
    }

            </pre>

            после рединга шаблона к body добавиться:<br> <b>style="background: #f1f1f1; margin: 0 0; padding: 0; width:
            100%; height: 100%; font-family: Arial, serif; font-size: 14px; color: #293034;"</b>


        </td>
    </tr>
    <tr>
        <td class="content_td">
            <h2>Шаблоны</h2>
            <p>Полная поддержка Fenom и тегов MODX</p>
            <pre>

# Этот шаблон вы можете найти в директории
core/components/bxsender/elements/mailing/default.tpl
            </pre>
        </td>
    </tr>
    <tr>
        <td  class="content_td">
            С уважением администрация сайта <b>{'site_name' | config}</b>
        </td>
    </tr>
    </tbody>
</table>
<table style="width: 600px; margin: auto;">
    <tr>
        <td align="center">
            <p style="color: #999">
                <a style="font-size: 12px; color: #999999" href="{$unsubscribe_page}">Отписаться от рассылки</a> |
                <a style="font-size: 12px; color: #999999" href="{$subscribe_manager_page}">Управление
                    подпиской</a><br>
                <a href="{$open_browser_link}" style="font-size: 12px; color: #999999">Открыть в браузере</a>
            </p>
        </td>
    </tr>
</table>
<div>
</div>
{$imageviewcount}
</body>
</html>