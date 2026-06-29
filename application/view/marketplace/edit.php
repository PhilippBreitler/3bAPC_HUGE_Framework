<div class="container">

    <a href="<?php echo Config::get('URL'); ?>marketplace/view/<?php echo $this->listing->listing_id; ?>" class="mp-back-link">&larr; Zurück zum Angebot</a>

    <div class="box">
        <h2>Angebot bearbeiten</h2>
    </div>

    <?php $this->renderFeedbackMessages(); ?>

    <form method="post"
        enctype="multipart/form-data"
        action="<?php echo Config::get('URL'); ?>marketplace/edit/<?php echo $this->listing->listing_id; ?>"
        style="margin-top: 20px;">

        <input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />

        <div class="mp-form-group">
            <label for="title" class="mp-label">Titel *</label>
            <input type="text" id="title" name="title" maxlength="150" required class="mp-input"
                   value="<?php echo htmlspecialchars($_POST['title'] ?? $this->listing->listing_title); ?>" />
        </div>

        <div class="mp-row">
            <div class="mp-col">
                <label for="category_id" class="mp-label">Kategorie *</label>
                <select id="category_id" name="category_id" required class="mp-input">
                    <option value="">-- bitte wählen --</option>
                    <?php foreach ($this->categories as $cat): ?>
                        <option value="<?php echo $cat->category_id; ?>"
                            <?php
                                $selected = $_POST['category_id'] ?? $this->listing->category_id ?? null;
                                if ($selected == $cat->category_id) echo 'selected';
                            ?>>
                            <?php echo htmlspecialchars($cat->category_name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mp-col">
                <label for="price" class="mp-label">Preis (€) *</label>
                <input type="number" id="price" name="price" min="0.01" step="0.01" required class="mp-input"
                       value="<?php echo htmlspecialchars($_POST['price'] ?? $this->listing->listing_price); ?>" />
            </div>
        </div>

        <div class="mp-form-group">
            <label for="description" class="mp-label">Beschreibung *</label>
            <textarea id="description" name="description" rows="6" required class="mp-input"><?php
                echo htmlspecialchars($_POST['description'] ?? $this->listing->listing_description);
            ?></textarea>
        </div>

        <!-- NEU: Foto-Verwaltung -->
        <?php if (!empty($this->photos)): ?>
        <div class="mp-form-group">
            <label class="mp-label">Vorhandene Fotos</label>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <?php foreach ($this->photos as $photo): ?>
                <div style="position:relative;">
                    <img src="<?= Config::get('URL') ?>marketplace/photo/<?= $photo->photo_id ?>"
                         style="width:100px; height:100px; object-fit:cover; border-radius:4px;" />
                    <button type="submit" name="delete_photo_id"
                            value="<?= $photo->photo_id ?>"
                            style="position:absolute;top:2px;right:2px;background:red;color:white;border:none;border-radius:3px;cursor:pointer;">✕</button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="mp-form-group">
            <label for="photos" class="mp-label">Neue Fotos hinzufügen (max. 3 insgesamt, JPG/PNG/GIF, je 5 MB)</label>
            <input type="file" id="photos" name="photos[]" multiple accept="image/*" class="mp-input" />
        </div>
        <!-- ENDE Foto-Verwaltung -->



        <div class="mp-form-actions">
            <button type="submit" name="submit" value="1" class="mp-btn">Änderungen speichern</button>
            <a href="<?php echo Config::get('URL'); ?>marketplace/view/<?php echo $this->listing->listing_id; ?>" class="mp-btn-secondary">Abbrechen</a>
        </div>

    </form>
</div>