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
    $this->add_hook('smtp_connect', array($this, 'impersonate_smtp'));  
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

  /**
   *
   * This function will use the dovecot master username as the smtp_user if
   * the SMTP login is done using the roundcube username and password.
   *
   * If the configuration is adjusted this way the smtp server is probably
   * authenticating users against dovecot and we need to pass the right
   * username to make sure that passwords match.
   *
   * If the configuration is not using dovecot to authenticate we are not
   * breaking anything either, as the smtp server will not be able to
   * authenticate the user anyway (we are passing the master password
   * instead of the user password).
   *
   */

  function impersonate_smtp($data) {
    if(isset($_SESSION['plugin.dovecot_impersonate_master'])) {
      if ($data['smtp_user'] == '%u' and $data['smtp_pass'] == '%p') {
        $rcube = rcube::get_instance();
        $data['smtp_user'] = $rcube->get_user_name() . $_SESSION['plugin.dovecot_impersonate_master']; 
      }
    }
    return($data);
  }
}
?>
