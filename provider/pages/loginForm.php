<?php
/*
    NameID, a namecoin based OpenID identity provider.
    Copyright (C) 2013-2016 by Daniel Kraft <d@domob.eu>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/* Page layout for the login form.  */

if (!isset ($fromIndex) || $fromIndex !== "yes")
  die ("Invalid page load.\n");

?>

<h1>Sign In</h1>

<?php
$msg->finish ();
?>

<div class="hideWithAddon">
  <div class="alert alert-info">Manually signing the challenge is very
troublesome.  Take a look at our <a href="?view=addon"><b>add-on</b></a>!</div>
  <div class="alert alert-warning">Note that the login process was
changed since version 0.4a of the add-on, so you may need to
<a href="?view=addon">upgrade</a> if you still have the old version.</div>
</div>

<p>In order to sign in with your Namecoin identity, you have to
<a href="https://en.wikipedia.org/wiki/Digital_signature">sign</a>
a <b>challenge message</b> with the Namecoin address
holding your identity.  This process does not reveal your private key
to this website or anyone else, but can be used to prove that you are indeed
the owner of your identity.</p>

<form id="loginForm" method="post" class="offset1"
      action="?action=login&amp;view=login">

<input type="hidden" name="version" value="1" />

<div class="input-prepend">
  <label class="add-on" for="identity"><?php
echo $html->escape ($namePrefix);
?>/</label>
  <input type="text" name="identity" id="identity" />
</div>

<div class="hideWithAddon">
  <p><label for="message">Please use <code>namecoind signmessage</code> with the
address corresponding to your identity to sign the following
message:</label></p>
  <textarea id="message" readonly="readonly">Enter your ID to see the message to sign.</textarea>
  <textarea name="signature" id="signature"
            placeholder="Put the signature here."></textarea>
</div>

<p>
  <button type="submit" name="login" class="btn btn-primary">Sign In</button>
  <button type="submit" name="cancel" class="btn" id="cancel">Cancel</button>
</p>

<!-- Store login data into the page for retrieval from JS.  -->
<input type="hidden" name="nonce" id="nonce"
       value="<?php echo $html->escape ($loginNonce); ?>" />
<input type="hidden" name="url" id="url"
       value="<?php echo $html->escape ($serverUri); ?>" />

</form>

<!-- Load JS script.  -->
<script type="text/javascript" src="js/NameId.js"></script>
<script type="text/javascript" src="js/LoginForm.js"></script>

<noscript class="hideWithAddon">Since you have JavaScript disabled, you will
have to construct the message to sign on your own.  Good luck with that!
The nonce is: <?php echo $html->escape ($loginNonce); ?></noscript>
