<?php
    $email_content = '
<html>
<body>
  <table style="max-width: 800px">
    <tr>
      <td>
        <h1>SageDonate</h1>
        <p>There has been a new donation on ' . get_bloginfo('name') . '.</p>
        <table width="100%">
          <tr>
            <td width="33%"><b>Status</b></td>
            <td width="67%">' . $donation->status .'</td>
          </tr>
          <tr>
            <td><b>Date</b></td>
            <td>' . $donation->init_time .'</td>
          </tr>
          <tr>
            <td><b>Name</b></td>
            <td>
              ' . $donation->name_first. ' ' . $donation->name_last .'
            </td>
          </tr>
          <tr>
            <td><b>Address</b></td>
            <td>' .
              $donation->address1 . ' <br />';
              if($donation->address2) { $email_content .= $donation->address2 . '<br />'; }
              $email_content .= $donation->city . ' <br />';
              if($donation->county) { $email_content .= $donation->county . '<br />'; }
              $email_content .= $donation->postcode . '<br />' . $donation->country . '
            </td>
          </tr>
          <tr>
            <td><b>Email</b></td>
            <td>' . $donation->email .'</td>
          </tr>
          <tr>
            <td><b>Phone</b></td>
            <td>' . $donation->phone .'</td>
          </tr>
          <tr>
            <td><b>Amount</b></td>
            <td>' . $donation->amount .' ' . $donation->currency .'</td>
          </tr>
          <tr>
            <td><b>Gift Aid</b></td>
            <td>';
              if ($donation->giftaid) { $email_content .= 'Yes'; }
              else { $email_content .= 'No'; };
            $email_content .= '</td>
          </tr>
          <tr>
            <td><b>Mailing List</b></td>
            <td>';
              if ($donation->mailinglist) { $email_content .= 'Yes'; }
              else { $email_content .= 'No'; };
            $email_content .= '</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
    ';
?>
