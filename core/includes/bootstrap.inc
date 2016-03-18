<?php


/**
 * Invokes a bootstrap hook in all bootstrap modules that implement it.
 *
 * @param $hook
 *   The name of the bootstrap hook to invoke.
 *
 * @see bootstrap_hooks()
 */
function bootstrap_invoke_all($hook) {
  // Bootstrap modules should have been loaded when this function is called, so
  // we don't need to tell module_list() to reset its internal list (and we
  // therefore leave the first parameter at its default value of FALSE). We
  // still pass in TRUE for the second parameter, though; in case this is the
  // first time during the bootstrap that module_list() is called, we want to
  // make sure that its internal cache is primed with the bootstrap modules
  // only.
  foreach (module_list(FALSE, TRUE) as $module) {
    drupal_load('module', $module);
    module_invoke($module, $hook);
  }
}



/**
 * Includes a file with the provided type and name.
 *
 * This prevents including a theme, engine, module, etc., more than once.
 *
 * @param $type
 *   The type of item to load (i.e. theme, theme_engine, module).
 * @param $name
 *   The name of the item to load.
 *
 * @return
 *   TRUE if the item is loaded or has already been loaded.
 */
function drupal_load($type, $name) {
  // Once a file is included this can't be reversed during a request so do not
  // use drupal_static() here.
  static $files = array();

  if (isset($files[$type][$name])) {
    return TRUE;
  }

  $filename = drupal_get_filename($type, $name);

  if ($filename) {
    include_once DRUPAL_ROOT . '/' . $filename;
    $files[$type][$name] = TRUE;

    return TRUE;
  }

  return FALSE;
}




/**
 * Translates a string to the current language or to a given language.
 *
 * The t() function serves two purposes. First, at run-time it translates
 * user-visible text into the appropriate language. Second, various mechanisms
 * that figure out what text needs to be translated work off t() -- the text
 * inside t() calls is added to the database of strings to be translated.
 * These strings are expected to be in English, so the first argument should
 * always be in English. To enable a fully-translatable site, it is important
 * that all human-readable text that will be displayed on the site or sent to
 * a user is passed through the t() function, or a related function. See the
 * @link http://drupal.org/node/322729 Localization API @endlink pages for
 * more information, including recommendations on how to break up or not
 * break up strings for translation.
 *
 * @section sec_translating_vars Translating Variables
 * You should never use t() to translate variables, such as calling
 * @code t($text); @endcode, unless the text that the variable holds has been
 * passed through t() elsewhere (e.g., $text is one of several translated
 * literal strings in an array). It is especially important never to call
 * @code t($user_text); @endcode, where $user_text is some text that a user
 * entered - doing that can lead to cross-site scripting and other security
 * problems. However, you can use variable substitution in your string, to put
 * variable text such as user names or link URLs into translated text. Variable
 * substitution looks like this:
 * @code
 * $text = t("@name's blog", array('@name' => format_username($account)));
 * @endcode
 * Basically, you can put variables like @name into your string, and t() will
 * substitute their sanitized values at translation time. (See the
 * Localization API pages referenced above and the documentation of
 * format_string() for details about how to define variables in your string.)
 * Translators can then rearrange the string as necessary for the language
 * (e.g., in Spanish, it might be "blog de @name").
 *
 * @section sec_alt_funcs_install Use During Installation Phase
 * During the Drupal installation phase, some resources used by t() wil not be
 * available to code that needs localization. See st() and get_t() for
 * alternatives.
 *
 * @param $string
 *   A string containing the English string to translate.
 * @param $args
 *   An associative array of replacements to make after translation. Based
 *   on the first character of the key, the value is escaped and/or themed.
 *   See format_string() for details.
 * @param $options
 *   An associative array of additional options, with the following elements:
 *   - 'langcode' (defaults to the current language): The language code to
 *     translate to a language other than what is used to display the page.
 *   - 'context' (defaults to the empty context): The context the source string
 *     belongs to.
 *
 * @return
 *   The translated string.
 *
 * @see st()
 * @see get_t()
 * @see format_string()
 * @ingroup sanitization
 */
function t($string, array $args = array(), array $options = array()) {
  global $language;
  static $custom_strings;

  // Merge in default.
  if (empty($options['langcode'])) {
    $options['langcode'] = isset($language->language) ? $language->language : 'en';
  }
  if (empty($options['context'])) {
    $options['context'] = '';
  }

  // First, check for an array of customized strings. If present, use the array
  // *instead of* database lookups. This is a high performance way to provide a
  // handful of string replacements. See settings.php for examples.
  // Cache the $custom_strings variable to improve performance.
  if (!isset($custom_strings[$options['langcode']])) {
    $custom_strings[$options['langcode']] = variable_get('locale_custom_strings_' . $options['langcode'], array());
  }
  // Custom strings work for English too, even if locale module is disabled.
  if (isset($custom_strings[$options['langcode']][$options['context']][$string])) {
    $string = $custom_strings[$options['langcode']][$options['context']][$string];
  }
  // Translate with locale module if enabled.
  elseif ($options['langcode'] != 'en' && function_exists('locale')) {
    $string = locale($string, $options['context'], $options['langcode']);
  }
  if (empty($args)) {
    return $string;
  }
  else {
    return format_string($string, $args);
  }
}



