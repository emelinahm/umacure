<?php
class __Mustache_a67b0498224ea08b366a362f2c70f043 extends Mustache_Template
{
    private $lambdaHelper;
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'shownav' section
        $buffer .= $this->section33708d51768663ebfdeed3e016cd7004($context, $indent, $context->find('shownav'));
        return $buffer;
    }

    private function section6a6f918a4e49c0d1d6c7a6ab88ff3163(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '{{{prev_title}}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $value = $this->resolveValue($context->find('prev_title'), $context, $indent);
                $buffer .= $value;
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section40cba5207b26acd221469955736c57b5(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '<div class="nav-container prev clearfix">
            <a class="post-link" href="{{{prev}}}">
              <i class="icon-angle-left"></i>
              <span class="visible-phone">{{prev_label}}</span>
              <span class="hidden-phone">{{#prev_title}}{{{prev_title}}}{{/prev_title}}{{^prev_title}}{{prev_label}}{{/prev_title}}</span>
        {{!        <!-- <span class="hidden-phone">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</span> --> }}
            </a>
          </div><!-- .nav-container -->';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= '<div class="nav-container prev clearfix">
';
                $buffer .= $indent . '            <a class="post-link" href="';
                $value = $this->resolveValue($context->find('prev'), $context, $indent);
                $buffer .= $value;
                $buffer .= '">
';
                $buffer .= $indent . '              <i class="icon-angle-left"></i>
';
                $buffer .= $indent . '              <span class="visible-phone">';
                $value = $this->resolveValue($context->find('prev_label'), $context, $indent);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</span>
';
                $buffer .= $indent . '              <span class="hidden-phone">';
                // 'prev_title' section
                $buffer .= $this->section6a6f918a4e49c0d1d6c7a6ab88ff3163($context, $indent, $context->find('prev_title'));
                // 'prev_title' inverted section
                $value = $context->find('prev_title');
                if (empty($value)) {
                    
                    $value = $this->resolveValue($context->find('prev_label'), $context, $indent);
                    $buffer .= call_user_func($this->mustache->getEscape(), $value);
                }
                $buffer .= '</span>
';
                $buffer .= $indent . '            </a>
';
                $buffer .= $indent . '          </div><!-- .nav-container -->';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section7e07106d49d01fb8062cad89df0f2805(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '{{{next_title}}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $value = $this->resolveValue($context->find('next_title'), $context, $indent);
                $buffer .= $value;
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section000faf32e313699033d4fd96c5cdf305(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '<div class="nav-container next clearfix">
            <a class="post-link" href="{{{next}}}">
              <span class="visible-phone">{{next_label}} <i class="icon-angle-right"></i></span>
              <span class="hidden-phone">{{#next_title}}{{{next_title}}}{{/next_title}}{{^next_title}}{{next_label}}{{/next_title}} <i class="icon-angle-right"></i></span>
        {{!        <!-- <span class="hidden-phone">Ut enim ad minim et ven  veniam, quis nostrud exercitation ullamco <i class="icon-angle-right"></i></span> --> }}
            </a>
          </div><!-- .nav-container -->';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= '<div class="nav-container next clearfix">
';
                $buffer .= $indent . '            <a class="post-link" href="';
                $value = $this->resolveValue($context->find('next'), $context, $indent);
                $buffer .= $value;
                $buffer .= '">
';
                $buffer .= $indent . '              <span class="visible-phone">';
                $value = $this->resolveValue($context->find('next_label'), $context, $indent);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= ' <i class="icon-angle-right"></i></span>
';
                $buffer .= $indent . '              <span class="hidden-phone">';
                // 'next_title' section
                $buffer .= $this->section7e07106d49d01fb8062cad89df0f2805($context, $indent, $context->find('next_title'));
                // 'next_title' inverted section
                $value = $context->find('next_title');
                if (empty($value)) {
                    
                    $value = $this->resolveValue($context->find('next_label'), $context, $indent);
                    $buffer .= call_user_func($this->mustache->getEscape(), $value);
                }
                $buffer .= ' <i class="icon-angle-right"></i></span>
';
                $buffer .= $indent . '            </a>
';
                $buffer .= $indent . '          </div><!-- .nav-container -->';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section33708d51768663ebfdeed3e016cd7004(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <!-- + -->

        <div class="single-post-navigation">
          {{#prev}}<div class="nav-container prev clearfix">
            <a class="post-link" href="{{{prev}}}">
              <i class="icon-angle-left"></i>
              <span class="visible-phone">{{prev_label}}</span>
              <span class="hidden-phone">{{#prev_title}}{{{prev_title}}}{{/prev_title}}{{^prev_title}}{{prev_label}}{{/prev_title}}</span>
        {{!        <!-- <span class="hidden-phone">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</span> --> }}
            </a>
          </div><!-- .nav-container -->{{/prev}}
          {{#next}}<div class="nav-container next clearfix">
            <a class="post-link" href="{{{next}}}">
              <span class="visible-phone">{{next_label}} <i class="icon-angle-right"></i></span>
              <span class="hidden-phone">{{#next_title}}{{{next_title}}}{{/next_title}}{{^next_title}}{{next_label}}{{/next_title}} <i class="icon-angle-right"></i></span>
        {{!        <!-- <span class="hidden-phone">Ut enim ad minim et ven  veniam, quis nostrud exercitation ullamco <i class="icon-angle-right"></i></span> --> }}
            </a>
          </div><!-- .nav-container -->{{/next}}
        </div><!-- .single-post-navigation -->';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '        <!-- + -->
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '        <div class="single-post-navigation">
';
                $buffer .= $indent . '          ';
                // 'prev' section
                $buffer .= $this->section40cba5207b26acd221469955736c57b5($context, $indent, $context->find('prev'));
                $buffer .= '
';
                $buffer .= $indent . '          ';
                // 'next' section
                $buffer .= $this->section000faf32e313699033d4fd96c5cdf305($context, $indent, $context->find('next'));
                $buffer .= '
';
                $buffer .= $indent . '        </div><!-- .single-post-navigation -->';
                $context->pop();
            }
        }
        return $buffer;
    }
}
