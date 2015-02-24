<?php
class __Mustache_5649fb9432f9e4ba82ad6740e15f5f21 extends Mustache_Template
{
    private $lambdaHelper;
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'open' section
        $buffer .= $this->sectionF2d26c0b7933a89e259c6be339a5227c($context, $indent, $context->find('open'));
        // 'close' section
        $buffer .= $this->section1829cc46e1e60df74b5c64734f47ee6e($context, $indent, $context->find('close'));
        return $buffer;
    }

    private function section28f25d48628fb8249d4f259d11500db9(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = ' {{visibility}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= ' ';
                $value = $this->resolveValue($context->find('visibility'), $context, $indent);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
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

    private function sectionF2d26c0b7933a89e259c6be339a5227c(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
<div class="sidebar {{{container_class}}}{{#visibility}} {{visibility}}{{/visibility}}"{{#inline_css}} style="{{{inline_css}}}"{{/inline_css}}>
';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '<div class="sidebar ';
                $value = $this->resolveValue($context->find('container_class'), $context, $indent);
                $buffer .= $value;
                // 'visibility' section
                $buffer .= $this->section28f25d48628fb8249d4f259d11500db9($context, $indent, $context->find('visibility'));
                $buffer .= '"';
                // 'inline_css' section
                $buffer .= $this->sectionA35e2193df2b0249647838fa2f4ade98($context, $indent, $context->find('inline_css'));
                $buffer .= '>
';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section1829cc46e1e60df74b5c64734f47ee6e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '

</div><!-- .sidebar -->
';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '
';
                $buffer .= $indent . '</div><!-- .sidebar -->
';
                $context->pop();
            }
        }
        return $buffer;
    }
}
