<?php

require_once __DIR__ . '/../../app/Lib/Session.php';
require_once __DIR__ . '/../../app/Lib/findUserByMail.php';
require_once __DIR__ . '/../../app/Lib/createUser.php';
require_once __DIR__ . '/../../app/Lib/redirect.php';


$email = filter_input(INPUT_POST, 'email');
$name = filter_input(INPUT_POST, 'name');
$password = filter_input(INPUT_POST, 'password');
$confirmPassword = filter_input(INPUT_POST, 'confirmPassword');

$session = Session::getInstance();

if (empty($password) || empty($confirmPassword)) {
    $session->appendError('パスワードを入力してください');
}
if ($password !== $confirmPassword) {
    $session->appendError('パスワードが一致しません');
}
if ($session->existsErrors()) {
    $formInputs =[
        'email' => $email,
        'user' => $user,
    ];
    $session->setFormInputs($formInputs);
    redirect('signup.php');
}

// メールアドレスに一致するユーザーの取得
$user = findUserByMail($email);
if (!is_null($user)) {
    $session->appendError('すでに登録済みのメールアドレスです');
}
if (!empty($_SESSION['errors'])) {
    redirect('signup.php');
}

// ユーザーの保存
createUser($name, $email, $password);

$message = '登録できました。';
$session->setMessage($message);
redirect('signin.php');
