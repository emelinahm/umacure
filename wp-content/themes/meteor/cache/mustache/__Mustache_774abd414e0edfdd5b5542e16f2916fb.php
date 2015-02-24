<?php
class __Mustache_774abd414e0edfdd5b5542e16f2916fb extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';

        $buffer .= $indent . '<div class="meteor-404 post-content">
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<h1 class="big-404"><i class="';
        $value = $this->resolveValue($context->find('icon'), $context, $indent);
        $buffer .= $value;
        $buffer .= '"></i></h1>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<div class="ovh">
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '  <h2>';
        $value = $this->resolveValue($context->find('error_label'), $context, $indent);
        $buffer .= $value;
        $buffer .= '</h2>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '  <p>';
        $value = $this->resolveValue($context->find('error_description'), $context, $indent);
        $buffer .= $value;
        $buffer .= '</p>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '  <form class="meteor-form" action="';
        $value = $this->resolveValue($context->find('home_url'), $context, $indent);
        $buffer .= $value;
        $buffer .= '" method="get" >
';
        $buffer .= $indent . '    <div class="dual-container entry text clearfix">
';
        $buffer .= $indent . '      <div class="entry">
';
        $buffer .= $indent . '        <input class="float-left" type="text" name="s" placeholder="';
        $value = $this->resolveValue($context->find('search_text'), $context, $indent);
        $buffer .= $value;
        $buffer .= '" />
';
        $buffer .= $indent . '      </div><!-- .entry -->
';
        $buffer .= $indent . '    </div><!-- .dual-container -->
';
        $buffer .= $indent . '  </form><!-- .meteor-form -->
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '  <p><a class="meteor-button medium" href="';
        $value = $this->resolveValue($context->find('home_url'), $context, $indent);
        $buffer .= $value;
        $buffer .= '"><i class="';
        $value = $this->resolveValue($context->find('home_btn_icon'), $context, $indent);
        $buffer .= $value;
        $buffer .= '"></i> &nbsp; ';
        $value = $this->resolveValue($context->find('back_to_homepage'), $context, $indent);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '</a></p>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '</div><!-- .ovh -->
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '</div><!-- .post-content -->';
        return $buffer;
    }
}
