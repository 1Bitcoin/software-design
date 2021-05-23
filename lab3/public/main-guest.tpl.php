<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Главная страница</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">

    <!-- Custom styles -->
    <link href="../css/styles-main.css" rel="stylesheet">
    <link href="../css/styles-button.css" rel="stylesheet">
    <link href="../css/statistics-table.css" rel="stylesheet">
  </head>

  <body>

    <main role="main" class="container">

      <h1>Добро пожаловать. Снова.</h1>
      <p class="lead mb-4">
        Для получения возможности загрузки файлов на сервер авторизируйтесь или зарегистрируйтесь.
      </p>

      <a href="list?page=1" class="button-list">Список файлов</a>
      <a href="register" class="button-register">Регистрация</a>
      <a href="login" class="button-login">Авторизация</a>

      <p></p>

      <p class="lead mb-4">
        Статистика сайта.
      </p>

    <table class="simple-little-table" cellspacing='0'>
        <tr>
            <th>Количество администраторов</th>
            <th>Количество модераторов</th>
            <th>Количество пользователей</th>
            <th>Количество комментариев</th>
            <th>Файлов загружено</th>
            <th>Файлов скачано</th>
        </tr>
    
        <tr>
            <td><?php echo $pageData['statistics']['count_administrators']; ?></td>
            <td><?php echo $pageData['statistics']['count_moderators']; ?></td>
            <td><?php echo $pageData['statistics']['count_users']; ?></td>
            <td><?php echo $pageData['statistics']['count_comments']; ?></td>
            <td><?php echo $pageData['statistics']['count_upload_files']; ?></td>
            <td><?php echo $pageData['statistics']['count_download_files']; ?></td>
        </tr>
    </table>

    <footer class="text-center">
        <p></p>
        <p><a href="/">www.iu7.ru</a></p>
        
    </footer>

  </body>
</html>
