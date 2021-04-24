<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>My blog</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>

<table class="layout">
    <tr>
        <td colspan="2" class="header">
            My blog
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: right">
            <?php if (!empty($user)): ?>
                Hello, <?= $user->getNickname() ?>  | <a href="http://myproject.loc/users/logOut">logout</a>
            <?php else: ?>
                <a href="http://myproject.loc/users/login">Sign in</a> | <a href="http://myproject.loc/users/register">Sign up</a>
            <? endif; ?>
        </td>
    </tr>
    <tr>
        <td>