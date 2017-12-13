<?php
class Validator {
  //filter functions
  protected $filter_functions = [];

  //sanitize functions
  protected $sanitize_functions = [];

  //error log
  protected $error_log = [];


  /**
   * getter/setter for filter functions
   * @param  array  $functions functions you may want to add to the filter_functions property;
   * @return mixed true if new items are added, array with filter functions if $functions is empty
   */
  public function sanitizeFunctions(array $functions = []) {
    if(empty($functions)){
      return $this->sanitize_functions;
    }

    $this->sanitize_functions = $this->createValidArray($functions, 'sanitize_');
  }


  /**
   * setter/getter for $this->filter_functions;
   *
   */

  public function filterFunctions(array $filters = []) {
    if(empty($filters)) {
      return $this->filter_functions;
    }

    $this->filter_functions = $this->createValidArray($filters, 'filter_');
  }


  /* HELPER FUNCTIONS */
  private function createValidArray(array $array, string $preset) {
    $newArray = [];

    foreach($array as $name => $filters) {
      $filter = explode('|', $filters);
      $newArray[$name] = [];

      foreach($filter as $methodName) {
        if(array_key_exists($preset . $methodName, $newArray)) {
          continue;
        }

        $strstrMethod = strstr($methodName, ',', true);
        $strstrValue = ltrim(strstr($methodName, ','), ',');

        if(method_exists($this, $preset . $strstrMethod)) {
          if($strstrMethod !== false) {
            $newArray[$name][$preset . $strstrMethod] = $strstrValue;
            continue;
          }
        }

        if(method_exists($this, $preset . $methodName)) {
          $newArray[$name][$preset . $methodName] = '';
          continue;
        }
      }
    }
    return $newArray;
  }


  /* END HELPER FUNCTIONS */

  /* SANITIZE FUNCTIONS */
  protected function sanitize_sanitize_string($value, $params = null) {
    return filter_var($value, FILTER_SANITIZE_STRING);
  }


  protected function sanitize_numeric($value, $param = null) {
    return preg_replace('/\D/', '', $value);
  }


  protected function sanitize_sanitize_url($value, $param = null) {
    return filter_var($value, FILTER_SANITIZE_URL);
  }


  protected function sanitize_trim($value, $params = null) {
    return trim($value);
  }


  protected function sanitize_remove($value, $params = null) {
    if($params === '') {return $value;}
    return preg_replace('/(^|[' . $params . '])/', '', $value);
  }
  /* END SANITIZE FUNCTIONS */


  /* FILTER FUNCTIONS */
  protected function filter_maxlen($value, $params = null, $postName = '') {
    if(!intval($params)){throw new Exception('This is not a number in function maxlen.');}
    $output = (strlen($value) <= $params) ? true : false;

    if($output === false) {
      $this->error_log[$postName]['filter_maxlen'] = 'length can not be greater than ' . $params;
    }

    return $output;
  }


  protected function filter_minlen($value, $params = null, $postName = '') {
    if(!intval($params)){throw new Exception('This is not a number in function minlen.');}
    $output = (strlen($value) >= $params) ? true : false;
    if($output === false) {
      $this->error_log[$postName]['filter_minlen'] = 'length is smaller then ' . $params;
    }

    return $output;
  }


  protected function filter_email($value, $params = null, $postName = '') {
    $output = filter_var($value, FILTER_VALIDATE_EMAIL);
    if($output === false) {
      $this->error_log[$postName]['filter_email'] = ' is not an email.';
    }

    return $output;
  }


  protected function filter_alphanumeric($value, $params = null, $postName = '') {
    $output = !preg_match('/[^a-z0-9]/i', $value);
    if($output === false) {
      $this->error_log[$postName]['filter_alphanumeric'] = ' is not alphanumeric.';
    }

    return $output;
  }


  protected function filter_not_empty($value, $params = null, $postName = '') {
    $output = ($value == '') ? false : true;
    if($output === false) {
      $this->error_log[$postName]['filter_empty'] = 'may not be empty.';
    }

    return $output;
  }

  /* END FILTER FUNCTIONS */

  /* MAIN CALLABLE FUNCTIONS */

  /**
   * Loops through the sanitize functions and returns the given values.
   * @param  array  $input      Most likely the $_POST array you want to sanitize.
   * @param  array  $filterset  key = key of the $_POST array, value = functions you want to execute.
   * @return array  $input      array with the sanitized values.
   */

  public function sanitize(array $input, array $filterset) {
    $this->sanitizeFunctions($filterset);
    foreach($input as $postName => $postValue) {
      if(!array_key_exists($postName, $this->sanitize_functions)) {
        throw new Exception('key has not been made: "' . $postName . '"');
      }

      foreach($this->sanitize_functions[$postName] as $method => $param) {
        $input[$postName] = $this->$method($input[$postName], $param);
      }
    }

    return $input;
  }


  /**
   * Loops through all the filter functions and checks if the values match the filters.
   * @param  array  $input    Most likely the $_POST array to filter the values out
   * @param  array  $filters
   * @return boolean|array       True if all the values passed the test, array of errors if the tests failed.
   */
  public function filter(array $input, array $filters) {
    $this->filterFunctions($filters);

    foreach($input as $postName => $postValue) {
      if(!array_key_exists($postName, $this->filter_functions)) {
        throw new Exception('key has not been made: "' . $postName . '"');
      }

      $input[$postName] = [];
      foreach($this->filter_functions[$postName] as $method => $param) {
        $input[$postName][$method] = $this->$method($postValue, $param, $postName);
      }
    }

    if(!empty($this->error_log)) {
      return $this->error_log;
    }

    return true;
  }

  /* END MAIN CALLABLE FUNCTIONS */
}
