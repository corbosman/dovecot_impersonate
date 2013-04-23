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
  
}
?>
