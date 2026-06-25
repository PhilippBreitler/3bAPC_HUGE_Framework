<div class="container">

    <a href="<?php echo Config::get('URL'); ?>marketplace/view/<?php echo $this->listing->listing_id; ?>" class="mp-back-link">&larr; Zurück zum Angebot</a>

    <div class="box">
        <h2>Angebot bearbeiten</h2>
    </div>

    <?php $this->renderFeedbackMessages(); ?>

    <form method="post"
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

        <div class="mp-form-actions">
            <button type="submit" name="submit" value="1" class="mp-btn">Änderungen speichern</button>
            <a href="<?php echo Config::get('URL'); ?>marketplace/view/<?php echo $this->listing->listing_id; ?>" class="mp-btn-secondary">Abbrechen</a>
        </div>

    </form>
</div>