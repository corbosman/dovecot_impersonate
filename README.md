dovecot_impersonate
===================

This plugin lets you impersonate another user using the dovecot master user feature.

WARNING:  please only use for user support or similar operational issues.  I recommend you always get approval. Using this without consent may be illegal in some countries.  For more information about this feature read: http://wiki.dovecot.org/Authentication/MasterUsers

The default separator character used is '*', but you can set a different one
using the plugin config file.

How it works:

When you login to roundcube, you have to use your master user information:

Login: user*master
Password: password_of_master

The plugin then strips the master info from the form, so all preferences are correctly fetched for the user. (else it would try to find preferences for user*master). If you use any other plugins that use the authenticate hook, you might want to make this plugin the first plugin.


OLD VERSIONS
------------

This project has moved from Google Code to git. Older version are available at [Google Code](http://code.google.com/p/roundcube-plugins/downloads/list). This git repository is only for roundcube versions 0.8 and higher.

CONTACT
-------
Author:   Cor Bosman (cor@roundcu.be)

Bug reports through github (https://github.com/corbosman/dovecot_impersonate/issues)

LICENSE
-------

This plugin is distributed under the GNU General Public License Version 2.
Please read through the file LICENSE for more information about this license.

