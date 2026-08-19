<?php

namespace Czebra\Smtp;

use Bitrix\Main\Config\Option;

class Mail
{
    private $connection;
    private $clientHost;
    private $from;
    private $psw;
    private $host;
    private $port;

    const EOL = "\r\n";

    public function __construct()
    {
        $this->from = Option::get("czebra.smtp", "from", "");
        $this->psw = Option::get("czebra.smtp", "psw", "");
        $this->host = Option::get("czebra.smtp", "host", "");
        $this->port = Option::get("czebra.smtp", "port", "");
    }

    public function Send($to, $subject, $message, $additional_headers, $additional_parameters)
    {
        if ($this->from == "" || $this->psw == "" || $this->host == "" || $this->port == "") {
            return false;
        }

        try {
            if (!$this->connection) {
                $this->connect();
            }

            $this->write("MAIL FROM:<$this->from>", 250);
            $this->write("RCPT TO:<$to>", [250, 251]);

            preg_match('/CC: (.+)\n/i', $additional_headers, $matches);
            list(, $strCC) = $matches;
            if (strlen($strCC) > 0) {
                $arCC = preg_split("/,/", $strCC);
                foreach ($arCC as $cc) {
                    $this->write("RCPT TO:<$cc>", [250, 251]);
                }
            }

            preg_match('/BCC: (.+)\n/i', $additional_headers, $matches);
            list(, $strBCC) = $matches;
            if (strlen($strBCC) > 0) {
                $arBCC = preg_split("/,/", $strBCC);
                foreach ($arBCC as $bcc) {
                    $this->write("RCPT TO:<$bcc>", [250, 251]);
                }
            }

            $additional_headers = preg_replace("/From: (.*?)\n/", "From: " . $this->from . "\n", $additional_headers);
            $additional_headers = preg_replace("/Reply-To: (.*?)\n/", "Reply-To: " . $this->from . "\n", $additional_headers);
            $additional_headers .= "\nSubject: " . $subject . "\n";

            $this->write('DATA', 354);
            $this->write($additional_headers);
            $this->write($message);
            $this->write('.', 250);
            $this->write('QUIT', 221);
            $this->disconnect();
        } catch (\Exception $e) {
            if ($this->connection) {
                $this->disconnect();
            }
            return false;
        }

        return true;
    }

    protected function connect()
    {
        $this->clientHost = isset($_SERVER['HTTP_HOST']) && preg_match('#^[\w.-]+\z#', $_SERVER['HTTP_HOST'])
        ? $_SERVER['HTTP_HOST']
        : 'localhost';

        $opt_timeout = 20;

        $this->connection = @stream_socket_client('ssl://' . $this->host . ':' . $this->port,
            $errno, $error, $opt_timeout, STREAM_CLIENT_CONNECT, stream_context_get_default()
        );
        if (!$this->connection) {
            throw new \Exception($error ?: error_get_last()['message'], $errno);
            return false;
        }

        stream_set_timeout($this->connection, $opt_timeout, 0);
        $this->read();
        $this->write("EHLO $this->clientHost");
        $ehloResponse = $this->read();
        if ((int) $ehloResponse !== 250) {
            $this->write("HELO $this->clientHost", 250);
        }

        if ($this->from != null && $this->psw != null) {
            $authMechanisms = [];
            if (preg_match('~^250[ -]AUTH (.*)$~im', $ehloResponse, $matches)) {
                $authMechanisms = explode(' ', trim($matches[1]));
            }

            if (in_array('PLAIN', $authMechanisms, true)) {
                $credentials = $this->from . "\0" . $this->from . "\0" . $this->psw;
                $this->write('AUTH PLAIN ' . base64_encode($credentials), 235, 'PLAIN credentials');
            } else {
                $this->write('AUTH LOGIN', 334);
                $this->write(base64_encode($this->from), 334, 'username');
                $this->write(base64_encode($this->psw), 235, 'password');
            }
        }
    }

    protected function write($line, $expectedCode = null, $message = null)
    {
        fwrite($this->connection, $line . self::EOL);
        if ($expectedCode) {
            $response = $this->read();
            if (!in_array((int) $response, (array) $expectedCode, true)) {
                throw new \Exception('SMTP server did not accept ' . ($message ? $message : $line) . ' with error: ' . trim($response));
            }
        }
    }

    protected function read()
    {
        $s = '';
        while (($line = fgets($this->connection, 1000)) != null) {
            $s .= $line;
            if (substr($line, 3, 1) === ' ') {
                break;
            }
        }
        return $s;
    }

    protected function disconnect()
    {
        fclose($this->connection);
        $this->connection = null;
    }

}
