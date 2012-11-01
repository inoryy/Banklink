Banklink implementations for Estonian Internet Banks services.
==============

Swedbank, SEB and Sampo all share one protocol - iPizza.
Nordea uses e-payment.

This application is written in PHP and follows [PSR-0] (https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md) standard to handle autoloading.

Support status:
* SEB:          Payments - Yes, Authentication - No
* LHV:          Payments - Yes, Authentication - No
* Sampo:        Payments - Yes, Authentication - No
* Nordea:       Payments - **No**,  Authentication - No
* EstCard:      Payments - **No**,  Authentication - No
* Swedbank:     Payments - Yes, Authentication - No
* Krediidipank: Payments - Yes, Authentication - No
