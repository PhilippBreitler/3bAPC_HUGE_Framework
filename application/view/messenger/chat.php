
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/chat.css" />

    <div class="container">
        <section class="discussion">
            <?php foreach ($this->messages as $msg): ?>
                <?php $is_sender = ($msg->sender_id == Session::get('user_id')); ?>

                <div class="bubble <?php echo $is_sender ? 'sender' : 'recipient'; ?>">
                    <?php echo htmlspecialchars($msg->content); ?>

                    <?php if ($this->chat_info->is_group && !$is_sender): ?>
                        <span class="sender-name"><?php echo htmlspecialchars($msg->user_name); ?></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </section>

        <form class="chat-form" action="<?php echo Config::get('URL'); ?>messenger/sendMessage" method="post">
            <input type="hidden" name="chat_id" value="<?php echo $this->chat_id; ?>" />
            <input type="text" name="content" placeholder="Nachricht schreiben..." autocomplete="off" required />
            <button type="submit">Senden</button>
        </form>
    </div>