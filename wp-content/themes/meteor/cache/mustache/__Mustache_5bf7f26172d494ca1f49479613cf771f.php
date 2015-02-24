<?php
class __Mustache_5bf7f26172d494ca1f49479613cf771f extends Mustache_Template
{
    private $lambdaHelper;
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'inline_css' section
        $buffer .= $this->sectionA35e2193df2b0249647838fa2f4ade98($context, $indent, $context->find('inline_css'));
        return $buffer;
    }

    private function sectionA35e2193df2b0249647838fa2f4ade98(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = ' style="{{{inline_css}}}"';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . ' style="';
                $value = $this->resolveValue($context->find('inline_css'), $context, $indent);
                $buffer .= $value;
                $buffer .= '"';
                $context->pop();
            }
        }
        return $buffer;
    }
}
