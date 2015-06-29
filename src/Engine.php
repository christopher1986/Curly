<?php 

namespace Curly;

use Curly\Collection\ArrayList;
use Curly\Lang\Operator\Binary\AdditionOperator;
use Curly\Lang\Operator\Binary\GreaterOperator;
use Curly\Lang\Operator\Binary\SubtractionOperator;
use Curly\Lang\Statement\AssignmentStatement;
use Curly\Lang\Statement\DeclarationStatement;
use Curly\Lang\Statement\ForStatement;
use Curly\Lang\Statement\PrintStatement;
use Curly\Lang\Statement\IfStatement;

/**
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
final class Engine implements EngineInterface
{
    /**
     * A collection of statements.
     *
     * @var array
     */
    private $statements;

    /**
     * A collection of operators.
     *
     * @var array
     */
    private $operators;

    /**
     * Construct a new Engine.
     */
    public function __construct()
    {
        $this->statements = new ArrayList();
        $this->operators  = new ArrayList();
    }

    /**
     * {@inheritDoc}
     */   
    public function getOperators()
    {
        if (!is_array($this->operators)) {
            $this->operators = array(
                '+' => new AdditionOperator(),
                '-' => new SubtractionOperator(),
                '>' => new GreaterOperator(),
            );
        }
        
        return $this->operators;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getStatements()
    {
        if (!is_array($this->statements)) {
            $this->statements = array(
                '='     => new AssignmentStatement(),
                'var'   => new DeclarationStatement(),
                'if'    => new IfStatement(),
                'for'   => new ForStatement(),
                'print' => new PrintStatement(),
            );
        }
        
        return $this->statements;
    }
}
