<?php
// Файлы phpmailer
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

# проверка, что ошибки нет
if (!error_get_last()) {

    // Переменные, которые отправляет пользователь
    
    $name = $_POST['name'] ;
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $msg = $_POST['msg'];
    

    
    // Формирование самого письма
    $title = "Заявка с Фестиваля Искусства";
    $body = "
    <h2>Новая заявка</h2>
    <b>Имя:</b> $name<br>
    <b>Электронная почта:</b> $email<br><br>
    <b>Номер телефона:</b><br>$phone
    <br><br>
    <b>Сообщение:</b><br>$msg";
    
    // Настройки PHPMailer
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    
    $mail->isSMTP();   
    $mail->CharSet = "UTF-8";
    $mail->SMTPAuth   = true;
    //$mail->SMTPDebug = 2;
    $mail->Debugoutput = function($str, $level) {$GLOBALS['data']['debug'][] = $str;};
    
    // Настройки  почты
    $mail->Host       = 'smtp.mail.ru'; 
    $mail->Username   = 'fest_dali@mail.ru'; 
    $mail->Password   = 'ZraZcfJqSs1t5i4iGkp8'; 
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;;
    $mail->setFrom('fest_dali@mail.ru', 'fest_dali@mail.ru'); 
    
    // Получатель письма
    $mail->addAddress('info@festdali.ru');  
    
    // Отправка сообщения
    $mail->isHTML(true);
    $mail->Subject = $title;
    $mail->Body = $body;    
    // Проверяем отправленность сообщения
    if (preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i", $email) && preg_match("/^\+7\d{10}$/", $phone) ) {
        if ($mail->send()) {
            $data['result'] = "success";
            $data['info'] = "Сообщение успешно отправлено!";
        } else {
            $data['result'] = "error";
            $data['info'] = "Сообщение не было отправлено. Произошла ошибка";
            $data['desc'] = "Причина ошибки: {$mail->ErrorInfo}";
        }
        
    } else {
        $data['result'] = "error";
        $data['info'] = "Ошибка! Проверьте правильность заполенных данных";
        $data['desc'] = error_get_last();
    }
    }

// Отправка результата
header('Content-Type: application/json');
echo json_encode($data);

?>