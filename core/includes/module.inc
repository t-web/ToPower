<?php

function module_list($refresh = FALSE, $bootstrap_refresh = FALSE, $sort = FALSE, $fixed_list = NULL) {
  static $list = array(), $sorted_list;

  if (empty($list) || $refresh || $fixed_list) {
    $list = array();
    $sorted_list = NULL;
    if ($fixed_list) {
      foreach ($fixed_list as $name => $module) {
        drupal_get_filename('module', $name, $module['filename']);
        $list[$name] = $name;
      }
    }
    else {
      if ($refresh) {
        // For the $refresh case, make sure that system_list() returns fresh
        // data.
        drupal_static_reset('system_list');
      }
      if ($bootstrap_refresh) {
        $list = system_list('bootstrap');
      }
      else {
        // Not using drupal_map_assoc() here as that requires common.inc.
        $list = array_keys(system_list('module_enabled'));
        $list = (!empty($list) ? array_combine($list, $list) : array());
      }
    }
  }
  if ($sort) {
    if (!isset($sorted_list)) {
      $sorted_list = $list;
      ksort($sorted_list);
    }
    return $sorted_list;
  }
  return $list;
}