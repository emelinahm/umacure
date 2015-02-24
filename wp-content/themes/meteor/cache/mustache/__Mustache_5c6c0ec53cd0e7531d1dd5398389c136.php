<?php
class __Mustache_5c6c0ec53cd0e7531d1dd5398389c136 extends Mustache_Template
{
    private $lambdaHelper;
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'has_tags' section
        $buffer .= $this->section6485e72f089855eb36d21a7d13558790($context, $indent, $context->find('has_tags'));
        return $buffer;
    }

    private function section41da9412360d35b27c72765127c8dd01(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <a class="meteor-capsule" href="{{{url}}}">{{name}}</a>
      ';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '        <a class="meteor-capsule" href="';
                $value = $this->resolveValue($context->find('url'), $context, $indent);
                $buffer .= $value;
                $buffer .= '">';
                $value = $this->resolveValue($context->find('name'), $context, $indent);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</a>
';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section6485e72f089855eb36d21a7d13558790(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
      <!-- + -->
        
      <div class="single-tags-list clearfix">
        <strong>{{{tags_label}}}:</strong>
      {{#tags}}
        <a class="meteor-capsule" href="{{{url}}}">{{name}}</a>
      {{/tags}}
      </div><!-- .tags-list -->';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '      <!-- + -->
';
                $buffer .= $indent . '        
';
                $buffer .= $indent . '      <div class="single-tags-list clearfix">
';
                $buffer .= $indent . '        <strong>';
                $value = $this->resolveValue($context->find('tags_label'), $context, $indent);
                $buffer .= $value;
                $buffer .= ':</strong>
';
                // 'tags' section
                $buffer .= $this->section41da9412360d35b27c72765127c8dd01($context, $indent, $context->find('tags'));
                $buffer .= $indent . '      </div><!-- .tags-list -->';
                $context->pop();
            }
        }
        return $buffer;
    }
}
