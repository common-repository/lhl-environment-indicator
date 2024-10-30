<?php

class LHLNVND_email_redirect
{

    public $options;
    public $redirect_enabled = false;
    public $redirect_addr = "";

    public function __construct()
    {
        $this->options = get_option('lhlnvnd_email_options');

        if (!empty($this->options['email_redirect_enable']) && $this->options['email_redirect_enable'] == 'enabled') {
            $this->redirect_enabled = true;
        }

        if (!empty($this->options['email_redirect_addr'])) {
            $this->redirect_addr = $this->options['email_redirect_addr'];
        }
    }

    public function redirect_email(&$phpmailer)
    {

        if ($this->redirect_enabled) {

            $email_addresses = $this->redirect_addr;

            $phpmailer->ClearAllRecipients();

            $email_array = explode(',', $email_addresses);

            foreach ($email_array as $email) {
                $phpmailer->AddAddress(trim(sanitize_email($email)));
            }
        }
    }

    public function modify_email($mail_parts)
    {

        if (!$this->redirect_enabled) {
            return $mail_parts;
        }

        $is_html = !empty($mail_parts['message']) && strstr($mail_parts['message'], '</body>') !== FALSE;

        /**
         * Original Recipients
         */
        $original_recipients = '';
        if (is_array($mail_parts['to'])) {
            $original_recipients = implode(', ', $mail_parts['to']);
        } else {
            $original_recipients = $mail_parts['to'];
        }

        if ($is_html) {
            $redirect_notification = "<br>This Email was Redirected.<br><br><br><hr>Originally sent to: $original_recipients <br><br><hr> ";
            $mail_parts['message'] = str_replace('</body>', $redirect_notification . '</body>', $mail_parts['message']);
        } else {
            $redirect_notification = "This Email was Redirected.";
            $mail_parts['message'] .= "\r\n\r\n
====================================================\r\n
\r\n$redirect_notification\r\n
Originally sent to: $original_recipients";
        }
        return $mail_parts;
    }
}