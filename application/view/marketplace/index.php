<div class="container">
    <h1>Marketplace</h1>
    <?php $this->renderFeedbackMessages(); ?>

    <div class="box" style="display: flex; align-items: center; justify-content: space-between;">
        <h2 style="margin: 0;">Marketplace</h2>
        <a href="<?php echo Config::get('URL'); ?>marketplace/create" class="mp-btn">+ Neues Angebot</a>
    </div>

    <?php $activeTab = $this->active_tab; ?>

    <?php if ($activeTab !== 'mine'): ?>

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
                            <div class="mp-card-actions">
                                <a href="<?php echo Config::get('URL'); ?>marketplace/edit/<?php echo $listing->listing_id; ?>">Bearbeiten</a>
                                <a href="<?php echo Config::get('URL'); ?>marketplace/delete/<?php echo $listing->listing_id; ?>"
                                   class="mp-card-action-delete"
                                   onclick="return confirm('Angebot wirklich löschen?');">Löschen</a>
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