<?php
require '../vendor/autoload.php';

/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.12.17
 * Time: 12:56
 */
class Mailer
{    public $attached_files = array();
    public $title = '';
    public $to;
    public $body = '';
    public $errors = null;
    public $from;
    private $mailer;

    /**
     * Инициилизация mailer
     */
    public function init($params)
    {
        $this->from = $params['from'];
        $transport = Swift_SmtpTransport
            ::newInstance($params['host'])
            ->setPort($params['port'])
            ->setAuthMode($params['auth'])
            ->setLocalDomain($params['domain'])
            ->setUsername($params['user'])
            ->setPassword($params['pass']);
        $this->mailer = Swift_Mailer::newInstance($transport);

    }

    /**
     * mailer
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Формирование сообщения
     * Прикрепление вложений
     * Отправление сообщения
     * @return bool
     */
    public function send()
    {
        $message = Swift_Message::newInstance($this->title)
            ->setFrom($this->from)
            ->setTo($this->to)
            ->setBody($this->body);

        if (!empty($this->attached_files)) {
            foreach ($this->attached_files as $filename) {
                $message->attach(Swift_Attachment::fromPath($filename));
            }

        }

        return($this->mailer->send($message));

    }

    /**
     * Отправление сообщения
     * @param $title
     * @param $to
     * @param string $body
     * @param array $files
     */
    public static function sendMessage($title, $to, $body = '', $files = array())
    {

        $mail = new self();
        $mail->title = $title;
        $mail->to = $to;
        $mail->body = $body;
        $mail->attached_files = $files;
        $response = $mail->send();
        return $response;

    }

}