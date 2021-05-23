<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список логов</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <link href="../css/style-header.css" rel="stylesheet">
    <link href="../css/style-logo.css" rel="stylesheet">
    <link href="../css/style-logging.css" rel="stylesheet">
</head>
<body>
    <div id = "wrap">
        <header>
        <p class="logo"><a href="/">
            <img src="/public/logo.png" alt="Логотип в шапке" width="80" height="80" />
        </a></p>
        </header>
    </div>
    
    <h4><div style="text-align: center;">Список последних 1000 записей в таблице логов</div></h4>
    <h5><div style="text-align: center;">(Сверху более свежие логи)</div></h5>

    <div class="layer">
    <p><?php foreach($pageData as $log): ?>
        <div style="text-align: center;" class ="panel panel-default">
            <?php echo("| " . "id: " . $log['id'] . " | "); ?>
            <?php echo("user_id: " . $log['user_id'] . " | "); ?>
            <?php echo("action: " . $log['action'] . " | "); ?>
            <?php echo("object_id: " . $log['object_id'] . " | "); ?>
            <?php echo("date: " . $log['date'] . " | "); ?>
            <?php echo("ip: " . $log['ip'] . " | "); ?>
        </div>
    <?php endforeach; ?> </p>
  </div> 
</body>
</html>