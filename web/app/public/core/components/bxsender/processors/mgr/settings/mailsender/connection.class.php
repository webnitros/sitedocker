<?php

class bxMailSenderConnectionProcessor extends modProcessor
{
    /**
     * @return array|string
     */
    public function process()
    {
        /* @var bxSender $bxSender */
        $bxSender = $this->modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');

        if (!$MailSender = $bxSender->loadMailSender()) {
            return 'Error load class MailSender';
        }


        $this->modx->lexicon->load('bxsender:manager');


        /* @var modPHPMailer $mail */
        $mail = $this->modx->getService('mail', 'mail.modPHPMailer');

        $email = $this->getProperty('email');

        $MailSender->transporter($mail);


        // add message content
        $mail->address('to', $email);
        $mail->set(modMail::MAIL_SUBJECT, $this->modx->lexicon('bxsender_settings_mailsender_testing_message_email_subject'));
        $mail->set(modMail::MAIL_BODY, $this->modx->lexicon('bxsender_settings_mailsender_testing_message_email_body'));


        if (!$mail->send()) {
            return $this->failure($mail->mailer->ErrorInfo);
        }

        return $this->success($this->modx->lexicon('bxsender_settings_mailsender_testing_message_email_success'));
    }
}

return 'bxMailSenderConnectionProcessor';