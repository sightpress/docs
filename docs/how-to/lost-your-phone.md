# Lost your phone

If you lost your phone or changed your number, you can't get codes to your old number. Here is how to get going again.

## You can still sign in

Sign in with your **email address** and password. To reset a forgotten password, choose **Forgot your password?** and
use your email address: the code comes by email.

## Ask for your number to be removed

While the website [asks for a code for important changes](confirm-important-changes.md), you can't change your number
yourself, because that needs a code to the old one. Ask someone who manages people on the website:

1. They go to **People** and choose you.
2. They choose **Remove the mobile number of …** (they confirm it's them with their own code).

If nobody on the website can, whoever runs your websites can do it from the command line:

```sh
bin/sightpress site:user:phone:reset riverside ada@example.org
```

## Add your new number

On **My account → Mobile number and text messages**, add your new number and type the code we send to it (see
[Add your mobile number](add-your-mobile-number.md)). Adding a first number never needs a code to an old one.
