<h1>Donations</h1>

<table class="widefat fixed">
    <thead>
        <tr>
            <th>Date</th>
            <th>Name</th>
            <th>Donation</th>
            <th>Gift Aid</th>
            <th>Email</th>
            <th>Status</th>
            <?php /*<th>&nbsp;</th>*/ ?>
        </tr>
    </thead>

    <?php foreach($donations as $k=>$donation): ?>
    <tr<?php if($k% 2 == 0){ echo ' class="alternate"';}?>>
        <td><?php echo $donation->init_time; ?></td>
        <td><?php echo $donation->name_first; ?> <?php echo $donation->name_last; ?></td>
        <td><?php echo $donation->amount; ?> <?php echo $donation->currency; ?></td>
        <td><?php echo $donation->giftaid; ?></td>
        <td><?php echo $donation->email; ?></td>
        <td><?php echo $donation->status; ?></td>
        <?php /*<td><a href="#">More information</a></td>*/ ?>
    </tr>
    <?php endforeach; ?>

</table>
