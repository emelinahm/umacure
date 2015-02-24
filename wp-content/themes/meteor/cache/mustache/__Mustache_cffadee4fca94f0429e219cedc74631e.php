<?php
class __Mustache_cffadee4fca94f0429e219cedc74631e extends Mustache_Template
{
    private $lambdaHelper;
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<section id="section-title" class="block"';
        // 'inline_css' section
        $buffer .= $this->sectionA35e2193df2b0249647838fa2f4ade98($context, $indent, $context->find('inline_css'));
        $buffer .= '>
';
        $buffer .= $indent . '  <div class="wrap">
';
        $buffer .= $indent . '    <div class="container">
';
        $buffer .= $indent . '      <h1 class="title">';
        $value = $this->resolveValue($context->find('title'), $context, $indent);
        $buffer .= $value;
        $buffer .= '</h1>
';
        $buffer .= $indent . '      ';
        // 'show_description' section
        $buffer .= $this->sectionC483a459440f0bbbaee9d2ba1f03789f($context, $indent, $context->find('show_description'));
        $buffer .= '
';
        $buffer .= $indent . '    </div><!-- .container -->
';
        $buffer .= $indent . '  </div><!-- .wrap -->
';
        $buffer .= $indent . '  ';
        // 'show_breadcrumb' section
        $buffer .= $this->section8b6fb3c030f9967597ee7d588bc0a2d6($context, $indent, $context->find('show_breadcrumb'));
        $buffer .= '
';
        $buffer .= $indent . '</section><!-- .section-title -->
';
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

    private function sectionC483a459440f0bbbaee9d2ba1f03789f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '{{{description}}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $value = $this->resolveValue($context->find('description'), $context, $indent);
                $buffer .= $value;
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section8b6fb3c030f9967597ee7d588bc0a2d6(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '<div class="breadcrumb">
    <div class="container">
      <p>{{{breadcrumb}}}</p>
    </div><!-- .container -->
  </div><!-- .breadcrumb -->';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= '<div class="breadcrumb">
';
                $buffer .= $indent . '    <div class="container">
';
                $buffer .= $indent . '      <p>';
                $value = $this->resolveValue($context->find('breadcrumb'), $context, $indent);
                $buffer .= $value;
                $buffer .= '</p>
';
                $buffer .= $indent . '    </div><!-- .container -->
';
                $buffer .= $indent . '  </div><!-- .breadcrumb -->';
                $context->pop();
            }
        }
        return $buffer;
    }
}
