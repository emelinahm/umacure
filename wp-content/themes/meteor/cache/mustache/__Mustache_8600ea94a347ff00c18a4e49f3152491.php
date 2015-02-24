<?php
class __Mustache_8600ea94a347ff00c18a4e49f3152491 extends Mustache_Template
{
    private $lambdaHelper;
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'description' section
        $buffer .= $this->sectionEaa34a23f99d94a638c0e6c4fa2bbc5d($context, $indent, $context->find('description'));
        return $buffer;
    }

    private function sectionEaa34a23f99d94a638c0e6c4fa2bbc5d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '      
      <!-- + -->
    
      <div class="author-bio clearfix">
        <a class="avatar rounded" href="{{{author_url}}}">{{{avatar}}}</a>
        <aside class="post-content">
          <h3 class="author"><a href="{{{author_url}}}">{{display_name}}</a></h3>
          {{{description}}}
        </aside>
      </div><!-- .author-bio -->';
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
                $buffer .= $indent . '      <div class="author-bio clearfix">
';
                $buffer .= $indent . '        <a class="avatar rounded" href="';
                $value = $this->resolveValue($context->find('author_url'), $context, $indent);
                $buffer .= $value;
                $buffer .= '">';
                $value = $this->resolveValue($context->find('avatar'), $context, $indent);
                $buffer .= $value;
                $buffer .= '</a>
';
                $buffer .= $indent . '        <aside class="post-content">
';
                $buffer .= $indent . '          <h3 class="author"><a href="';
                $value = $this->resolveValue($context->find('author_url'), $context, $indent);
                $buffer .= $value;
                $buffer .= '">';
                $value = $this->resolveValue($context->find('display_name'), $context, $indent);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</a></h3>
';
                $buffer .= $indent . '          ';
                $value = $this->resolveValue($context->find('description'), $context, $indent);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '        </aside>
';
                $buffer .= $indent . '      </div><!-- .author-bio -->';
                $context->pop();
            }
        }
        return $buffer;
    }
}
