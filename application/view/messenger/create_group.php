<!-- VOM COPLIOT ERSTELLT -->
<div class="container">
    <h2>Gruppen-Chat erstellen</h2>
    <form action="<?php echo Config::get('URL'); ?>messenger/createGroup" method="post">
        <label>Gruppenname:</label>
        <input type="text" name="group_name" required />

        <label>Mitglieder auswählen:</label><br>
        <?php foreach ($this->users as $user): ?>
            <?php if ($user->user_id == Session::get('user_id')) continue; ?>
            <label>
                <input type="checkbox" name="members[]" value="<?= $user->user_id; ?>" />
                <?= htmlspecialchars($user->user_name); ?>
            </label><br>
        <?php endforeach; ?>

        <button type="submit">Gruppe erstellen</button>
    </form>
</div>