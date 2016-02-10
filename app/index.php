<?php

require_once __DIR__.'/../vendor/autoload.php';

require_once 'smtpmail/classes/class.phpmailer.php';

/**
 * Class teleport site
 */
class iMegaTeleportSite
{
    protected $description = 'Migrate Интеграция интернет-магазина 1С 1C WooCommerce синхронизация данных wordpress template viper import ecommerce e-commerce commerce woothemes ecommerce affiliate store sales sell shop shopping cart checkout configurable variable widgets reports download downloadable digital inventory stock reports shipping tax';
    
    protected $error = false;

    protected $filePromo = 'promo.html';

    protected $fileContactus = 'contactus.html';

    protected $fileDonate = 'donate.html';

    protected $fileDone = 'done.html';

    protected $fileDownload = 'download.html';

    protected $fileFeedback = 'feedback.html';

    protected $fileFish = 'fish.html';

    protected $fileFooter = 'footer.html';

    protected $fileHeader = 'header.html';

    protected $fileIndex = 'index1.html';

    protected $fileInstructions = 'instructions.html';

    protected $fileStyle = 'style.css';

    protected $fishPatterns = array();

    protected $title = 'iMegaTeleport взаимосвязь интернет-магазина и 1С';

    protected $url = 'http://teleport.imega.ru';

    /**
     * Конструктор
     */
    function __construct ()
    {
        set_error_handler(
                array(
                    $this,
                    'errorHandler'));
        if (isset($_GET['q'])) {
            echo $this->loadFile($this->filePromo);
        }
        $this->route();
        
        $this->template('header', $this->header());
        $this->template('style', $this->style());
        $this->template('footer', $this->footer());
        echo $this->fish($this->fishPatterns);
    }

    /**
     * Обработка ошибок
     */
    function errorHandler ($errno, $errstr, $errfile, $errline)
    {
        $this->error = $errno . $errstr . $errfile . $errline;
    }

    /**
     * contactus page
     */
    function contactus ()
    {
        return $this->loadFile($this->fileContactus);
    }

    /**
     * Donate page
     */
    function donate ()
    {
        return $this->loadFile($this->fileDonate);
    }

    /**
     * Done page
     */
    function done ()
    {
        return $this->loadFile($this->fileDone);
    }

    /**
     * Download page
     */
    function download ()
    {
        return $this->loadFile($this->fileDownload);
    }

    /**
     * feedback page
     */
    function feedback ()
    {
        return $this->loadFile($this->fileFeedback);
    }

    /**
     * Fish page
     *
     * @param array $patterns            
     * @return string
     */
    function fish ($patterns)
    {
        $fish = $this->loadFile($this->fileFish);
        $patterns['title'] = $this->title;
        $patterns['description'] = $this->description;
        $this->patterns($fish, $patterns);
        return $fish;
    }

    /**
     * Footer page
     */
    function footer ()
    {
        $pattern['url'] = $this->url;
        $footer = $this->loadFile($this->fileFooter);
        $this->patterns($footer, $pattern);
        return $footer;
    }

    /**
     * Header page
     */
    function header ()
    {
        $pattern['url'] = $this->url;
        $header = $this->loadFile($this->fileHeader);
        $this->patterns($header, $pattern);
        return $header;
    }

    /**
     * Index page
     */
    function index ()
    {
        return $this->loadFile($this->fileIndex);
    }

    /**
     * Instructions page
     */
    function instructions ()
    {
        return $this->loadFile($this->fileInstructions);
    }

    /**
     * Загружает файл с текущей директори плагина
     *
     * @param string $filename            
     * @return string
     */
    function loadFile ($filename)
    {
        if ($this->error) {
            return;
        }
        $text = file_get_contents(__DIR__ . "/{$filename}");
        return $text;
    }

    /**
     * Send mail
     */
    function mail ($name, $email, $phone, $subject, $message)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 465;
        $mail->Username = "baks@imega.ru";
        $mail->Password = "%2UJiDGx3<xTAf^HyRp,";
        $mail->SetFrom($email);
        $mail->Subject = "iMegaTeleport. " . $subject;
        $mail->Body = "Это письмо отправлено с сайта http://teleport.imega.ru" .
                 "\n\nАвтор: " . $name . " ($ip)\nEmail: " . $email . "\nPhone: " .
                 $phone . "\nТема: " . $subject . "\n\n" . $message;
        $mail->AddAddress("info@imega.ru");
        $mail->Send();
    }

    /**
     * Patterns
     *
     * @param string $content            
     * @param array $patterns            
     * @return void
     */
    function patterns (&$content, $patterns)
    {
        foreach ($patterns as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }
    }

    /**
     * Маршруты
     */
    function route ()
    {
        $q = '';
        
        if (isset($_GET['q'])) {
            $q = $_GET['q'];
        }
        
        switch ($q) {
            case 'contactus':
                $this->title = 'Контакты - ' . $this->title;
                $this->template('page', $this->contactus());
                break;
            case 'donate':
                $this->title = 'Пожертвование - ' . $this->title;
                $this->template('page', $this->donate());
                break;
            case 'done':
                $this->title = 'Отправлено - ' . $this->title;
                
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $p = $_POST;
                    if (isset($p['name']))
                        $name = $p['name'];
                    if (isset($p['email']))
                        $email = $p['email'];
                    if (isset($p['phone']))
                        $phone = $p['phone'];
                    if (isset($p['subject']))
                        $subject = $p['subject'];
                    if (isset($p['message']))
                        $message = $p['message'];
                    $this->mail($name, $email, $phone, $subject, $message);
                    header('Location: ' . $this->url . '/done');
                }
                $this->template('page', $this->done());
                
                break;
            case 'download':
                $this->title = 'Загрузить - ' . $this->title;
                $this->template('page', $this->download());
                break;
            case 'feedback':
                $this->title = 'Обратная связь - ' . $this->title;
                $this->template('page', $this->feedback());
                break;
            case 'instructions':
                $this->title = 'Инструкции - ' . $this->title;
                $this->template('page', $this->instructions());
                break;
            case 'index':
                $this->template('page', $this->index());
        }
    }

    /**
     * Styles
     */
    function style ()
    {
        return $this->loadFile($this->fileStyle);
    }

    /**
     * Insert template
     *
     * @param string $key            
     * @param string $value            
     * @return iMegaTeleportSite
     */
    function template ($key, $value)
    {
        $this->fishPatterns[$key] = $value;
        return $this;
    }
}

$iMegaTeleportSite = new iMegaTeleportSite();