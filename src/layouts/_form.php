<div id="form-blog">
    <?php if (!empty($errors)) { ?>
        <?php include "layouts/_errors.php" ?>
    <?php } ?>
    <form method="post" enctype="multipart/form-data">
        <div class="input-control">
            <label for="name">Title: </label>
            <input type="text" name="title" class="input-field input-sm" value="<?= $_POST['title'] ?>" />
        </div>
        <div class="input-control">
            <label for="category">Category:</label>
            <select name="category_id" id="category" class="input-field input-sm">
                <option value="">--- Select Category ---</option>
                <?php if(!empty($categories)) { ?>
                    <?php foreach ($categories as $row) { ?>
                        <option value="<?= $row['id'] ?>" <?= ($row['id'] == $_POST['category_id']) ? 'selected' : '' ?>><?= $row['category_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
        <div class="input-control">
            <textarea id="editor" name="body" class="input-field input-sm"><?= $_POST['body'] ?></textarea>
        </div>
        <div class="input-control">
            <label for="category">Thumbnail</label>
            <input type="file" name="thumbnail" accept="image/*" class="input-field input-sm" />
            <input type="hidden" value="<?= $_POST['thumbnail'] ?>" name="thumbnail">
        </div>
        <?php if(!empty($_POST['thumbnail'])) { ?>
            <div class="thumbnail">
                <img src="data:image/jpeg;base64,<?= $_POST['thumbnail'] ?>" alt="" width="500">
            </div>
        <?php } ?>
        <div class="input-control">
            <label for="category">Status</label>
            <select name="status_id" id="category" class="input-field input-sm">
                <?php if(!empty($status)) { ?>
                    <?php foreach ($status as $key => $value) { ?>
                        <option value="<?= $key ?>" <?= ($key == $_POST['status_id']) ? 'selected' : '' ?>><?= $value ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
        <div class="input-control">
            <input type="submit" name="submit" class="btn btn-primary btn-lg" value="Save" />
        </div>
    </form>
</div>