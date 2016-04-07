<?php
/*
    NameID, a namecoin based OpenID identity provider.
    Copyright (C) 2013-2016 by Daniel Kraft <d@domob.eu>

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

/* Page layout to show the identity page.  */

if (!isset ($fromIndex) || $fromIndex !== "yes")
  die ("Invalid page load.\n");

/* Sanitise a link.  This makes sure that it starts with 'http://'
   or 'https://', in order to prevent in particular javascript: links
   to be inserted by an attacker.  */
function sanitiseLink ($href)
{
  $allowed = array ("http://", "https://");
  foreach ($allowed as $prefix)
    if (substr ($href, 0, strlen ($prefix)) === $prefix)
      return $href;

  return "http://$href";
}

/**
 * Helper routine to check a key fingerprint and format it nicely.  This
 * makes all hex characters upper case, removes spaces and colons, and
 * puts back spaces to group the characters in a uniform way.  Finally,
 * it formats the final digits (as in the usual GPG fingerprint key ID)
 * in <strong> tags.
 * @param fpr The fingerprint to format.
 * @return The processed fingerprint.
 */
function formatKeyFingerprint ($fpr)
{
  $fpr = strtoupper ($fpr);
  $fpr = preg_replace ("/[ :]/", "", $fpr);

  if (!preg_match ("/^[0-9A-F]{40}$/", $fpr))
    return NULL;

  $fpr = preg_replace ("/(.{4})/", "$1 ", $fpr);
  $fpr = preg_replace ("/(.{4} .{4}) $/", "<strong>$1</strong>", $fpr);

  return $fpr;
}

/* ************************************************************************** */
/* Field handlers.  */

/**
 * Basic class for a field handler.
 */
abstract class FieldHandler
{

  /** Label to display.  */
  private $label;

  /**
   * Construct it.  It only sets the label.  Information about the
   * actual data to display is handled by subclasses.
   * @param label The label to display.
   */
  public function __construct ($label)
  {
    $this->label = $label;
  }

  /**
   * Print out HTML for this field in the <dl>.
   * @param html HtmlOutput object.
   * @param data JSON data of the id value.
   */
  public function output (HtmlOutput $html, $data)
  {
    $content = $this->processContent ($html, $data);
    if (is_string ($content))
      {
        assert ($content !== NULL);
        echo "<dt>" . $html->escape ($this->label) . "</dt>\n";
        echo "<dd>$content</dd>\n";
      }
  }

  /**
   * Subclasses must implement extraction of the "content" (as HTML text)
   * to display.  NULL should be returned if this field is not served
   * by the given data.
   * @param html HtmlOutput object to use.
   * @param data JSON data of the id value.
   * @return Processed content.
   */
  abstract protected function processContent (HtmlOutput $html, $data);

}

/**
 * Basic field class that reads its value from a defined JSON field.
 * It is still abstract and doesn't fully implement the output.
 */
abstract class BasicField extends FieldHandler
{

  /** Key in the JSON data to extract.  */
  private $key;

  /**
   * Construct the field.
   * @param label The label to display.
   * @param key The key for data extraction.
   */
  public function __construct ($label, $key)
  {
    parent::__construct ($label);
    $this->key = $key;
  }

  /**
   * Get the content to show.  This extracts the field value (if present)
   * and calls another abstract method to interpret it.
   * @param html HtmlOutput object to use.
   * @param data JSON data of the id value.
   * @return Processed content.
   */
  protected function processContent (HtmlOutput $html, $data)
  {
    if (!isset ($data->{$this->key}))
      return NULL;

    return $this->processValue ($html, $data->{$this->key});
  }

  /**
   * Subclasses must implement interpretation of the value.
   * Returning NULL signals that the value can not be interpreted.
   * @param html HtmlOutput object to use.
   * @param val Value of the field's key in the JSON object.
   * @return Processed content.
   */
  abstract protected function processValue (HtmlOutput $html, $val);

}

/**
 * Simple field that prints its value literally.  It can be configured to
 * support arrays and object values, iterating over the fields.
 */
class SimpleField extends BasicField
{

  /** Allow array values?  */
  private $arrays;
  /** Allow object values?  */
  private $objects;

  /**
   * Construct it.
   * @param label The label to display.
   * @param key The key for data extraction.
   * @param withArrays Allow arrays?
   * @param withObjects Allow objects?
   */
  public function __construct ($label, $key, $withArrays = false,
                               $withObjects = false)
  {
    parent::__construct ($label, $key);
    $this->arrays = $withArrays;
    $this->objects = $withObjects;
  }

  /**
   * Implement the process value function.
   * @param html HtmlOutput object to use.
   * @param val Value of the field's key in the JSON object.
   * @return Processed content.
   */
  protected function processValue (HtmlOutput $html, $val)
  {
    if ($this->arrays && is_array ($val))
      {
        $entries = array ();
        foreach ($val as $v)
          {
            $cur = $this->processSimple ($html, $v);
            if (is_string ($cur))
              {
                assert ($cur !== NULL);
                array_push ($entries, $cur);
              }
          }
        return implode (", ", $entries);
      }

    if ($this->objects && is_object ($val))
      {
        $entries = array ();
        foreach ($val as $key => $v)
          {
            $cur = $this->processSimple ($html, $v);
            if (is_string ($key) && is_string ($cur))
              {
                assert ($cur !== NULL);
                $k = $html->escape ($key);
                array_push ($entries, "$cur ($k)");
              }
          }
        return implode (", ", $entries);
      }

    return $this->processSimple ($html, $val);
  }

