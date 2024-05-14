<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

function xls_import_help($path, $arg) {
  switch ($path) {
    case "admin/help#xls_import":
      return t("XLS Import help page.");
  }
}

function xls_import_menu() {
  $items = array();

  $items['admin/xls-import'] = array(
    'title' => 'Import XLS File',
    'page callback' => 'drupal_get_form',
    'page arguments' => array('xls_import_form'),
    'access callback' => 'user_access',
    'access arguments' => array('administer site configuration'),
    'type' => MENU_NORMAL_ITEM,
  );

  $items['admin/xls-import/handle'] = array(
    'page callback' => 'xls_import_handle_import',
    'access callback' => 'user_access',
    'access arguments' => array('administer site configuration'),
    'type' => MENU_CALLBACK,
  );

  return $items;
}

function xls_import_form($form, &$form_state) {
  $form['xls_file'] = array(
    '#type' => 'file',
    '#title' => t('Upload XLS file'),
    '#description' => t('Please upload the XLS file.'),
  );

  $form['actions']['#type'] = 'actions';
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Import'),
  );

  return $form;
}

function xls_import_form_submit($form, &$form_state) {
  $validators = array('file_validate_extensions' => array('xls xlsx'));
  if ($file = file_save_upload('xls_file', $validators, FALSE, 0, FILE_EXISTS_RENAME)) {
    $file_path = drupal_realpath($file->uri);
    xls_import_import_from_file($file_path);
    drupal_set_message(t('File imported successfully.'));
    drupal_goto('admin/xls-import');
  } else {
    form_set_error('xls_file', t('Failed to upload file.'));
  }
}

function xls_import_import_from_file($file_path) {
  require_once 'vendor/autoload.php'; 

  $spreadsheet = IOFactory::load($file_path);

  $sheet = $spreadsheet->getSheet(2);
  $data = $sheet->toArray();

  foreach ($data as $row) {
    // обработка каждой строки
  }
}
?>