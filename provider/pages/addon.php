<?php
/*
    NameID, a namecoin based OpenID identity provider.
    Copyright (C) 2013-2015 by Daniel Kraft <d@domob.eu>

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
extension are still in an early, experimental phase.  So please be warned,
although everything seemed to work fine in our tests.</div>

<div class="alert alert-info">This extension is still under development, and
some ideas for improvements have not yet been implemented&mdash;please
excuse a lot of rough edges.  Nevertheless, if you have any ideas, comments
or suggestions, please <a href="?view=contact">contact me</a>!</div>

<p>Since the steps required for a fully manual sign-in are quite complicated
and troublesome, you can <b>automate them</b> by using the
<b>NameID extension</b> for <a href="https://www.mozilla.org/">Mozilla</a>
browsers.  Try it out:</p>

<div class="offset1"><a class="btn btn-success"
      href="NameIdLogin-0.7.1.xpi">NameID Easy Login<br />
<small>Version 0.7.1</small></a></div>

<p class="padTop">Alternatively, you can also install the extension
from the
<a href="https://addons.mozilla.org/firefox/addon/nameid-easy-login/">official
<strong>Mozilla add-on catalog</strong></a>.</p>

<p>When the extension is installed, you have to make sure that the
<a href="https://github.com/namecoin/namecoin"><b>Namecoin</b></a>
daemon is running, and check the connection settings in the extension's
preferences dialog.  Those must match your <code>namecoin.conf</code>
file.  When everything is set up, the extension will allow you to automate
the signing procedure whenever it recognizes a login form for <b>NameID</b>.
It may ask for your Namecoin wallet passphrase in case you have an encrypted
and locked wallet, but appart from that, you only ever have to enter
the identity you want to use and submit.</p>
