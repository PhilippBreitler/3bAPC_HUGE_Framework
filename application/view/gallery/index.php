<div class="container">
    <h1>MessengerController/index</h1>
    <div class="box">

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>
        <!-- </div>
            <form method="post" enctype="multipart/form-data">
            <input type="file" name="datei" accept=".jpg,.png,.pdf">
            <button type="submit">Hochladen</button>
        </form> -->

        <form method="post" action="<?= Config::get('URL') ?>gallery/index" enctype="multipart/form-data">
            <input type="file" name="datei" accept=".jpg,.jpeg,.png,.gif">
            <button type="submit" name="upload" value="1">Hochladen</button>
        </form>
    </div>

    <?php if ($this->images): ?>
        <section class="gallery">
            <?php foreach ($this->images as $img): ?>
                <figure tabindex="1">
                    <img src="<?= Config::get('URL') ?>gallery/image/<?= $img->image_id ?>"
                         alt="<?= htmlentities($img->original_name) ?>">
                    <figcaption><?= htmlentities($img->original_name) ?></figcaption>
                </figure>
            <?php endforeach; ?>
        </section>
    <?php else: ?>
        <p>Noch keine Bilder hochgeladen.</p>
    <?php endif; ?>
</div>

