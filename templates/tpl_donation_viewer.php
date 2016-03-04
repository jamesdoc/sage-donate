<?php /* TODO: This needs a lot of design improvement */ ?>
<h1>Donations</h1>

<table class="widefat fixed">
    <thead>
        <tr>
            <th>Date</th>
            <th>Name</th>
            <th>Donation</th>
            <th>Gift Aid</th>
            <th>Email</th>
            <th>Mailing List</th>
            <th>Status</th>
        </tr>
    </thead>

    <?php foreach($donations as $k=>$donation): ?>
    <tr<?php if($k% 2 == 0){ echo ' class="alternate"';}?>>
        <td><?php echo $donation->init_time; ?></td>
        <td><?php echo $donation->name_first; ?> <?php echo $donation->name_last; ?></td>
        <td><?php echo $donation->amount; ?> <?php echo $donation->currency; ?></td>
        <td><?php if ($donation->giftaid) { echo 'Yes'; } else { echo 'No'; }; ?></td>
        <td><?php echo $donation->email; ?></td>
        <td><?php if ($donation->mailinglist) { echo 'Yes'; } else { echo 'No'; }; ?></td>
        <td><?php echo $donation->status; ?></td>
    </tr>

    <tr<?php if($k% 2 == 0){ echo ' class="alternate"';}?>>
        <td colspan="2'">
            <p>
                <strong>Address</strong><br />
                <?php echo $donation->address1; ?><br />
                <?php if($donation->address2) { echo $donation->address2 . '<br />'; } ?>
                <?php echo $donation->city; ?><br />
                <?php if($donation->county) { echo $donation->county . '<br />'; } ?>
                <?php echo $donation->postcode; ?><br />
                <?php echo $donation->country; ?>
            </p>
        </td>
        <td>
            <p>
                <strong>Phone Number</strong><br />
                <?php
                    if($donation->phone) {
                        echo $donation->phone;
                    } else {
                        echo 'No phone number given';
                    }
                ?>
            </p>
        </td>
        <td>
            <p>
                <strong>Allocation</strong><br />
                <?php
                    if($donation->gift_allocation) {
                        echo $donation->gift_allocation;
                    } else {
                        echo 'N/A';
                    }
                ?>
            </p>
        </td>
        <td colspan="3"></td>
    </tr>
    <?php endforeach; ?>

</table>

<?php echo paginate_links( $pagination_config ); ?>

<p><?php echo $total_donations; ?> donations</p>
