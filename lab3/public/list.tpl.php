<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список файлов</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <script type="text/javascript" charset="utf8" src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-2.0.3.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/simplePagination.css" />
    <link href="../css/styles-list-button.css" rel="stylesheet">
    <link href="../css/style-table.css" rel="stylesheet">
    <link href="../css/style-header.css" rel="stylesheet">
    <link href="../css/style-logo.css" rel="stylesheet">
    <script src="../js/simplePagination.js"></script>
</head>
<body>
    <div id = "wrap">
        <header>
        <p class="logo"><a href="/">
            <img src="/public/logo.png" alt="Логотип в шапке" width="80" height="80" />
        </a></p>
        </header>
    </div>

    <div class="my-table">
    <table>
    <caption>
        Список файлов
    </caption>
    <tr>
        <th class="test"></th> 
    </tr>

    <?php foreach($pageData['files'] as $file): ?>
        <tr>  
            <td><a href="file?hash=<?php echo $file["hash"]; ?>"> <?php echo $file["name"]; ?></td>  
        </tr>
    <?php endforeach; ?> 

</table>
</div>
   
<div class="php-pagination">
<?php  
$total_records = $pageData['count'];  
$total_pages = ceil($total_records / $pageData['limit']);  
$pagLink = "<nav><ul class='pagination'>";  

for ($i = 1; $i <= $total_pages; $i++) 
{  
    $pagLink .= "<li><a href='list?page=".$i."'>".$i."</a></li>";  
};
echo $pagLink . "</ul></nav>";  
?>
</div>

<script>
$(document).ready(function(){
    $('.pagination').pagination({
        items: <?php echo $total_records;?>,
        itemsOnPage: <?php echo $pageData['limit'];?>,
        cssStyle: 'light-theme',
        currentPage : <?php echo $pageData['page'];?>,
        hrefTextPrefix : 'list?page='
    });
});
</script>

</body>
</html>