/**
 * Ensures Drupal is bootstrapped to the specified phase.
 *
 * In order to bootstrap Drupal from another PHP script, you can use this code:
 * @code
 *   define('DRUPAL_ROOT', '/path/to/drupal');
 *   require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
 *   drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
 * @endcode
 *
 * @param int $phase
 *   A constant telling which phase to bootstrap to. When you bootstrap to a
 *   particular phase, all earlier phases are run automatically. Possible
 *   values:
 *   - DRUPAL_BOOTSTRAP_CONFIGURATION: Initializes configuration.
 *   - DRUPAL_BOOTSTRAP_PAGE_CACHE: Tries to serve a cached page.
 *   - DRUPAL_BOOTSTRAP_DATABASE: Initializes the database layer.
 *   - DRUPAL_BOOTSTRAP_VARIABLES: Initializes the variable system.
 *   - DRUPAL_BOOTSTRAP_SESSION: Initializes session handling.
 *   - DRUPAL_BOOTSTRAP_PAGE_HEADER: Sets up the page header.
 *   - DRUPAL_BOOTSTRAP_LANGUAGE: Finds out the language of the page.
 *   - DRUPAL_BOOTSTRAP_FULL: Fully loads Drupal. Validates and fixes input
 *     data.
 * @param boolean $new_phase
 *   A boolean, set to FALSE if calling drupal_bootstrap from inside a
 *   function called from drupal_bootstrap (recursion).
 *
 * @return int
 *   The most recently completed phase.
 */
function drupal_bootstrap($phase = NULL, $new_phase = TRUE) {
  // Not drupal_static(), because does not depend on any run-time information.
  static $phases = array(
    DRUPAL_BOOTSTRAP_CONFIGURATION,
    DRUPAL_BOOTSTRAP_PAGE_CACHE,
    DRUPAL_BOOTSTRAP_DATABASE,
    DRUPAL_BOOTSTRAP_VARIABLES,
    DRUPAL_BOOTSTRAP_SESSION,
    DRUPAL_BOOTSTRAP_PAGE_HEADER,
    DRUPAL_BOOTSTRAP_LANGUAGE,
    DRUPAL_BOOTSTRAP_FULL,
  );
  // Not drupal_static(), because the only legitimate API to control this is to
  // call drupal_bootstrap() with a new phase parameter.
  static $final_phase;
  // Not drupal_static(), because it's impossible to roll back to an earlier
  // bootstrap state.
  static $stored_phase = -1;

  if (isset($phase)) {
    // When not recursing, store the phase name so it's not forgotten while
    // recursing but take care of not going backwards.
    if ($new_phase && $phase >= $stored_phase) {
      $final_phase = $phase;
    }

    // Call a phase if it has not been called before and is below the requested
    // phase.
    while ($phases && $phase > $stored_phase && $final_phase > $stored_phase) {
      $current_phase = array_shift($phases);

      // This function is re-entrant. Only update the completed phase when the
      // current call actually resulted in a progress in the bootstrap process.
      if ($current_phase > $stored_phase) {
        $stored_phase = $current_phase;
      }

      switch ($current_phase) {
        case DRUPAL_BOOTSTRAP_CONFIGURATION:
          _drupal_bootstrap_configuration();
          break;

        case DRUPAL_BOOTSTRAP_PAGE_CACHE:
          _drupal_bootstrap_page_cache();
          break;

        case DRUPAL_BOOTSTRAP_DATABASE:
          _drupal_bootstrap_database();
          break;

        case DRUPAL_BOOTSTRAP_VARIABLES:
          _drupal_bootstrap_variables();
          break;

        case DRUPAL_BOOTSTRAP_SESSION:
          require_once DRUPAL_ROOT . '/' . variable_get('session_inc', 'includes/session.inc');
          drupal_session_initialize();
          break;

        case DRUPAL_BOOTSTRAP_PAGE_HEADER:
          _drupal_bootstrap_page_header();
          break;

        case DRUPAL_BOOTSTRAP_LANGUAGE:
          drupal_language_initialize();
          break;

        case DRUPAL_BOOTSTRAP_FULL:
          require_once DRUPAL_ROOT . '/includes/common.inc';
          _drupal_bootstrap_full();
          break;
      }
    }
  }
  return $stored_phase;
}