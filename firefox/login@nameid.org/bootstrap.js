/*
    NameID, a namecoin based OpenID identity provider.
    Copyright (C) 2013 by Daniel Kraft <d@domob.eu>

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

/* Firefox plugin bootstrapping code.  */

Components.utils.import ("resource://gre/modules/Services.jsm");

/**
 * Utility function to log a message to the ErrorConsole.  This is used
 * for debugging and may be disabled in release code.
 * @param msg The message to send.
 */
function log (msg)
{
  Services.console.logStringMessage (msg);
}

/**
 * Utility function to assert a fact for debugging.
 * @param cond The condition that must hold.
 */
function assert (cond)
{
  if (!cond)
    throw "Assertion failed!";
}

/* ************************************************************************** */

/**
 * The main object encapsulating the addon's state.
 */
function NameIdAddon ()
{
  this.register ();
}

NameIdAddon.prototype =
  {

    /**
     * Initialise the observer to "start" this addon.
     */
    register: function ()
    {
      Services.obs.addObserver (this, "document-element-inserted", false);
    },

    /**
     * Stop the observer on shutdown.
     */
    unregister: function ()
    {
      Services.obs.removeObserver (this, "document-element-inserted");
    },

    /**
     * Observe events, in particular loads of new documents that have to be
     * scanned for signs of a NameID login form.
     * @param subject Subject of the event.
     * @param topic Topic of the event.
     * @param data Further data.
     */
    observe: function (subject, topic, data)
    {
      if (topic !== "document-element-inserted")
        return;

      log ("Observing page load: " + subject.URL);
      var me = this;
      function handler (evt)
        {
          me.scanPage (evt.target.ownerDocument);
        }
      subject.addEventListener ("load", handler, true);
    },

    /**
     * Handle events of fully loaded pages, which are then scanned
     * for signs of a NameID form.
     * @param doc The page's document.
     */
    scanPage: function (doc)
    {
      var nonceEl = doc.getElementById ("nameid-nonce");
      var uriEl = doc.getElementById ("nameid-uri");
      if (!nonceEl || !uriEl)
        {
          log ("Found no NameID login form.");
          return;
        }

      /* Ask the user about trust for this page.  */
      var text = "The page at '" + doc.URL + "' contains a NameID"
                 + " login form.  Do you want to permit it to automatically"
                 + " sign challenge messages for you?";
      /* XXX: enable this after debugging!
      var ok = Services.prompt.confirm (null, "Allow NameID?", text);
      if (!ok)
        return;
      */

      this.nonce = nonceEl.textContent;
      this.uri = uriEl.textContent;
      log ("Found NameID login form with nonce: " + this.nonce);

      /* Hide the manual entry forms.  */
      doc.documentElement.className = "withAddon";

      /* Connect a handler to intercept the form submit.  Note that we don't
         want to intercept if the cancel button was clicked.  */
      var form = doc.getElementById ("loginForm");
      var cancel = doc.getElementById ("cancel");
      this.cancelClicked = false;
      var me = this;
      function handlerSubmit (e)
        {
          if (!me.cancelClicked)
            me.interceptSubmit (doc);
        }
      function handlerCancel (e)
        {
          me.cancelClicked = true;
        }
      form.addEventListener ("submit", handlerSubmit, true);
      cancel.addEventListener ("click", handlerCancel, true);
    },

    /**
     * Intercept the form submit.
     * @param doc The document we're on.
     */
    interceptSubmit: function (doc)
    {
      var idEntry = doc.getElementById ("identity");
      var id = idEntry.value;
      var msg = this.getChallenge (id);
      log ("Attempting to sign challenge: " + msg);

      /* TODO: Implement real signing and update form data.  */
    },

    /**
     * Construct the challenge message for a given ID.  Page URI and
     * nonce are stored already as variables.
     * @param id The user entered ID.
     * @return The full challenge message.
     */
    getChallenge: function (id)
    {
      /* This must of course be in sync with the PHP code as well as
         the "ordinary" page JavaScript!  */

      var fullId = this.uri + "?name=" + encodeURIComponent (id);
      var msg = "login " + fullId + " " + this.nonce;

      return msg;
    }

  };

/* ************************************************************************** */

/** The instance of NameIdAddon used.  */
var instance = null;

/**
 * Bootstrap the addon.
 * @param data Bootstrap data.
 * @param reason Why startup is called.
 */
function startup (data, reason)
{
  instance = new NameIdAddon ();
}

/**
 * Disable the addon.
 * @param data Bootstrap data.
 * @param reason Why shutdown is called.
 */
function shutdown (data, reason)
{
  instance.unregister ();
  instance = null;
}

/**
 * Install the addon.
 * @param data Bootstrap data.
 * @param reason Why install is called.
 */
function install (data, reason)
{
  // Nothing to do.
}

/**
 * Uninstall the addon.
 * @param data Bootstrap data.
 * @param reason Why uninstall is called.
 */
function uninstall (data, reason)
{
  // Nothing to do.
}
