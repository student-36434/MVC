<?php include __DIR__ . '/../header.php'; ?>
    <h1>Creating a new article</h1>
<?php if(!empty($error)): ?>
    <div style="color: red;"><?= $error ?></div>
<?php endif; ?>
    <form action="/articles/add" method="post">
        <label for="name">Article title</label><br>
        <input type="text" name="name" id="name" value="<?= $_POST['name'] ?? '' ?>" size="50"><br>
        <br>
        <label for="text">Article text</label><br>
        <textarea name="text" id="text" rows="10" cols="80"><?= $_POST['text'] ?? '' ?></textarea><br>
        <br>
        <input type="submit" value="Create">
    </form>
<?php include __DIR__ . '/../footer.php'; ?>

