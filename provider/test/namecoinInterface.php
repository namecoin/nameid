#!/usr/bin/php
<?php
/*
    NameID, a namecoin based OpenID identity provider.
    Copyright (C) 2013-2020 by Daniel Kraft <d@domob.eu>

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

/* Test the namecoin interface.  */

require_once ("../lib/config.inc.php");
require_once ("../libauth/namecoin_interface.inc.php");

$rpc = new HttpNamecoin ($rpcHost, $rpcPort, $rpcUser, $rpcPassword);
$nc = new NamecoinInterface ($rpc, $namePrefix);

$res = $nc->getIdValue ("dani");
assert ($res->email === "d@domob.eu");

$thrown = FALSE;
try
  {
    $nc->getIdValue ("foo-bar-name-does-not-exist (hopefully still not)");
  }
catch (NameNotFoundException $exc)
  {
    $thrown = TRUE;
  }
assert ($thrown);

$val = $nc->getIdValue ("domob");
assert (isset ($val->email) && $val->email === "d@domob.eu");
$val = $nc->getIdValue ("invalid-json");
assert ($val === NULL);

$msg = "My test message to be signed!\nAnother line.";
$sig = "HCpqMVqWfYuT0WJ8WXyLhMXF5lnZ0DwphVcV0rr8bCNxONddYJtINIs5I8Bd"
       ."Mqrk4wKaGQTK8035q+IMW3JVP0g=";
$addr = "NFppu8bRjGVYTjyVrFZE9cGmjvzD6VUo5m";
$res = $nc->verifyMessage ($addr, $msg, $sig);
assert ($res);
$res = $nc->verifyMessage ($addr, "forged message", $sig);
assert (!$res);
$res = $nc->verifyMessage ($addr, $msg, base64_encode ("forged sig"));
assert (!$res);

$res = $nc->isLegacyAddress ($addr);
assert ($res);
$res = $nc->isLegacyAddress (array (5));
assert (!$res);
$res = $nc->isLegacyAddress ("");
assert (!$res);
$res = $nc->isLegacyAddress (NULL);
assert (!$res);
$res = $nc->isLegacyAddress ("invalid-address");
assert (!$res);
$res = $nc->isLegacyAddress ("6QYV9s9vvM3qH8eGr8cqvtQZLi8NBTm1TN");
assert (!$res);
$res = $nc->isLegacyAddress ("nc1q7x7j4jvqgs8f32ach38wum6zsnu06n9hd8np8d");
assert (!$res);

$nc->close ();

?>
