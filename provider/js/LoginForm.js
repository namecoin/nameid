/*
    NameID, a namecoin based OpenID identity provider.
    Copyright (C) 2013-2016 by Daniel Kraft <d@domob.eu>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/* Page-specific JS code for loginForm.  This is here instead of a <script>
   tag to allow setting the CSP header to no-inline.  */

/* The NameId object used.  Will be constructed in onload event.  */
var nameid = null;

/* Update the challenge field.  */
function updateChallenge (evt)
{
  var id = document.getElementById ("identity").value;
  var msg = nameid.getChallenge (id);
  document.getElementById ("message").value = msg;
}

/* Try to sign the challenge message via the add-on.  */
function signChallenge ()
{
  var id = document.getElementById ("identity").value;
  var signature = nameid.signChallenge (id);

  if (signature === null)
    return false;

  document.getElementById ("signature").value = signature;
  return true;
}

/* Set up everything on page load.  */
function setup (evt)
{
  var url = document.getElementById ("url").value;
  var nonce = document.getElementById ("nonce").value;

  nameid = new NameId (url, nonce);
  nameid.requestApi ();

  if (nameid.hasApi ())
    {
      var body = document.getElementsByTagName ("body");
      body[0].className = "withAddon";
    }

  var idEntry = document.getElementById ("identity");
  idEntry.addEventListener ("change", updateChallenge);

  var cancelClicked = false;
  function handleSubmit (evt)
    {
      if (cancelClicked)
        return;

      var res = signChallenge ();
      if (!res)
        evt.preventDefault ();
    }
  function handleCancel (evt)
    {
      cancelClicked = true;
    }
  if (nameid.hasApi ())
    {
      var form = document.getElementById ("loginForm");
      form.addEventListener ("submit", handleSubmit);
      var cancel = document.getElementById ("cancel");
      cancel.addEventListener ("click", handleCancel);
    }
}
window.addEventListener ("load", setup);
