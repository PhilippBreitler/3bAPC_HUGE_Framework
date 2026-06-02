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
                    <td>Id</td>
                    <td>Avatar</td>
                    <td>Username</td>
                    <td>Chat</td>
                    <td>Ungelesen</td>  <!-- NEU -->
                </tr>
                </thead>
                <?php foreach ($this->users as $user) { ?>
                    <!-- Damit man man slebst nicht angezeigt wird -->
                    <?php if ($user->user_id == Session::get('user_id')) continue; ?>
                    <tr class="<?= ($user->user_active == 0 ? 'inactive' : 'active'); ?>">
                        <td><?= $user->user_id; ?></td>
                        <td class="avatar">
                            <?php if (isset($user->user_avatar_link)) { ?>
                                <img src="<?= $user->user_avatar_link; ?>" />
                            <?php } ?>
                        </td>
                        <td><?= $user->user_name; ?></td>
                        <td>
                            <a href="<?= Config::get('URL') . 'messenger/openChat/' . $user->user_id; ?>">Chat öffnen</a>
                        </td>
                        <td>
                            <?php 
                                $chatId = $this->directChats[$user->user_id] ?? null;
                                if ($chatId && !empty($this->unread[$chatId])): ?>
                                    <span class="badge" style="background-color: red; color: white; padding: 2px 7px; border-radius: 10px; font-weight: bold;"><?= $this->unread[$chatId]; ?></span>
                            <?php endif; ?>
                            </td>
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
                <td>Id</td>
                <td>Name</td>
                <td>Gruppe öffnen</td>
                <td>Ungelesen</td>
            </tr>
            </thead>
            <?php foreach ($this->chats as $chat): ?>
                <?php if (!$chat->is_group) continue; ?>
                <tr>
                    <td><?= $chat->id; ?></td>
                    <td><?= htmlspecialchars($chat->name); ?></td>
                    <td><a href="<?= Config::get('URL') . 'messenger/showChat/' . $chat->id; ?>">Gruppe öffnen</a></td>
                    <td>
                        <?php if (!empty($this->unread[$chat->id])): ?>
                            <span class="badge" style="background-color: red; color: white; padding: 2px 7px; border-radius: 10px; font-weight: bold;"><?= $this->unread[$chat->id]; ?></span>
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
