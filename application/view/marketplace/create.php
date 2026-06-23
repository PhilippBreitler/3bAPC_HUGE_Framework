<div class="container">
    <div class="box">
        <h1>Neues Angebot erstellen</h1>

        <?php $this->renderFeedbackMessages(); ?>

        <form method="post" action="<?php echo Config::get('URL'); ?>marketplace/create" enctype="multipart/form-data" style="margin-top: 20px;">

            <input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />

            <div class="mp-form-group">
                <label for="title" class="mp-label">Titel *</label>
                <input type="text" id="title" name="title" maxlength="150" required class="mp-input"
                    value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" />
            </div>

            <div class="mp-row">
                <div class="mp-col">
                    <label for="category_id" class="mp-label">Kategorie *</label>
                    <select id="category_id" name="category_id" required class="mp-input">
                        <option value="">-- bitte wählen --</option>
                        <?php foreach ($this->categories as $cat): ?>
                            <option value="<?php echo $cat->category_id; ?>">
                                <?php if (isset($_POST['category_id']) && $_POST['category_id'] == $cat->category_id) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($cat->category_name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mp-col">
                    <label for="price" class="mp-label">Preis (€) *</label>
                    <input type="number" id="price" name="price" min="0.01" step="0.01" required class="mp-input"/>
                            value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>" />
                </div>
            </div>

            <div class="mp-form-group">
                <label for="description" class="mp-label">Beschreibung *</label>
                <textarea id="description" name="description" rows="6" required class="mp-input"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
            </div>

            <div class="mp-form-group">
                <label class="mp-label">
                    Fotos <span class="mp-label-hint">(max. 3 Dateien &middot; je max. 5&nbsp;MB &middot; JPG, PNG, GIF)</span>
                </label>
                <!-- multiple erlaubt mehrere Dateien gleichzeitig auszuwählen -->
                <input type="file" id="photos" name="photos[]" accept=".jpg,.jpeg,.png,.gif" multiple class="mp-input" onchange="previewPhotos(this)" />
                <div id="photo-preview" class="mp-photo-preview"></div>
            </div>

            <div class="mp-form-actions">
                <button type="submit" name="submit" value="1" class="mp-btn">Angebot erstellen</button>
                <a href="<?php echo Config::get('URL'); ?>marketplace/index" class="mp-btn-secondary">Abbrechen</a>
            </div>
        </form>
    </div>
</div>

<script>
    function previewPhotos(input) {
        var preview = document.getElementById('photo-preview');
        preview.innerHTML = '';
        Array.prototype.slice.call(input.files, 0, 3).forEach(function(file) {
            if (!file.type.match('image.*')) return;
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.createElement('img');
                img.src = e.target.result;
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }
</script>