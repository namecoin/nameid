#!/bin/bash
#   NameID, a namecoin based OpenID identity provider.
#   Copyright (C) 2013-2015 by Daniel Kraft <d@domob.eu>
#
#   This program is free software: you can redistribute it and/or modify
#   it under the terms of the GNU General Public License as published by
#   the Free Software Foundation, either version 3 of the License, or
#   (at your option) any later version.
#
#   This program is distributed in the hope that it will be useful,
#   but WITHOUT ANY WARRANTY; without even the implied warranty of
#   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with this program.  If not, see <http://www.gnu.org/licenses/>.

# Compile the add-on into an XPI.

dir=login@nameid.org
zipfile=NameIdLogin-0.7.1.xpi

files="bootstrap.js chrome.manifest install.rdf icon.png"
for file in Namecoind.js NameIdAddon.js \
            PrefHandler.js TrustManager.js Utils.js \
            preferences.js preferences.xul
do
  files+=" content/$file"
done

rm $zipfile
(cd $dir; zip ../$zipfile $files)
