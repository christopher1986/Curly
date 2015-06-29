<?php 

namespace Curly;

use Curly\Collection\ArrayList;
use Curly\Lang\Operator\Binary\AdditionOperator;
use Curly\Lang\Statement\ForStatement;
use Curly\Lang\Statement\DeclarationStatement;

/**
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface EngineInterface
{
    /**
     * Returns a collection of operators.
     *
     * @return ListInterface collection of operators.
     */   
    public function getOperators();
    
    /**
     * Returns a collection of statements.
     *
     * @return ListInterface collection of statements.
     */
    public function getStatements();
}
