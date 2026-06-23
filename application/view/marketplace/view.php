
<div class="container">

    <a href="<?php echo Config::get('URL'); ?>marketplace/index" class="mp-back-link">&larr; Zurück zur Übersicht</a>

    <div class="mp-detail-layout">

        <!-- Linke Spalte: Fotos -->
        <div class="mp-detail-photos">
            <?php if (!empty($this->photos)): ?>
                <div class="mp-detail-photo-row">
                    <?php foreach ($this->photos as $i => $photo): ?>
                        <img src="<?php echo Config::get('URL'); ?>marketplace/photo/<?php echo $photo->photo_id; ?>"
                             alt="Foto <?php echo $i + 1; ?>"
                             class="mp-lightbox-trigger"
                             data-index="<?php echo $i; ?>">
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="marketplace-card-no-photo" style="height: 240px; font-size: 14px;">Kein Foto vorhanden</div>
            <?php endif; ?>
        </div>

        <!-- Rechte Spalte: Details -->
        <div class="mp-detail-info">
            <p class="mp-detail-category"><?php echo htmlspecialchars($this->listing->category_name); ?></p>
            <h2 class="mp-detail-title"><?php echo htmlspecialchars($this->listing->listing_title); ?></h2>
            <p class="mp-detail-price"><?php echo number_format($this->listing->listing_price, 2, ',', '.'); ?> €</p>

            <div class="mp-detail-divider"></div>

            <p class="mp-detail-description"><?php echo nl2br(htmlspecialchars($this->listing->listing_description)); ?></p>

            <div class="mp-detail-divider"></div>

            <p class="mp-detail-meta">
                Angeboten von <strong><?php echo htmlspecialchars($this->listing->user_name); ?></strong><br>
                Eingestellt am <?php echo date('d.m.Y', $this->listing->listing_creation_timestamp); ?>
            </p>

            <?php if ($this->listing->user_id !== Session::get('user_id')): ?>
                <a href="<?php echo Config::get('URL'); ?>marketplace/contactSeller/<?php echo $this->listing->listing_id; ?>" class="mp-btn" style="margin-top: 15px;">
                    Verkäufer kontaktieren
                </a>
            <?php else: ?>
                <a href="<?php echo Config::get('URL'); ?>marketplace/edit/<?php echo $this->listing->listing_id; ?>" class="mp-btn" style="margin-top: 15px;">
                    Angebot bearbeiten
                </a>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- Lightbox -->
<div id="mp-lightbox">
    <div id="mp-lightbox-overlay"></div>
    <img id="mp-lightbox-img" src="" alt="">
    <button id="mp-lightbox-close">&times;</button>
    <button id="mp-lightbox-prev">&#8249;</button>
    <button id="mp-lightbox-next">&#8250;</button>
</div>

<script>
(function($) {
    var photos = [];
    var current = 0;

    $('.mp-lightbox-trigger').each(function() {
        photos.push($(this).attr('src'));
    });

    function open(index) {
        current = index;
        $('#mp-lightbox-img').attr('src', photos[current]);
        $('#mp-lightbox-prev').toggle(photos.length > 1);
        $('#mp-lightbox-next').toggle(photos.length > 1);
        $('#mp-lightbox').fadeIn(150);
        $('body').css('overflow', 'hidden');
    }

    function close() {
        $('#mp-lightbox').fadeOut(150);
        $('body').css('overflow', '');
    }

    function prev() { open((current - 1 + photos.length) % photos.length); }
    function next() { open((current + 1) % photos.length); }

    $('.mp-lightbox-trigger').on('click', function() {
        open(parseInt($(this).data('index')));
    });

    $('#mp-lightbox-overlay, #mp-lightbox-close').on('click', close);
    $('#mp-lightbox-prev').on('click', function(e) { e.stopPropagation(); prev(); });
    $('#mp-lightbox-next').on('click', function(e) { e.stopPropagation(); next(); });

    $(document).on('keydown', function(e) {
        if ($('#mp-lightbox').is(':visible')) {
            if (e.key === 'Escape')     close();
            if (e.key === 'ArrowLeft')  prev();
            if (e.key === 'ArrowRight') next();
        }
    });
})(jQuery);
</script>