<?php

/**
  * This plugin lets you impersonate another user using a master login. Only works with dovecot.
  *
  * http://wiki.dovecot.org/Authentication/MasterUsers
  *
  * @author Cor Bosman (roundcube@wa.ter.net)
  */

class dovecot_impersonate extends rcube_plugin {

  public function init()
  {
    $this->add_hook('storage_connect', array($this, 'impersonate'));
    $this->add_hook('managesieve_connect', array($this, 'impersonate'));
    $this->add_hook('authenticate', array($this, 'login'));
    $this->add_hook('sieverules_connect', array($this, 'impersonate_sieve'));
  }

  function login($data) {
    // find the seperator character
    $rcmail = rcmail::get_instance();
    $this->load_config();

    $seperator = $rcmail->config->get('dovecot_impersonate_seperator', '*');

    if(strpos($data['user'], $seperator)) {
      $arr = explode($seperator, $data['user']);
      if(count($arr) == 2) {
        $data['user'] = $arr[0];
        $_SESSION['plugin.dovecot_impersonate_master'] = $seperator . $arr[1];

        // should we notify someone ?
        $notify = $rcmail->config->get('dovecot_impersonate_notify');
        if ( !empty($notify) ) {
          $notify = str_replace('{{REMOTE_ADDR}}', $_SERVER['REMOTE_ADDR'], $notify);
          $notify = str_replace('{{SERVER_ADDR}}', $_SERVER['SERVER_ADDR'], $notify);
          $notify = str_replace('{{ACCOUNT}}', $arr[0], $notify);
          $notify = str_replace('{{MASTER}}', $arr[1], $notify);
          system($notify);
        }
      }

    }
    return($data);
  }

  function impersonate($data) {
    if(isset($_SESSION['plugin.dovecot_impersonate_master'])) {
      $data['user'] = $data['user'] . $_SESSION['plugin.dovecot_impersonate_master'];
    }
    return($data);
  }

  function impersonate_sieve($data) {
    if(isset($_SESSION['plugin.dovecot_impersonate_master'])) {
      $data['username'] = $data['username'] . $_SESSION['plugin.dovecot_impersonate_master'];
    }
    return($data);
  }

}
