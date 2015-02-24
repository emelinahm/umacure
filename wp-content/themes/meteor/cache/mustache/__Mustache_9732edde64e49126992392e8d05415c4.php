<?php
class __Mustache_9732edde64e49126992392e8d05415c4 extends Mustache_Template
{
    private $lambdaHelper;
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'single_post' section
        $buffer .= $this->section47b7eea2f12cfe3bbdd849cad9868b87($context, $indent, $context->find('single_post'));
        $buffer .= '
';
        // 'single_post' inverted section
        $value = $context->find('single_post');
        if (empty($value)) {
            
            // 'edit_post_link' section
            $buffer .= $this->sectionC42b3cb45abddae273b801cede2f5046($context, $indent, $context->find('edit_post_link'));
            $buffer .= '
';
            // 'metadata_all' section
            $buffer .= $this->section8f2f18fef2b0cfd93776f62e8216a97f($context, $indent, $context->find('metadata_all'));
        }
        return $buffer;
    }

    private function section47b7eea2f12cfe3bbdd849cad9868b87(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '{{{rand}}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $value = $this->resolveValue($context->find('rand'), $context, $indent);
                $buffer .= $indent . $value;
                $context->pop();
            }
        }
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
                $buffer .= $indent . $value;
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section8f2f18fef2b0cfd93776f62e8216a97f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '{{{metadata_all}}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $value = $this->resolveValue($context->find('metadata_all'), $context, $indent);
                $buffer .= $indent . $value;
                $context->pop();
            }
        }
        return $buffer;
    }
}
