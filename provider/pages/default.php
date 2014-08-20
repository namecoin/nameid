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

/* Page layout to show if nothing else applied.  */

if (!isset ($fromIndex) || $fromIndex !== "yes")
  die ("Invalid page load.\n");

?>

<h1>Namecoin + OpenID = NameID!</h1>

<div class="alert alert-warning"><b>NameID</b> is still a very early
experiment, so <b>use at your own risk</b>!
The <a href="?view=faq">FAQs</a> have some more information.</a></div>

<p><a href="http://namecoin.info/"><b>Namecoin</b></a>
is an amazing technology that allows anyone to register arbitrary
<b>human-readable</b> names in a <b>completely decentralised</b>
but <b>nevertheless secure</b> fashion.
(Ever heard of
<a href="https://en.wikipedia.org/wiki/Zooko%27s_triangle"><b>Zooko's
triangle</b></a>?  It can be argued that Namecoin is
a prime <b>counter-example</b> to it.)
These names can be used
to create <a href="http://wiki.namecoin.info/?title=Identity"><b>online
identities</b></a>.
<i>With NameID, you can instantly turn your Namecoin identity
into an <a href="https://openid.net/">OpenID</a>,
and use it to readily sign into millions of OpenID-enabled websites!</i>
Check out the <a href="?view=faq"><b>FAQs</b></a> to learn more.</p>

<p>Already have a Namecoin identity?  Then
<a href="?view=login"><b>log in</b></a>!
Alternatively if you want to check out some other Namecoin identities,
take a look at <a href="?name=jkenney">mine</a> or query for an arbitrary
name:</p>
<form method="get" action="?" class="offset1">
  <div class="input-prepend input-append">
    <label for="name" class="add-on"><?php
echo $html->escape ($namePrefix);
?>/</label>
    <input type="text" id="name" name="name" class="input-medium" />
  <button class="btn btn-primary" type="submit">Query</button>
  </div>
</form>
