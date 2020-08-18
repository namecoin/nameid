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

/* Perform the user authentication on login requests.  */

require_once ("namecoin_interface.inc.php");

/**
 * Exception to signal login failure.
 */
class LoginFailure extends RuntimeException
{

  /**
   * Construct with the given message.
   * @param msg The error message.
   */
  public function __construct ($msg)
  {
    parent::__construct ("Login failed: $msg");
  }

}

/**
 * Check user login signatures.
 */
class Authenticator
{

  /** Namecoind connection we have.  */
  private $nc;
  /** The server's URI for which login messages should be formulated.  */
  private $serverUri;

  /**
   * Construct with the given namecoin interface.
   * @param nc The namecoind connection to use.
   * @param uri Server's URI for which login messages should be.
   */
  public function __construct (NamecoinInterface $nc, $uri)
  {
    $this->nc = $nc;
    $this->serverUri = $uri;
  }

  /**
   * Try to log a user in with the given data.  This method throws if the
   * login fails and succeeds (plus TRUE return value) if the user
   * is indeed authenticated.
   * @param id The user's id.
   * @param signature The user's signature.
   * @param nonce The login nonce for the current session.
   * @return True if the login is ok.
   * @throws LoginFailure if the login is not ok.
   */
  public function login ($id, $signature, $nonce)
  {
    $msg = $this->getChallenge ($id, $nonce);

    /* Get data for the name first.  This is then used to check against all
       allowable signer addresses.  */
    try
      {
        $data = $this->nc->getIdData ($id);
        assert (isset ($data->address));
        $addr = $data->address;
        
        $value = $this->nc->getIdValue ($id);
      }
    catch (NameNotFoundException $exc)
      {
        $prefix = $this->nc->getNamespace ();
        throw new LoginFailure ("The identity $prefix/$id does not exist.");
      }

    /* Perform actual value checks.  */

    if ($this->nc->isLegacyAddress ($addr)
        && $this->nc->verifyMessage ($addr, $msg, $signature))
      return TRUE;

    if ($value !== NULL && isset ($value->signer))
      {
        if (is_string ($value->signer))
          $arr = array ($value->signer);
        else if (is_array ($value->signer))
          $arr = $value->signer;
        else
          $arr = array ();

        foreach ($arr as $addr)
          if ($this->nc->isLegacyAddress ($addr)
              && $this->nc->verifyMessage ($addr, $msg, $signature))
            return TRUE;
      }

    throw new LoginFailure ("The signature is invalid.");
  }

  /**
   * Build up the challenge message.
   * @param id The identity of the user.
   * @param nonce The nonce used.
   * @return The challenge message that should be signed.
   */
  private function getChallenge ($id, $nonce)
  {
    /* This must of course be in sync with the JavaScript
       code on the loginForm page!  */

    $prefix = $this->nc->getNamespace ();
    $fullId = "{$this->serverUri}?name=" . urlencode ($id);

    return "login $fullId $nonce";
  }

}

?>
