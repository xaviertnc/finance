<?php namespace OneFile;

use Exception;


/**
 *
 * DB Query Class
 *
 * @author: C. Moller
 * @date: 24 Jan 2017
 *
 *
 * Usage Example(s):
 * -----------------
 * DB::query->from('view_uitstallings')
 * ->where('pakket_id', '=', $pakket_id)
 * ->where('id', '>', $id, ['ignore'=>[null]])
 * ->where('refno', 'IS NULL')
 * ->where('tag_id', 'IN', (array) $tagIDs)
 *
 * ->where('CONCAT(firstname, ' ', lastname)', 'LIKE%', $search)
 *
 * ->where(DbQuery::subQuery()
 *    ->where('date', 'BETWEEN', [$fromDate, $toDate], ['ignore'=>[0, null]])
 *    ->orWhere())
 *
 * ->orWhere('name', '%LIKE%', $name, ['ignore'=>[null]])
 *
 * ->orderBy([$colA => $colAdir, $colB => $colBdir])
 *
 * ->getResults();
 *
 * OR
 *
 * ->limit($offset, $itemspp)
 * ->paginate($pageno, $itemspp)
 * ->getBy('id', 'name,description');
 * ->get('id,desc');
 *
 */
 
class DbQuery
{

  public $executeQueryFn;


  public function __construct($executeQueryFn)
  {
    $this->executeQueryFn = $executeQueryFn;
  }


  private function getExecuteCallback()
  {
    return function ($resultsFormat = null, $tableName = null, $sql = null, $params = null, $args = null)
    {
      $sql = $tableName . ($sql ? " WHERE $sql" : '');
      
      $results = call_user_func($this->executeQueryFn, $sql, $params);
      
      if ($resultsFormat == 'getBy')
      {
        $lookup = array();
        $id_column = isset($args[0]) ? $args[0] : 'id';
        $display_column = isset($args[1]) ? $args[1] : null;
        $idDecorator = isset($args[2]) ? $args[2] : null; // e.g. 'item_%s'
        $isMultiColumnDisp = is_array($display_column);
        foreach ($results?:array() as $result)
        {
          if ($isMultiColumnDisp)
          {
            $displayValue = array();
            // $display_column == array: index only the record fields specified (object)
            foreach ($display_column as $column_name) $displayValue[$column_name] = $result->{$column_name};
          }
          elseif($display_column)
          {
            // $display_column == string: index a single value (scalar)
            $displayValue = $result->{$display_column};
          }
          else
          {
            // $display_column == null: index the entire record (object)
            $displayValue = $result;
          }
          $lookup_id = $idDecorator ? sprintf($idDecorator, $result->{$id_column}) : $result->{$id_column};
          $lookup[$lookup_id] = $displayValue;
        }

        unset($results);
        return $lookup;
      }

      return $results;

    };
  }


  public function from($tableName)
  {
    // Log::DbQuery('DbQuery::from(' . $tableName . '), Start');
    return new QueryStatement($tableName, $this->getExecuteCallback());
  }


  public function subQuery()
  {
    return new QueryStatement();
  }

}



/**
 *
 * Query Expression Class
 *
 * @author: C. Moller
 * @date: 08 Jan 2017
 *
 * @update: C. Moller
 *   - Moved to OneFile 24 Jan 2017
 *
 */
 
class QueryExpression
{
  
  protected $leftArg;
  protected $operator;
  protected $rightArg;
  protected $options;
  protected $glue;


  /**
   * @param string $glue Possible glue values: AND, OR
   */
  public function __construct($leftArg, $operator = null, $rightArg = null, $options = null, $glue = null)
  {
    $this->leftArg = $leftArg;
    $this->operator = $operator;
    $this->rightArg = $rightArg;
    $this->options = $options ?: array();
    $this->glue = $glue;
  }


