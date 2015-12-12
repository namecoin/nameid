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

/* Page content about contact details.  */

if (!isset ($fromIndex) || $fromIndex !== "yes")
  die ("Invalid page load.\n");

?>

<h1>Contact Details</h1>

<p>If you want to contact me, just take a look at my own
<a href="?name=domob"><b>profile page</b></a>.  Encrypted emails
or <a href="https://bitmessage.org/">Bitmessage</a> are explicitly
welcome.  If you want to be sure about the keys, you can also
check out the data yourself in the Namecoin blockchain
with my identity
<a href="?name=domob"><code>id/domob</code></a>.</p>

<p><b>NameID</b> is a project I run purely for the sake of bringing
Namecoin identities to a wider usage, I don't have any
commercial interests in it.  I have an IT service business registered
in Austria nevertheless (not because of this project, though), and you
can contact me at:</p>
<address class="offset1">Daniel Kraft<br />
Kirchbichl 42<br />
8920 Hieflau<br />
Austria<br /><br />
<a href="mailto:d@domob.eu">d@domob.eu</a><br />
GnuPG: <a href="domob.asc">A7330737</a>
</address>
