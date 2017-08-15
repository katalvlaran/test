<!DOCTYPE Html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Новости</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </head>
    <body>

        <?php
            require_once 'update.php';

            $showLinks = new NewsParse();
            $links = $showLinks->getLinks();
        ?>

        <div id="content">
            <?php if(!empty($links)):?>
            <h4>Новости</h4>
            <div class="news-links">
                <ul class="links">
                    <?php foreach ($links as $link) {
                            echo "<li class='link'><a href='$link[2]'>$link[1]</a></li>";
                        }
                    ?>
                </ul>
                    <?php $date = array_pop($links);?>

                <button type="button" id="update" class="btn btn-success btn-sm">Обновить</button>
                <span id="last_update">Дата последнего обновления: <?=$date[3]?></span>
            </div>
            <?php endif;?>
        </div>
        <script>
            $('.news-links').on('click', '#update', function(){
                $.ajax({
                    type: "GET",
                    url: '/update.php?update=1',
                    success: function (data) {
                        $('#content').load('index.php');
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
        </script>
    </body>
</html>




