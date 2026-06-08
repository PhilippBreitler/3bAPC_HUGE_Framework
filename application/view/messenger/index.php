<div class="container">
    <h1>MessengerController/index</h1>
    <div class="box">

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>

        <h3>What happens here ?</h3>
        <p>
            Opening Chats with other Users or starting group chats!
        <div>
            <table id="profilestable" class="overview-table">
                <thead>
                <tr>
                    <td>Avatar</td>
                    <td>Username</td>
                    <td>Chat</td>
                    <td>Ungelesen</td>
                </tr>
                </thead>
                <?php foreach ($this->users as $user) { ?>
                    <!-- Damit man man slebst nicht angezeigt wird -->
                    <?php if ($user->user_id == Session::get('user_id')) continue; ?>
                    <tr>
                        <td class="avatar">
                            <?php if (isset($user->user_avatar_link)) { ?>
                                <img src="<?= $user->user_avatar_link; ?>" />
                            <?php } ?>
                        </td>
                        <td><?= htmlspecialchars($user->user_name); ?></td>
                        <td>
                            <a href="<?= Config::get('URL') . 'messenger/openChat/' . $user->user_id; ?>">Chat öffnen</a>
                        </td>
                        <td>
                            <?php foreach ($this->chats as $chat) { ?>
                                <?php if (!$chat->is_group && $chat->partner_id == $user->user_id && $chat->unread_count > 0) { ?>
                                    <span class="badge"><?= $chat->unread_count; ?></span>
                                    <?php break; ?>
                                <?php } ?>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <a href="<?= Config::get('URL'); ?>messenger/createGroup">
            <button class="group-chat-btn">Gruppe erstellen</button>
        </a>
        <h3>Meine Gruppen</h3>
        <div>
        <table id="groupstable" class="overview-table">
            <thead>
            <tr>
                <td>Name</td>
                <td>Gruppe öffnen</td>
                <td>Ungelesen</td>
            </tr>
            </thead>
            <?php foreach ($this->chats as $chat): ?>
                <?php if (!$chat->is_group) continue; ?>
                <tr>
                    <td><?= htmlspecialchars($chat->name); ?></td>
                    <td><a href="<?= Config::get('URL') . 'messenger/showChat/' . $chat->id; ?>">Gruppe öffnen</a></td>
                    <td>
                        <?php if ($chat->unread_count > 0): ?>
                            <span class="badge"><?= $chat->unread_count; ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        </div>
    </div>
</div>

<script>
    new DataTable('#profilestable');
    new DataTable('#groupstable');
</script>
