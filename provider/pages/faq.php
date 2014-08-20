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

/* Page displaying FAQs.  */

if (!isset ($fromIndex) || $fromIndex !== "yes")
  die ("Invalid page load.\n");

?>

<h1>FAQs</h1>

<div class="well well-small">
  <p class="faq">Should I use this version or <a href="https://nameid.org">NameID.org?</a></p>
  <p>If you need to ask this question then you should definitely be at <a href="https://nameid.org">NameID.org</a>. This is a testnet version, suitable only for testing. Namecoin Testnet is not as secure as the main network, so <b>you may lose your testnet ID or have it stolen at any time.</b> The rest of this FAQ relates to the mainnet version at <a href="https://nameid.org">NameID.org</a>, any assumptions made about safety are wrong for this testnet version. </p>
</div>

<div class="well well-small">
  <p class="faq">What is Namecoin?</p>
  <p><a href="http://namecoin.info/">Namecoin</a> is a peer-to-peer, completely
decentralised system based on <a href="https://bitcoin.org/">Bitcoin</a>
technology that allows everyone to register <i>names</i>.  After you own
a name, no-one can take it from you without your consent, and you can associate
data with it that everyone can be sure originated from you but which can neither
be forged nor censored by unauthorised attackers.</p>
</div>

<div class="well well-small">
  <p class="faq">How do Namecoin identities work?</p>
  <p>In the Namecoin system, there are multiple possible uses for names:  The
most common today is for
<a href="http://wiki.namecoin.info/?title=Domain_Name_Specification_2.0"><b>domain
names</b></a>
(these are names with the <code>d/</code> prefix), but another possible
use-case is that of
<a href="http://wiki.namecoin.info/?title=Identity"><b>identities</b></a>:
Those are names of the form <code>id/your-nick</code>, and they are
particularly interesting because you can, for instance, store your public keys
for <a href="http://gnupg.org/">GnuPG</a>, your
<a href="https://bitmessage.org/">Bitmessage address</a> or other things
in their value field, so that you only need to tell someone your
(easily remembered) identity nick, and they can later access your keys
to send you encrypted messages and can be assured that the keys are indeed
yours.</p>
</div>

<div class="well well-small">
  <p class="faq">What is OpenID?</p>
  <p><a href="https://openid.net/">OpenID</a> is an open internet standard
that specifies a protocol for some webserver (your <i>identity provider</i>)
to authenticate you to another site (the <i>consumer</i>).  This is meant
as a solution to the problem of an ever-increasing amount of usernames and
passwords someone has to remember on the internet for accounts at a multitude
of sites.  With OpenID, you only ever need one account at some
trusted identity provider, and can then use it to sign into (or create
accounts at) millions of other OpenID-enabled &quot;consumer&quot; sites.</p>
</div>

<div class="well well-small">
  <p class="faq">So, how do Namecoin and OpenID relate?</p>
  <p><b>NameID</b> brings both concepts together:  It is an identity provider,
but you don't (and can't) have to create an account or even
remember a password.  Instead, you need a Namecoin identity (that is,
at the very least some <code>id/</code> name which you own, no matter
what values you have stored with it).  The password is replaced by
a <b><a href="https://en.wikipedia.org/wiki/Digital_signature">digital
signature</a></b> with the private key associated to your name.</p>
</div>

<div class="well well-small">
  <p class="faq">Is it safe?</p>
  <p>With the digital signature
it is possible to prove to <b>NameID</b> that you are really the
owner of the identity you claim, without revealing any compromising data.
Even if <b>NameID</b> was malicious or the site cracked, you only ever run
the risk of an attacker signing into other OpenID sites with your
identity until you revoke it.  No-one is ever able to steal
the Namecoin identity
from you as long as your computer and Namecoin wallet are
safe.  Furthermore, at <b>NameID</b>
there is no list of passwords or account details that can ever be stolen.
All that is needed for us to identify you is the Namecoin blockchain, so
there's no need to keep any sensitive information around.</p>
</div>

<div class="well well-small">
  <p class="faq">How do I sign into an OpenID-enabled site?</p>
  <p>Simply enter <code>https://nmctest.net/nameid/</code> into the
login-box.  You will be redirected to <b>NameID</b> where you can log in
with your name, and if that is successful, you will be returned to the
OpenID consumer site, where you are then authenticated with your identity.</p>
</div>

<div class="well well-small">
  <p class="faq">What do I need in order to use NameID?</p>
  <p>First of all, you need a 
<a href="http://wiki.namecoin.info/?title=Identity"><b>Namecoin
identity</b></a>,
and need the wallet that owns it on your local system.  Second, you need
<a href="https://github.com/namecoin/namecoin"><b>Namecoin</b></a>
installed and running with the <code>server=1</code> configuration flag,
and need to be able to perform
<code>signmessage</code> commands with it.  Don't worry though, you can
install the <a href="?view=addon"><b>NameID Easy Login</b></a> add-on for
<a href="https://www.mozilla.org/">Mozilla</a> browsers, which takes
care of the signing for you.  And finally, you need some OpenID-enabled
sites you want to sign into.</p>
</div>

<div class="well well-small">
  <p class="faq">Where do I get a Namecoin identity from?</p>
  <p>Please consult the <a href="http://namecoin.info/">Namecoin website</a>
for more details, or take a look at the
<a href="https://forum.namecoin.info/">Namecoin forum</a> for help.</p>
</div>

<div class="well well-small">
  <p class="faq">Do you store any data about me?</p>
  <p>No!  But I'm glad you asked and care about protecting your data,
which is a good thing.  All data <b>NameID</b> needs to authenticate you
is already available via the Namecoin blockchain (and it is up to
you what data you want to share there with the public), so we don't have
to store anything.  It is true that <b>NameID</b> gets to know all OpenID
sites you sign into, but that is inevitable by the design of OpenID.  We
don't however store any of that data except standard server log files, which
are also discarded regularly (about every month).</p>
</div>

<div class="well well-small">
  <p class="faq">Can I trust you?  Is it free?</p>
  <p>The answer to the second question is: <b>Yes, absolutely!</b>
The service provided is free as in free beer, I run <b>NameID</b>
as a service to the Namecoin community and to help it grow.  I don't
want to earn any money from it.  Furthermore, <b>NameID</b> is also
<b>free as in freedom</b> with the
<a href="https://github.com/John-Kenney/nameid">source code</a>
available to anyone who wants to check it out or even reuse it for
own projects under the
<a href="https://www.gnu.org/licenses/agpl-3.0.html"><b>AGPL v3</b></a>.</p>
  <p>For the first question, you have to decide on your own whether or
not you trust me based on the information on this page.  In any case you can
also just
<a href="https://github.com/John-Kenney/nameid">grab the source</a> and
set up <b>your own server</b> if you like the idea!</p>
</div>

<div class="well well-small">
  <p class="faq">How can I use NameID in my own project?</p>
  <p>As mentioned in multiple other answers on this page, <b>NameID</b> is
free software and the code can be obtained from
<a href="https://github.com/John-Kenney/nameid">my github</a>.  You can use this
code more or less directly to set up a &quot;clone&quot; of <b>NameID</b>
on <b>your own server</b>.  Alternatively, you can even use the
<b>authentication code</b> to implement Namecoin login
<b>directly into your webservice</b> without the need to go through
<b>NameID</b> or any other third-party!  See <code>README.libauth</code>
in the sources for a how-to on this.</p>
</div>

