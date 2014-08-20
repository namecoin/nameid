<?php
/*
    NameID, a namecoin based OpenID identity provider.
    Copyright (C) 2013-2014 by Daniel Kraft <d@domob.eu>

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

/* Page content about the Mozilla add-on.  */

if (!isset ($fromIndex) || $fromIndex !== "yes")
  die ("Invalid page load.\n");

?>

<h1>NameID Easy Login</h1>

<div class="alert alert-warning"><b>NameID</b> and in particular the
add-on are still in an early, experimental phase.  So please be warned,
although everything seemed to work fine in our tests.</div>

<p>Since the steps required for a fully manual sign-in are quite complicated
and troublesome, you can <b>automate them</b> by using the
<b>NameID add-on</b> for <a href="https://www.mozilla.org/">Mozilla</a>
browsers.  Try it out:</p>

<div class="offset1"><a class="btn btn-success"
      href="NameIdLogin-0.5a.xpi">NameID Easy Login<br />
<small>Version 0.5a</small></a></div>

<p class="padTop">When the add-on is installed, you have to make sure that the
<a href="https://github.com/namecoin-qt/namecoin-qt"><b>Namecoin-Qt</b></a>
daemon is running, and check the connection settings in the add-on's
preferences dialog.  Those must match your <code>namecoin.conf</code>
file.  When everything is set up, the add-on will allow you to automate
the signing procedure whenever it recognizes a login form for <b>NameID</b>.
It may ask for your Namecoin wallet passphrase in case you have an encrypted
and locked wallet, but appart from that, you only ever have to enter
the identity you want to use and submit.</p>
