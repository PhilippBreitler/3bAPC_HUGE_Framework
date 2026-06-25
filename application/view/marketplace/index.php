<div class="container">
    <h1>Marketplace</h1>
    <?php $this->renderFeedbackMessages(); ?>

    <div class="box">
        <h2>Offene Angebote</h2>
        <a href="<?php echo Config::get('URL'); ?>marketplace/create" class="mp-btn">+ Neues Angebot</a>
    </div>

    <?php $activeTab = $this->active_tab; ?>

    <?php if ($activeTab !== 'mine'): ?>


    <!-- Filter-Formular -->
    <form method="get" action="<?php echo Config::get('URL'); ?>marketplace/index"
        style="margin-top: 20px; display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
        <input type="hidden" name="tab" value="all">

        <div>
            <label class="mp-label" for="f-category">Kategorie</label>
            <select id="f-category" name="category_id" class="mp-input" style="width: auto; min-width: 160px;">
                <option value="">Alle Kategorien</option>
                <?php foreach ($this->categories as $cat): ?>
                    <option value="<?php echo $cat->category_id; ?>"
                        <?php if (($this->filters['category_id'] ?? '') == $cat->category_id) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($cat->category_name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="mp-label" for="f-price-min">Preis von (€)</label>
            <input type="number" id="f-price-min" name="price_min" min="0" step="0.01"
                class="mp-input" style="width: 110px;"
                value="<?php echo htmlspecialchars($this->filters['price_min'] ?? ''); ?>">
        </div>

        <div>
            <label class="mp-label" for="f-price-max">Preis bis (€)</label>
            <input type="number" id="f-price-max" name="price_max" min="0" step="0.01"
                class="mp-input" style="width: 110px;"
                value="<?php echo htmlspecialchars($this->filters['price_max'] ?? ''); ?>">
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="submit" class="mp-btn">Filtern</button>
            <?php if (!empty($this->filters['category_id']) || ($this->filters['price_min'] ?? '') !== '' || ($this->filters['price_max'] ?? '') !== ''): ?>
                <a href="<?php echo Config::get('URL'); ?>marketplace/index?tab=all" class="mp-btn-secondary">Zurücksetzen</a>
            <?php endif; ?>
        </div>
    </form>







        <?php if (!empty($this->listings)): ?>
            <div class="marketplace-grid">
                <?php foreach ($this->listings as $listing): ?>
                    <div class="marketplace-card">
                        <?php if ($listing->first_photo_id): ?>
                            <img src="<?php echo Config::get('URL'); ?>marketplace/photo/<?php echo $listing->first_photo_id; ?>"
                                 alt="<?php echo htmlspecialchars($listing->listing_title); ?>">
                        <?php else: ?>
                            <div class="marketplace-card-no-photo">Kein Foto</div>
                        <?php endif; ?>
                        <div class="marketplace-card-body">
                            <h3><?php echo htmlspecialchars($listing->listing_title); ?></h3>
                            <p class="marketplace-card-price"><?php echo number_format($listing->listing_price, 2, ',', '.'); ?> €</p>
                            <p class="marketplace-card-meta">
                                <?php echo htmlspecialchars($listing->category_name); ?> &bull;
                                <?php echo htmlspecialchars($listing->user_name); ?>
                            </p>
                            <a href="<?php echo Config::get('URL'); ?>marketplace/view/<?php echo $listing->listing_id; ?>">Details ansehen</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="margin-top: 20px; color: #777;">Noch keine Angebote anderer Nutzer vorhanden.</p>
        <?php endif; ?>

    <?php else: ?>

        <?php if (!empty($this->my_listings)): ?>
            <div class="marketplace-grid">
                <?php foreach ($this->my_listings as $listing): ?>
                    <div class="marketplace-card">
                        <?php if ($listing->first_photo_id): ?>
                            <img src="<?php echo Config::get('URL'); ?>marketplace/photo/<?php echo $listing->first_photo_id; ?>"
                                 alt="<?php echo htmlspecialchars($listing->listing_title); ?>">
                        <?php else: ?>
                            <div class="marketplace-card-no-photo">Kein Foto</div>
                        <?php endif; ?>
                        <div class="marketplace-card-body">
                            <h3><?php echo htmlspecialchars($listing->listing_title); ?></h3>
                            <p class="marketplace-card-price"><?php echo number_format($listing->listing_price, 2, ',', '.'); ?> €</p>
                            <p class="marketplace-card-meta"><?php echo htmlspecialchars($listing->category_name); ?></p>
                            <!-- <div class="mp-card-actions"> -->
                            <div class="mp-card-icon-actions">
                                <!-- <a href="<?php echo Config::get('URL'); ?>marketplace/edit/<?php echo $listing->listing_id; ?>">Bearbeiten</a> -->
                                <a href="<?php echo Config::get('URL'); ?>marketplace/edit/<?php echo $listing->listing_id; ?>"
                                    class="mp-edit-icon-btn"
                                    title="Bearbeiten"
                                    aria-label="Bearbeiten">✎</a>
                                <!-- <a href="<?php echo Config::get('URL'); ?>marketplace/delete/<?php echo $listing->listing_id; ?>"
                                   class="mp-card-action-delete"
                                   onclick="return confirm('Angebot wirklich löschen?');">Löschen</a> -->
                                <a href="<?php echo Config::get('URL'); ?>marketplace/delete/<?php echo $listing->listing_id; ?>"
                                    class="mp-delete-icon-btn"
                                    title="Löschen"
                                    aria-label="Löschen"
                                    onclick="return confirm('Angebot wirklich löschen?');">×</a>
                                <!-- <a href="<?php echo Config::get('URL'); ?>marketplace/delete/<?php echo $listing->listing_id; ?>"
                                    class="mp-card-action-delete">Verkauft</a> -->
                                <a href="<?php echo Config::get('URL'); ?>marketplace/inquiries/<?php echo $listing->listing_id; ?>" class="mp-inquiry-icon-btn" title="Anfragen"></a>
                                <a href="<?php echo Config::get('URL'); ?>marketplace/delete/<?php echo $listing->listing_id; ?>"
                                    class="mp-sold-icon-btn"
                                    title="Verkauft"
                                    aria-label="Verkauft">$</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="margin-top: 20px; color: #777;">Du hast noch keine Angebote erstellt.</p>
        <?php endif; ?>

    <?php endif; ?>
</div>