  /**
   * Process a simple value (not array or object).
   * @param html HtmlOutput object to use.
   * @param val The value.
   * @return Processed content.
   */
  protected function processSimple (HtmlOutput $html, $val)
  {
    if (is_string ($val))
      return $html->escape ($val);

    return NULL;
  }

}

/**
 * Field for website.
 */
class WebsiteField extends SimpleField
{

  /**
   * Get the content to show.
   * @param html HtmlOutput object to use.
   * @param val The value.
   * @return Processed content.
   */
  protected function processSimple (HtmlOutput $html, $val)
  {
    if (!is_string ($val))
      return NULL;

    $text = $html->escape ($val);
    $link = $html->escape (sanitiseLink ($val));

    return "<a href='$link'>$text</a>";
  }

}

/**
 * Field for email or crypto-addresses.  We create a link and prepend
 * a given "protocol" to it.
 */
class ProtocolledField extends SimpleField
{

  /** The protocol to prepend.  */
  private $protocol;

  /**
   * Construct the field.
   * @param label The label to display.
   * @param key The key for data extraction.
   * @param protocol The protocol to prepent.
   * @param withArrays Allow arrays?
   * @param withObjects Allow objects?
   */
  public function __construct ($label, $key, $protocol,
                               $withArrays = false, $withObjects = false)
  {
    parent::__construct ($label, $key, $withArrays, $withObjects);
    $this->protocol = $protocol;
  }

  /**
   * Get the content to show.
   * @param html HtmlOutput object to use.
   * @param val The value.
   * @return Processed content.
   */
  protected function processSimple (HtmlOutput $html, $val)
  {
    if (!is_string ($val))
      return NULL;

    $proto = $html->escape ($this->protocol);
    $val = $html->escape ($val);
    return "<a href='$proto:$val'>$val</a>";
  }

}

/**
 * Field for OTR key fingerprint.
 */
class OTR_Field extends SimpleField
{

  /**
   * Get the content to show.
   * @param html HtmlOutput object to use.
   * @param val The value.
   * @return Processed content.
   */
  protected function processSimple (HtmlOutput $html, $val)
  {
    if (!is_string ($val))
      return NULL;

    return formatKeyFingerprint ($val);
  }

}

/**
 * Field for GPG fingerprint.
 */
class GPG_Field extends BasicField
{

  /**
   * Get the content to show.
   * @param html HtmlOutput object to use.
   * @param val The value.
   * @return Processed content.
   */
  protected function processValue (HtmlOutput $html, $val)
  {
    if (!is_object ($val))
      return NULL;
    if (!isset ($val->v) || $val->v !== "pka1")
      return NULL;
    if (!isset ($val->fpr))
      return NULL;

    $formatted = formatKeyFingerprint ($val->fpr);
    if ($formatted === NULL)
      return NULL;

    $href = NULL;
    $content = "";
    if (isset ($val->uri))
      {
        $href = $val->uri;
        $href = $html->escape (sanitiseLink ($href));
        $content .= "<a href='$href'>";
      }
    $content .= $formatted;
    if ($href !== NULL)
      $content .= "</a>";

    return $content;
  }

}


/* ************************************************************************** */

?>

<h1><?php echo $html->escape ("$namePrefix/$identityName"); ?></h1>

<?php

$fields = array (new SimpleField ("Real Name", "name"),
                 new SimpleField ("Nickname", "nick", true, true),
                 new WebsiteField ("Website", "website", true, true),
                 new ProtocolledField ("Email", "email", "mailto", true, true),
                 new GPG_Field ("OpenPGP", "gpg"),
                 new OTR_Field ("OTR Key", "otr", true, true),
                 new SimpleField ("Bitmessage", "bitmessage", true, true),
                 new SimpleField ("XMPP", "xmpp", true, true),
                 new SimpleField ("Twister", "twister", true, true),
                 new ProtocolledField ("Bitcoin", "bitcoin", "bitcoin",
                                       true, true),
                 new ProtocolledField ("Namecoin", "namecoin", "namecoin",
                                       true, true),
                 new ProtocolledField ("Huntercoin", "huntercoin",
                                       "huntercoin", true, true),
                 new ProtocolledField ("Litecoin", "litecoin", "litecoin",
                                       true, true),
                 new ProtocolledField ("Faircoin", "faircoin", "faircoin",
                                       true, true),
                 new SimpleField ("Auroracoin", "auroracoin", true, true),
                 new SimpleField ("Peercoin", "ppcoin", true, true));

if ($identityPage)
  {
?>

<p>The Namecoin identity
<code><?php echo $html->escape ("$namePrefix/$identityName"); ?></code> has some
public profile information registered:</p>

<?php
    echo "<dl class='dl-horizontal'>\n";
    foreach ($fields as $f)
      $f->output ($html, $identityPage);
    echo "</dl>\n";
  }
else
  {
?>
<p>There's no public information for
<code><?php echo $html->escape ("$namePrefix/$identityName"); ?></code>.</p>
<?php
  }
?>
