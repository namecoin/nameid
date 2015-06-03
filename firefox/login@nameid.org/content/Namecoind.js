/*
    NameID, a namecoin based OpenID identity provider.
    Copyright (C) 2013,2015 by Daniel Kraft <d@domob.eu>

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

/* Interface to the used namecoind.  */

Components.utils.import ("chrome://nameid-login/content/Utils.js");
Components.utils.import ("resource://gre/modules/NetUtil.jsm");

var EXPORTED_SYMBOLS = ["Namecoind"];

/**
 * The main object encapsulating a namecoind connection.
 * @param pref Take preferences from here.
 */
function Namecoind (pref)
{
  var settings = pref.getConnectionSettings ();
  this.url = "http://" + settings.host + ":" + settings.port;
  this.user = settings.user;
  this.pass = settings.password;

  /* Increment ID always to ensure we get matching responses to all
     requests sent.  */
  this.nextID = 1;
}

Namecoind.prototype =
  {

    /**
     * Clean up everything.
     */
    close: function ()
    {
      // Nothing to be done for now.
    },

    /**
     * Call an RPC method.  If an error occurs, this method throws.
     * @param method The method to call.
     * @param args The arguments to pass it as array.
     * @param errHandler If given, call this method in cases of errors
     *                   reported from the RPC call instead of throwing.
     * @return The result in case of success.
     */
    executeRPC: function (method, args, errHandler)
    {
      var id = this.nextID++;
      var jsonData =
        {
          method: method,
          params: args,
          id: id
        };
      var res = this.requestHTTP (jsonData);

      /* Ensure the ID matches, should always be the case.  */
      assert (res.id === id);

      if (res.error !== null)
        {
          if (errHandler !== undefined && errHandler (res.error))
            return null;

          logError ("namecoind returned: " + res.error.message);
          throw "Namecoind failed to process the request successfully.";
        }

      return res.result;
    },

    /**
     * Send a JSON-RPC HTTP request to the server.
     * @param reqData Request (as JSON object) to send.
     * @return The returned JSON result.
     */
    requestHTTP: function (reqData)
    {
      var XMLHttpRequest
        = Components.Constructor ("@mozilla.org/xmlextras/xmlhttprequest;1",
                                  "nsIXMLHttpRequest");
      var req = new XMLHttpRequest ();
      req.withCredentials = true;
      req.open ("POST", this.url, false, this.user, this.pass);

      var reqString = JSON.stringify (reqData);
      log ("HTTP Request:\n" + reqString);

      req.responseType = "text";
      req.setRequestHeader ("Content-Type", "application/json");
      req.setRequestHeader ("Accept", "application/json");
      req.send (reqString);

      log ("HTTP Response status: " + req.status);
      log ("HTTP Response:\n" + req.responseText);

      assert (req.readyState === req.DONE);
      switch (req.status)
        {
        case 401:
          throw "The NameID add-on could not authenticate with the locally"
                + " running namecoind.";
          break;

        case 200:
        case 404:
        case 500:
          /* Everything ok.  500 means that the request was received fine,
             but an error occured during processing it.  This has to be
             handled elsewhere, though.  404 means that the requested method
             was not found.  */
          return JSON.parse (req.responseText);

        default:
          throw "Unknown error connecting to namecoind.";
          break;
        }
      assert (false);
    }

  };
