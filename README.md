# Sage Donate

V1.1.0

## Installation

Copy into plugins folder

Update plugin settings (Settings / Sage Donate)

Create three new pages:

*Donate* - Where a user makes a donation  
Add `[sage_donate]`

*Success page* - Where Sage redirects the user after successful payment  
Add `[sage_after_donate]`

*Failure* - Where Sage redirects the user if the payment fails  
Add `[sage_after_donate]`

## Credits

Sagepay PHP library by Timur Olzhabayev: https://github.com/tolzhabayev/sagepayForm-php

## Changelog

### V1.1.1
- From name and email address now set in thank you email

### V1.1.0
- Adds editable gift aid text
- Prevents the form from being submitted with £0.00

## Roadmap

- Improve admin settings design
- CSV export
- Encryption
- Basic styling

