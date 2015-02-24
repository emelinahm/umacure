<?php
class __Mustache_ec72d933bbf6e70bdcf85f994173abd5 extends Mustache_Template
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
        $buffer .= $indent . '      <!-- + -->
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '      <div class="share-links">
';
        $buffer .= $indent . '        <strong>Share this story</strong>
';
        $buffer .= $indent . '        <ul class="social-icons tooltips clearfix" data-tooltip-options="placement: top, delay.show: 200, delay.hide: 80, container: body">
';
        $buffer .= $indent . '          ';
        // 'image' section
        $buffer .= $this->section6ce60c5436e0fd93b6d31bae2d2a528b($context, $indent, $context->find('image'));
        $buffer .= '
';
        $buffer .= $indent . '          <li data-share="twitter" title="Twitter"><a class="social-';
        $value = $this->resolveValue($context->find('icon_color'), $context, $indent);
        $buffer .= $value;
        $buffer .= '-24 twitter" href="http://twitter.com/home?status=';
        $value = $this->resolveValue($context->find('title'), $context, $indent);
        $buffer .= $value;
        $value = $this->resolveValue($context->find('space'), $context, $indent);
        $buffer .= $value;
        $value = $this->resolveValue($context->find('url'), $context, $indent);
        $buffer .= $value;
        $buffer .= '"></a></li>
';
        $buffer .= $indent . '          <li data-share="facebook" title="Facebook"><a class="social-';
        $value = $this->resolveValue($context->find('icon_color'), $context, $indent);
        $buffer .= $value;
        $buffer .= '-24 facebook" href="http://www.facebook.com/sharer.php?u=';
        $value = $this->resolveValue($context->find('url'), $context, $indent);
        $buffer .= $value;
        $buffer .= '"></a></li>
';
        $buffer .= $indent . '          <li data-share="reddit" title="Reddit"><a class="social-';
        $value = $this->resolveValue($context->find('icon_color'), $context, $indent);
        $buffer .= $value;
        $buffer .= '-24 reddit" href="http://reddit.com/submit?url=';
        $value = $this->resolveValue($context->find('url'), $context, $indent);
        $buffer .= $value;
        $buffer .= '"></a></li>
';
        $buffer .= $indent . '          <li data-share="linkedin" title="Linkedin"><a class="social-';
        $value = $this->resolveValue($context->find('icon_color'), $context, $indent);
        $buffer .= $value;
        $buffer .= '-24 linkedin" href="http://linkedin.com/shareArticle?mini=true&url=';
        $value = $this->resolveValue($context->find('url'), $context, $indent);
        $buffer .= $value;
        $buffer .= '"></a></li>
';
        $buffer .= $indent . '          <li data-share="digg" title="Digg"><a class="social-';
        $value = $this->resolveValue($context->find('icon_color'), $context, $indent);
        $buffer .= $value;
        $buffer .= '-24 digg" href="http://digg.com/submit?phase=2&url=';
        $value = $this->resolveValue($context->find('url'), $context, $indent);
        $buffer .= $value;
        $buffer .= '&bodytext=&tags=&title=';
        $value = $this->resolveValue($context->find('title'), $context, $indent);
        $buffer .= $value;
        $buffer .= '"></a></li>
';
        $buffer .= $indent . '          <li data-share="delicious" title="Delicious"><a class="social-';
        $value = $this->resolveValue($context->find('icon_color'), $context, $indent);
        $buffer .= $value;
        $buffer .= '-24 delicious" href="http://www.delicious.com/post?v=2&url=';
        $value = $this->resolveValue($context->find('url'), $context, $indent);
        $buffer .= $value;
        $buffer .= '&notes=&tags=&title=';
        $value = $this->resolveValue($context->find('title'), $context, $indent);
        $buffer .= $value;
        $buffer .= '"></a></li>
';
        $buffer .= $indent . '          <li data-share="googleplus" title="Google Plus"><a class="social-';
        $value = $this->resolveValue($context->find('icon_color'), $context, $indent);
        $buffer .= $value;
        $buffer .= '-24 googleplus" href="http://google.com/bookmarks/mark?op=edit&bkmk=';
        $value = $this->resolveValue($context->find('url'), $context, $indent);
        $buffer .= $value;
        $buffer .= '&title=';
        $value = $this->resolveValue($context->find('title'), $context, $indent);
        $buffer .= $value;
        $buffer .= '"></a></li>
';
        $buffer .= $indent . '          <li data-share="email" title="Email"><a class="social-';
        $value = $this->resolveValue($context->find('icon_color'), $context, $indent);
        $buffer .= $value;
        $buffer .= '-24 email" href="mailto:?subject=';
        $value = $this->resolveValue($context->find('title'), $context, $indent);
        $buffer .= $value;
        $buffer .= '&body=';
        $value = $this->resolveValue($context->find('url'), $context, $indent);
        $buffer .= $value;
        $buffer .= '"></a></li>
';
        $buffer .= $indent . '        </ul><!-- .social-icons -->
';
        $buffer .= $indent . '      </div><!-- .share-links -->';
        return $buffer;
    }

    private function section6ce60c5436e0fd93b6d31bae2d2a528b(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '<li data-share="pinterest" title="Pinterest"><a class="social-{{{icon_color}}}-24 pinterest" href="http://pinterest.com/pin/create/button/?url={{{url}}}&media={{{image}}}&description={{{title}}}"></a></li>';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= '<li data-share="pinterest" title="Pinterest"><a class="social-';
                $value = $this->resolveValue($context->find('icon_color'), $context, $indent);
                $buffer .= $value;
                $buffer .= '-24 pinterest" href="http://pinterest.com/pin/create/button/?url=';
                $value = $this->resolveValue($context->find('url'), $context, $indent);
                $buffer .= $value;
                $buffer .= '&media=';
                $value = $this->resolveValue($context->find('image'), $context, $indent);
                $buffer .= $value;
                $buffer .= '&description=';
                $value = $this->resolveValue($context->find('title'), $context, $indent);
                $buffer .= $value;
                $buffer .= '"></a></li>';
                $context->pop();
            }
        }
        return $buffer;
    }
}