  public function build(&$params)
  {
    $glue = $this->glue ? (' ' . $this->glue . ' ') : '';
    if (is_object($this->leftArg) and ($this->leftArg instanceof Statement))
    {
      return $glue . '(' . $this->leftArg->build($params) . ')';
    }

    switch (strtoupper($this->operator))
    {
      default:

        if (isset($this->rightArg))
        {
          $params[] = $this->rightArg;
          return $glue . $this->leftArg . $this->operator . '?';
        }

        return $glue . $this->leftArg . ' ' . $this->operator;
    }
  }
  
}



/**
 *
 * Query Statement Class
 *
 */
 
class QueryStatement
{

  protected $expressions = array();

  protected $tableName;
  protected $executeCallback;
  protected $orderBy;
  protected $limit;


  public function __construct($tableName = null, $executeCallback = null)
  {
    $this->tableName = $tableName;
    $this->executeCallback = $executeCallback;
  }


  public function addExpression($leftArg, $expression_operator = null, $rightArg = null, $options = null, $glue = null)
  {
    if ( ! $options)
    {
      $options = array();
    }
    
    if (isset($options['ignore']))
    {
      $values_to_ignore = $options['ignore'];
      if (in_array($rightArg, $values_to_ignore)) return $this;
      //unset($options['ignore']);
    }

    if ($glue and ! $this->expressions) $glue = null;

    $this->expressions[] = new QueryExpression(
      $leftArg,
      $expression_operator,
      $rightArg,
      $options,
      $glue
    );

    return $this;
  }


  public function where($leftArg, $expression_operator = null, $rightArg = null, $options = null)
  {
    return $this->addExpression($leftArg, $expression_operator, $rightArg, $options, 'AND');
  }


  public function orWhere($leftArg, $expression_operator = null, $rightArg = null, $options = null)
  {
    return $this->addExpression($leftArg, $expression_operator, $rightArg, $options, 'OR');
  }


  /**
   * Build an order statement based on the value(s) in $orderBy.
   *
   * If $orderBy is an array or multi-array, assume that it contains multiple order statements.
   * The addition of order statements should be handled outside the scope of this class.
   *
   * @param mixed $orderBy String / Array / Multi-Array. E.g. $orderBy = "amount desc";
   *   ['amount desc', 'time asc']; [['amount'=>'desc'],['time'=>'asc']]
   *
   * @return Statement
   */
  public function orderBy($orderBy)
  {
    if (is_array($orderBy))
    {
      if (is_array($orderBy[0]))
      {
        $orderStatements = array();
        foreach ($orderBy as $orderSpec) { $orderStatements[] = implode(' ', $orderSpec); }
        $orderBy = implode(',', $orderStatements);
      }
      else
      {
        $orderBy = implode(',', $orderBy);
      }
    }

    $this->orderBy = $orderBy ? ' ORDER BY ' . $orderBy : null;

    return $this;
  }


  public function build(&$params)
  {
    $sql = '';
    foreach ($this->expressions?:array() as $expression) $sql .= $expression->build($params);
    if ($this->orderBy) $sql .= $this->orderBy;
    if ($this->limit) $sql .= $this->limit;
    return $sql;
  }


  /**
   * Execute the full SQL query string via a callback function.
   *
   * Calls $this->executeCallback (injected on instantiation) to allow the user to execute
   * the query created, using their own DB framework. The callback also allows the user to process
   * the results before delivery according to the name under which the callback was invoked!
   *
   * E.g.
   * ->get()
   * ->getBy()
   *
   * @param string $method Whatever the user wants to name this call for results.
   *   e.g. getIndexed, fetchResults, etc.
   *
   * @param array $arguments
   *
   */
  public function __call($method, $arguments)
  {
    // Log::DbQuery("QueryStatement::call($method), args: " . json_encode($arguments?:'none'));
    if ( ! $this->tableName)
    {
      throw new Exception("Results call: $method not allowed on a query substatement!");
    }
    $params = array();
    $sql = $this->build($params);
    $args = array($method, $this->tableName, $sql, $params, $arguments);
    return call_user_func_array($this->executeCallback, $args);
  }

}
