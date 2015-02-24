<?php
class __Mustache_103ac563c1b3e0f5bc1a9f4d993aa3fc extends Mustache_Template
{
    private $lambdaHelper;
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '  </div><!-- .inner-content -->
';
        $buffer .= $indent . '  ';
        // 'edit_post_link' section
        $buffer .= $this->sectionC42b3cb45abddae273b801cede2f5046($context, $indent, $context->find('edit_post_link'));
        $buffer .= '
';
        $buffer .= $indent . '</div><!-- .post-content -->
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<!-- + -->';
        return $buffer;
    }

    private function sectionC42b3cb45abddae273b801cede2f5046(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '{{{edit_post_link}}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $value = $this->resolveValue($context->find('edit_post_link'), $context, $indent);
                $buffer .= $value;
                $context->pop();
            }
        }
        return $buffer;
    }
}
