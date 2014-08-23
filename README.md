**_hardly in development_**

#SFWC

SFWC is a tiny php library for preventing CSRF attacks.

##example usage on forms

###handler

```php
<?php
include '/home/yunusemre/sfwc/sfwc.php';

SFWC::get()->initToken('login_form');

if($_POST) {
    if(SFWC::get()->controlToken($_POST["csrf_token"], 'login_form')) {
        echo "yello there, thanks for your login information.";
    }
    else {
        echo "thanks for your effort but i cannot accept this POST request.";
    }
}

?>
```

###form

```php
<form method="POST">
  <input type="text" value="username"> username <br />
  <input type="text" value="password"> password <br />
  <?php echo SFWC::get()->getTokenAsHtml() ?>
  <input type="submit" value="submit">
</form>
```
