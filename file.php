<?php
    require 'config.php';

    $errors=[];
    $messages=[];

    $imageFileName=$_GET['name'];
    $commentFilePath=COMMENT_DIR.'/'.$imageFileName.'.txt';

    if(!empty($_POST['comment'])) {
        $comment=trim($_POST['comment']);

        if($comment === '') {
            $errors[]='Вы не ввели текст комментария';
        }

        if(empty($errors)) {
            $comment=strip_tags($comment);
            $comment=str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n"),"<br/>", $comment);
            $comment=date('d.m.y H:i').': '.$comment;

            file_put_contents($commentFilePath, $comment."\n", FILE_APPEND);

            $messages[]='Комментарий был добавлен';
        }
    }

    $comments=file_exists($commentFilePath) ? file($commentFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <title>Галерея изображений</title>
    </head>
    <body>
        <div class="container">
            <h1 class="fs-2 text-primary text-opacity-75 text-center"><a href="<?php echo URL; ?>">Галерея изображений</a></h1>
            <?php foreach($errors as $error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endforeach; ?>
            <?php foreach($messages as $message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endforeach; ?>
            <h2 class="mb-4 text-center"><?php echo $imageFileName; ?></h2>
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2">
                    <img src="<?php echo UPLOAD_DIR.'/'.$imageFileName ?>" class="img-thumbnail mb-4" alt="<?php echo $imageFileName ?>">
                    <h3>Комментарии</h3>
                    <?php if(!empty($comments)): ?>
                        <?php foreach($comments as $key => $comment): ?>
                            <p class="<?php echo (($key%2)>0)?'bg-light':''; ?>"><?php echo $comment; ?></p>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Пока ни одного комментария, будте первым!</p>
                    <?php endif; ?>

                    <form method="post">
                        <div class="form-group">
                            <label for="comment">Ваш комментарий</label>
                            <textarea name="comment" id="comment" rows="3" class="form-control" required></textarea>
                        </div>
                        <hr>
                        <button class="btn btn-primary">Отправить</button>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input@1.3.4/dist/bs-custom-file-input.min.js"></script>
    </body>
</html>