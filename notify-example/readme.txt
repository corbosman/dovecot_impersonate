This folder contains a set of files you can use to notify people when the dovecot's impersonate facility has been used.

- login-report.ini       : to be placed in /etc/dovecot/, contains initialisation variable for emails, 
- master-user-report.pl  : a script to be placed in /usr/local/bin, for instance. 
- post-login.sh          : to be placed for instance in /etc/dovecot/scripts


In dovecot, you need to specify the post login script, if not done already:

# The service name below doesn't actually matter.
service imap-postlogin {
  # all post-login scripts are executed via script-login binary
  executable = script-login /etc/dovecot/scripts/post-login.sh

  # the script process runs as the user specified here (v2.0.14+):
  user = $default_internal_user
  # this UNIX socket listener must use the same name as given to imap executable
  unix_listener imap-postlogin {
  }
}


