<?php
class __Mustache_65b8f817af3b74fa46aad8f476a3decb extends Mustache_Template
{
    private $lambdaHelper;
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'content' section
        $buffer .= $this->sectionF8e7dc2bba9b4bda40b9a57d2cacec47($context, $indent, $context->find('content'));
        return $buffer;
    }

    private function sectionF8e7dc2bba9b4bda40b9a57d2cacec47(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
<div class="attachment-content">
  {{{content}}}
</div><!-- .attachment-content -->
';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '<div class="attachment-content">
';
                $buffer .= $indent . '  ';
                $value = $this->resolveValue($context->find('content'), $context, $indent);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '</div><!-- .attachment-content -->
';
                $context->pop();
            }
        }
        return $buffer;
    }
}
