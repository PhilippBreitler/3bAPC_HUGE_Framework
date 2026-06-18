<div class="container">
    <h1>MarketplaceController/index</h1>
    <div class="box">
        <h1>Marketplace</h1>

        <?php $this->renderFeedbackMessages(); ?>

        <a href="<?php echo Config::get('URL'); ?>marketplace/create" class="btn">
            + Neues Angebot erstellen
        </a>
    </div>

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
                        <a href="<?php echo Config::get('URL'); ?>marketplace/view/<?php echo $listing->listing_id; ?>">
                            Details ansehen
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Noch keine Angebote vorhanden.</p>
    <?php endif; ?>
</div>