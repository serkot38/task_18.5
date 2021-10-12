<?php 
    require 'config.php';

    $errors=[];
    $messages=[];

    $files=scandir(UPLOAD_DIR);
    $files=array_filter($files, function($file) {
        return !in_array($file, ['.','..', '.gitkeep']);
    });

    if(!empty($_FILES)) {
        for($i=0; $i<count($_FILES['files']['name']); $i++) {
            $fileName=$_FILES['files']['name'][$i];

            if($_FILES['files']['size'][$i]>UPLOAD_MAX_SIZE) {
                $errors[]='Недопустимый размер файла '.$fileName;
                continue;
            }

            if(!in_array($_FILES['files']['type'][$i], ALLOWED_TYPES)) {
                $errors[]='Недопустимый формат файла '.$fileName;
                continue;
            }

            $filePath=UPLOAD_DIR.'/'.basename($fileName);
            if(!move_uploaded_file($_FILES['files']['tmp_name'][$i], $filePath)) {
                $errors[]='Ошибка загрузки файла '.$fileName;
                continue;
            }
        }
        if(empty($errors)) {
            $messages[]='Файлы уже загружены';
        }
    }

    if(!empty($_POST['name'])) {
        $filePath=UPLOAD_DIR.'/'.$_POST['name'];
        $commentPath=COMMENT_DIR.'/'.$_POST['name'].'.txt';
        
        unlink($filePath);

        if(file_exists($commentPath)) {
            unlink($commentPath);
        }
        $messages[]='Файл был удален';
    }
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
            <?php if(!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if(!empty($_FILES) && empty($errors)): ?>
                <div class="alert alert-success">Файлы успешно загружены</div>
            <?php endif; ?>
                <form action="<?php echo URL; ?>" method="post" enctype="multipart/form-data">
                    <div class="custom-file">
                        <div class="row">
                            <div class="col-4">
                                <input type="file" class="custom-file-input" name="files[]" id="customFile" multiple required>
                                <label for="customFile" class="custom-file-label" data-browse="Выбрать">Выберите файлы</label>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-primary">Загрузить</button>
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            Максимальный размер файла: <?php echo UPLOAD_MAX_SIZE / 1000000; ?>Мб.
                            Допустимые форматы: <?php echo implode(', ', ALLOWED_TYPES) ?>.
                        </small>
                    </div>
                </form>
                <hr>
                <h1 class="fs-2 text-primary text-opacity-75 text-center">Галерея изображений</h1>
                <div class="mb-4">
                    <?php if(!empty($files)): ?>
                        <div class="row">
                            <?php foreach($files as $file): ?>
                                <div class="col-12 col-sm-3 mb-4">
                                    <form method="post">
                                        <input type="hidden" name="name" value="<?php echo $file; ?>">
                                        <button type="submit" class="close" aria-label="close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </form>
                                    <a href="<?php echo URL.'/file.php?name='.$file; ?>" title="Просмотр полного изображения">
                                        <img src="<?php echo URL.'/'.UPLOAD_DIR.'/'.$file ?>" class="img-thumbnail" alt="<?php echo $file; ?>">
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-secondary">Нет загруженных изображений</div>
                    <?php endif; ?>
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
        <script>
            $(() => {
                bsCustomFileInput.init();
            });
        </script>
    </body>
</html>