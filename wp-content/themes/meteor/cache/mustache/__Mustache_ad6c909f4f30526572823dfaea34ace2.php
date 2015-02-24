<?php
class __Mustache_ad6c909f4f30526572823dfaea34ace2 extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '      <p class="logged-in-as standout">
';
        $buffer .= $indent . '         ';
        $value = $this->resolveValue($context->find('login_label'), $context, $indent);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= ' &nbsp;<a href="';
        $value = $this->resolveValue($context->find('site_url'), $context, $indent);
        $buffer .= $value;
        $buffer .= '//wp-admin/profile.php" class="link-underline">';
        $value = $this->resolveValue($context->find('user_identity'), $context, $indent);
        $buffer .= $value;
        $buffer .= '</a>.
';
        $buffer .= $indent . '          <a class="notd link-hover-accent" href="';
        $value = $this->resolveValue($context->find('logout_url'), $context, $indent);
        $buffer .= $value;
        $buffer .= '" style="margin-left: 1em;">';
        $value = $this->resolveValue($context->find('logout_label'), $context, $indent);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= ' &nbsp;<i class="icon-signout"></i></a>
';
        $buffer .= $indent . '      </p><!-- .logged-in-as -->';
        return $buffer;
    }
}
