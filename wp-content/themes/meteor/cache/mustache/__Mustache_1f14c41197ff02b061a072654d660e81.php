<?php
class __Mustache_1f14c41197ff02b061a072654d660e81 extends Mustache_Template
{
    private $lambdaHelper;
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<div class="attachment-info text-center">
';
        $buffer .= $indent . '<a class="lite-rounded" href="';
        $value = $this->resolveValue($context->find('url'), $context, $indent);
        $buffer .= $value;
        $buffer .= '"><img alt="';
        $value = $this->resolveValue($context->find('title'), $context, $indent);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" width="';
        $value = $this->resolveValue($context->find('width'), $context, $indent);
        $buffer .= $value;
        $buffer .= '"';
        // 'height' section
        $buffer .= $this->section61df3d33e4035361d1d3a611c56471f2($context, $indent, $context->find('height'));
        $buffer .= ' src="';
        $value = $this->resolveValue($context->find('image'), $context, $indent);
        $buffer .= $value;
        $buffer .= '" /></a>
';
        // 'description' section
        $buffer .= $this->section43c0393456aface70a254e85a462b4df($context, $indent, $context->find('description'));
        $buffer .= '
';
        $buffer .= $indent . '</div><!-- .attachment-info -->';
        return $buffer;
    }

    private function section61df3d33e4035361d1d3a611c56471f2(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = ' height="{{{height}}}"';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= ' height="';
                $value = $this->resolveValue($context->find('height'), $context, $indent);
                $buffer .= $value;
                $buffer .= '"';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section43c0393456aface70a254e85a462b4df(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '<div class="desc text-center">{{{description}}}</div>';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '<div class="desc text-center">';
                $value = $this->resolveValue($context->find('description'), $context, $indent);
                $buffer .= $value;
                $buffer .= '</div>';
                $context->pop();
            }
        }
        return $buffer;
    }
}
