<?php
function validateName($name): array{
    if(preg_match('/[@#!$%^\/()]{1,}/', $name)){
        return [1, 'Ошибка: В имени пользователя есть специальные символы'];
    }

    return [0, 'Имя валидно'];
}

function validateEmail($email): array{
    if(!preg_match('/[a-z0-9._-]+@[a-z]{3,}\.[a-z]{2,3}/', $email)){
        return [1, 'Ошибка: Электронная почта пользователя не соответствует стандарту'];
    }

    return [0, 'Почта валидна'];
}

function validateChangingPassword($newPassword, $confirmPassword){
    if($newPassword != $confirmPassword){
        return [1, 'Ошибка: Пароли не совпадают'];
    }
    if($newPassword == ''){
        return [1, 'Ошибка: Пароль не может быть пустой строкой'];
    }

    return [0, 'Изменение пароля возможно'];
}

// only use with $_FILES['imagename']
function validateMedia($img, $to): array{
    if ($img['error'] > 0) {
        $str = 'Ошибка: ';
        switch ($img['error']) {
            case 1: $str .= 'Размер файла больше upload_max_filesize';
            break;
            case 2: $str .= 'Размер файла больше max_file_size';
            break;
            case 3: $str .= 'Загружена только часть файла';
            break;
            case 4: $str .= 'Файл не загружен';
            break;
            case 6: $str .= 'Загрузка невозможна: не задан временный каталог';
            break;
            case 7: $str .= 'Загрузка не выполнена: невозможна запись на диск';
            break;
        }
        return [1, $str];
    }

    if (!preg_match('/image\//', $img['type']) && !preg_match('/video\//', $img['type'])){
        return [1, 'Ошибка: Файл не является медиа-контентом'];
    }

    if (is_uploaded_file($img['tmp_name'])) {
        if (!move_uploaded_file($img['tmp_name'], $to)) {
            return [1, 'Ошибка: Невозможно переместить файл в необходимый каталог'];
        }
    } else {
        return [1, 'Ошибка: Возможна атака через загрузку файла'];
    }
    return [0, 'Изображение валидно'];
}