<div class="container">
    <h1>MessengerController/index</h1>
    <div class="box">

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>

        <h3>What happens here ?</h3>
        <div>
            This controller/action/view shows a list of all users in the system. You could use the underlying code to
            build things that use profile information of one or multiple/all users.
        </div>
        <div>
            <table id="profilestable" class="overview-table">
                <thead>
                <tr>
                    <td>Id</td>
                    <td>Avatar</td>
                    <td>Username</td>
                    <td>User's email</td>
                    <td>Activated ?</td>
                    <td>Link to user's profile</td>
                    <td>User Role</td>
                    <td>Chat</td>
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
                        <td><?= $user->user_email; ?></td>
                        <td><?= ($user->user_active == 0 ? 'No' : 'Yes'); ?></td>
                        <td>
                            <a href="<?= Config::get('URL') . 'profile/showProfile/' . $user->user_id; ?>">Profile</a>
                        </td>
                        <td>
                            <?php
                                if ($user->user_account_type == 7) {
                                    echo 'Admin';
                                } elseif ($user->user_account_type == 2) {
                                    echo 'normaler User';
                                } elseif ($user->user_account_type == 1) {
                                    echo 'Gast';
                                }
                            ?>
                        </td>
                        <td>
                            <a href="<?= Config::get('URL') . 'messenger/openChat/' . $user->user_id; ?>">Chat öffnen</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

<script>
    new DataTable('#profilestable');
</script>
