<?php
class __Mustache_16b1abd4e494c373548b0d20f0b7102c extends Mustache_Template
{
    private $lambdaHelper;
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<div class="php-code post-content ';
        $value = $this->resolveValue($context->find('container_class'), $context, $indent);
        $buffer .= $value;
        // 'visibility' section
        $buffer .= $this->section76fcbda0378b75df8ebb986fc77a0385($context, $indent, $context->find('visibility'));
        $buffer .= '"';
        // 'inline_css' section
        $buffer .= $this->sectionA35e2193df2b0249647838fa2f4ade98($context, $indent, $context->find('inline_css'));
        $buffer .= '>
';
        // 'title_heading' section
        $buffer .= $this->section0980f78ad684ef9d2a1e6823bc3fbc1e($context, $indent, $context->find('title_heading'));
        return $buffer;
    }

    private function section76fcbda0378b75df8ebb986fc77a0385(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = ' {{{visibility}}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= ' ';
                $value = $this->resolveValue($context->find('visibility'), $context, $indent);
                $buffer .= $value;
                $context->pop();
            }
        }
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
                $buffer .= ' style="';
                $value = $this->resolveValue($context->find('inline_css'), $context, $indent);
                $buffer .= $value;
                $buffer .= '"';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section0980f78ad684ef9d2a1e6823bc3fbc1e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '<h2 class="title-heading">{{title_heading}}</h2>';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '<h2 class="title-heading">';
                $value = $this->resolveValue($context->find('title_heading'), $context, $indent);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</h2>';
                $context->pop();
            }
        }
        return $buffer;
    }
}
