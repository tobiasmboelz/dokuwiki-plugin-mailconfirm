<?php
/**
 * Send confirmation mail action for DokuWiki plugin bureaucracy
 */

class helper_plugin_mailconfirm_mailconfirm extends helper_plugin_bureaucracy_action {

    /**
     * Send a confirmation mail
     *
     * @param helper_plugin_bureaucracy_field[] $fields
     * @param string                            $thanks
     * @param array                             $argv
     * @return string thanks message
     * @throws Exception mailing failed
     */
    public function run($fields, $thanks, $argv) {
        global $ID;
        global $conf;

        list($to, $subject, $body) = $argv;

        $mail = new Mailer();

        $this->prepareNamespacetemplateReplacements();
        $this->prepareDateTimereplacements();
        $this->prepareLanguagePlaceholder();
        $this->prepareNoincludeReplacement();
        $this->prepareFieldReplacements($fields);

        $mail->from($conf['mailfrom']);

        $to = $this->replace($to);
        $mail->cleanAddress($to);
        $mail->to($to);

        $subject = $this->replace($subject);
        $mail->subject($subject);

        $body = str_replace('\\r\\n', "\r\n", $body);
        $body = str_replace('\\n', "\r\n", $body);
        $body = $this->replace($body);
        $mail->setBody($body);

        if(!$mail->send()) {
            throw new Exception($this->getLang('e_mail'));
        }
        return $thanks;
    }
}
// vim:ts=4:sw=4:et:enc=utf-8:
