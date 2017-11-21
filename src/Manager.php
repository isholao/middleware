<?php

namespace Isholao\Middleware;

/**
 * @author Ishola O <ishola.tolu@outlook.com>
 */
class Manager
{

    protected $stack = [];

    /**
     * Middleware stack lock
     *
     * @var bool
     */
    protected $lock = FALSE;
    protected $requiredInstance;

    public function __construct(callable $middleware,
                                ?string $requiredInstance = NULL)
    {
        $this->stack[] = new \Isholao\CallableResolver\DeferredCallable($middleware);

        if ($requiredInstance)
        {
            $this->requiredInstance = $requiredInstance;
        }
    }

    /**
     * Register
     * 
     * @param callable $callable
     * @return $this
     * @throws \RuntimeException
     * @throws \Error
     */
    public function register($callable)
    {
        if ($this->lock)
        {
            throw new \RuntimeException('Middleware can’t be added once the stack is dequeuing');
        }

        if (empty($this->stack))
        {
            throw new \RuntimeException('The stack is empty');
        }

        $next = \array_pop($this->stack);
        $self = $this;
        $this->stack[] = new \CallableResolver\DeferredCallable(\Closure::bind(function (...$args) use ($callable, &$next, &$self)
                {
                    if ($next)
                    {
                        \array_push($args, $next);
                    }

                    $result = \call_user_func_array($callable, $args);

                    if (!\is_null($self->requiredInstance))
                    {
                        if (!$result instanceof $self->requiredInstance)
                        {
                            throw new \Error($self->requiredInstance . ' is the required return instance.');
                        }

                        return $result;
                    } else
                    {
                        return $result;
                    }
                }, NULL));

        unset($self);
        return $this;
    }

    /**
     * call stack
     * 
     * @param mixed $args
     * @return mixed
     */
    public function call(...$args)
    {
        $start = \array_pop($this->stack);
        $this->lock = TRUE;
        $result = \call_user_func_array($start, $args);
        $this->lock = FALSE;
        return $result;
    }

}
