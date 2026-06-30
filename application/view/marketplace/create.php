<div class="container">
    <h1>Marketplace</h1>
    <div class="box">
        <h2>Neues Angebot erstellen</h2>

        <?php $this->renderFeedbackMessages(); ?>

        <form method="post" action="<?php echo Config::get('URL'); ?>marketplace/create" enctype="multipart/form-data" style="margin-top: 20px;">

            <input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />

            <div class="mp-form-group">
                <label for="title" class="mp-label">Titel</label>
                <input type="text" id="title" name="title" maxlength="150" required class="mp-input"
                    value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" />
            </div>

            <div class="mp-row">
                <div class="mp-col">
                    <label for="category_id" class="mp-label">Kategorie</label>
                    <select id="category_id" name="category_id" required class="mp-input">
                        <!-- <option value="">-- bitte wählen --</option> -->
                         <option value=""></option>
                        <?php foreach ($this->categories as $cat): ?>
                            <option value="<?php echo $cat->category_id; ?>"
                                <?php if (isset($_POST['category_id']) && $_POST['category_id'] == $cat->category_id) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($cat->category_name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mp-col">
                    <label for="price" class="mp-label">Preis (€)</label>
                    <input type="number" id="price" name="price" min="0.01" step="0.01" required class="mp-input"
                        value="<?php echo htmlspecialchars($_POST['price'] ?? '0.00'); ?>" />
                </div>
            </div>

            <div class="mp-form-group">
                <label for="description" class="mp-label">Beschreibung</label>
                <textarea id="description" name="description" rows="6" required class="mp-input"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
            </div>

            <div class="mp-form-group">
                <label class="mp-label">
                    Fotos <span class="mp-label-hint">(max. 3 Dateien &middot; je max. 5&nbsp;MB &middot; JPG, PNG, GIF)</span>
                </label>
                <!-- multiple erlaubt mehrere Dateien gleichzeitig auszuwählen -->
                <input type="file" id="photos" name="photos[]" accept=".jpg,.jpeg,.png,.gif" multiple class="mp-input" onchange="previewPhotos(this)" />
                <div id="photo-preview" class="mp-photo-preview"></div>
                <div id="photo-error" style="color:red;display:none;">Maximal 3 Fotos erlaubt.</div>
            </div>

            <div class="mp-form-actions">
                <input type="submit" id="submit-btn" name="submit" value="Angebot erstellen" />
                <button type="button" onclick="window.location='<?php echo Config::get('URL'); ?>marketplace/index'">Abbrechen</button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewPhotos(input) {
        var preview = document.getElementById('photo-preview');
        var errorEl = document.getElementById('photo-error');
        var submitBtn = document.getElementById('submit-btn');
        preview.innerHTML = '';

        if (input.files.length > 3) {
            errorEl.style.display = 'block';
            submitBtn.disabled = true;
            return;
        }
        errorEl.style.display = 'none';
        submitBtn.disabled = false;

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