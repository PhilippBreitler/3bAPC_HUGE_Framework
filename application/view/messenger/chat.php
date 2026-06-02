
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/chat.css" />

    <div class="container">
        <section class="discussion">
            <?php
            $messages = $this->messages;
            $count = count($messages);
            $current_user_id = Session::get('user_id');
            $chat_info = $this->chat_info;
            $is_group  = $chat_info->is_group;

            for ($i = 0; $i < $count; $i++) {
                $msg     = $messages[$i];
                $is_sender   = $msg->sender_id == $current_user_id;
                $role    = $is_sender ? 'sender' : 'recipient';

                $prev_same = $i > 0 && $messages[$i - 1]->sender_id == $msg->sender_id;
                $next_same = $i < $count - 1 && $messages[$i + 1]->sender_id == $msg->sender_id;

                if (!$prev_same && $next_same) {
                    $group = 'first';
                } elseif ($prev_same && $next_same) {
                    $group = 'middle';
                } elseif ($prev_same && !$next_same) {
                    $group = 'last';
                } else {
                    $group = '';
                }

                echo '<div class="bubble ' . $role . ' ' . $group . '">';
                echo htmlspecialchars($msg->content);
                if ($is_group && !$is_sender && !$prev_same) {
                    echo '<span class="sender-name">' . htmlspecialchars($msg->user_name) . '</span>';
                }
                // echo htmlspecialchars($msg->content);
                echo '</div>';
            }
            ?>
        </section>

        <form action="<?php echo Config::get('URL'); ?>messenger/sendMessage" method="post">
            <input type="hidden" name="chat_id" value="<?php echo $this->chat_id; ?>" />
            <input type="text" name="content" placeholder="Nachricht schreiben..." autocomplete="off" required />
            <button type="submit">Senden</button>
        </form>
    </div>