
<div class="container">
    <div class="box">
        <h1>Neues Angebot erstellen</h1>

        <?php $this->renderFeedbackMessages(); ?>

        <form method="post" action="<?php echo Config::get('URL'); ?>marketplace/create" enctype="multipart/form-data">

            <label for="title">Titel *</label>
            <input type="text" id="title" name="title" maxlength="150" required />

            <label for="category_id">Kategorie *</label>
            <select id="category_id" name="category_id" required>
                <option value="">-- bitte wählen --</option>
                <?php foreach ($this->categories as $cat): ?>
                    <option value="<?php echo $cat->category_id; ?>">
                        <?php echo htmlspecialchars($cat->category_name); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="price">Preis (€) *</label>
            <input type="number" id="price" name="price" min="0.01" step="0.01" required />

            <label for="description">Beschreibung *</label>
            <textarea id="description" name="description" rows="5" required></textarea>

            <label for="photos">Fotos (max. 3, je max. 5 MB, JPG/PNG/GIF)</label>
            <!-- multiple erlaubt mehrere Dateien gleichzeitig auszuwählen -->
            <input type="file" id="photos" name="photos[]" accept=".jpg,.jpeg,.png,.gif" multiple />

            <button type="submit" name="submit" value="1">Angebot erstellen</button>
            <a href="<?php echo Config::get('URL'); ?>marketplace/index">Abbrechen</a>
        </form>
    </div>
</div>