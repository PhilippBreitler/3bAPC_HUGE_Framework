
<div class="container">

    <a href="<?php echo Config::get('URL'); ?>marketplace/index?tab=mine" class="mp-back-link">&larr; Zurück zu meinen Angeboten</a>

    <div class="box">
        <h2 style="margin: 0;">Anfragen für: <?php echo htmlspecialchars($this->listing->listing_title); ?></h2>
    </div>

    <?php if (!empty($this->inquiries)): ?>
        <table class="overview-table" style="margin-top: 20px; width: 100%;">
            <thead>
                <tr>
                    <td>Käufer</td>
                    <td>Ungelesene Nachrichten</td>
                    <td>Aktion</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->inquiries as $inq): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($inq->buyer_name); ?></td>
                        <td>
                            <?php if ($inq->unread_count > 0): ?>
                                <span class="badge"><?php echo $inq->unread_count; ?></span>
                            <?php else: ?>
                                –
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo Config::get('URL'); ?>messenger/showChat/<?php echo $inq->chat_id; ?>">
                                Chat öffnen
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="margin-top: 20px; color: #777;">Noch keine Anfragen für dieses Angebot.</p>
    <?php endif; ?>

</div>