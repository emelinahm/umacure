<?php
class __Mustache_b856b00a166c31d328183cbed713dbd7 extends Mustache_Template
{
    private $lambdaHelper;
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '/* Typography Styles */
';
        $buffer .= $indent . '
';
        $buffer .= $indent . 'body { font-size: ';
        $value = $this->resolveValue($context->find('fontSize'), $context, $indent);
        $buffer .= $value;
        $buffer .= 'px; }
';
        $buffer .= $indent . '
';
        $buffer .= $indent . 'header[role=banner] ul#navigation > li > a { font-size: ';
        $value = $this->resolveValue($context->find('navFontSize'), $context, $indent);
        $buffer .= $value;
        $buffer .= '%; }
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.mceContentBody { font-size: ';
        $value = $this->resolveValue($context->find('editorFontSize'), $context, $indent);
        $buffer .= $value;
        $buffer .= 'px !important; }
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.post-content h1, .mceContentBody h1 { font-size: ';
        $value = $this->resolveValue($context->find('h1FontSize'), $context, $indent);
        $buffer .= $value;
        $buffer .= '% !important; }
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.post-content h2, .mceContentBody h2 { font-size: ';
        $value = $this->resolveValue($context->find('h2FontSize'), $context, $indent);
        $buffer .= $value;
        $buffer .= '% !important; }
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.post-content h3, .mceContentBody h3 { font-size: ';
        $value = $this->resolveValue($context->find('h3FontSize'), $context, $indent);
        $buffer .= $value;
        $buffer .= '% !important; }
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.post-content h4, .mceContentBody h4 { font-size: ';
        $value = $this->resolveValue($context->find('h4FontSize'), $context, $indent);
        $buffer .= $value;
        $buffer .= '% !important; }
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.post-content h5, .mceContentBody h5 { font-size: ';
        $value = $this->resolveValue($context->find('h5FontSize'), $context, $indent);
        $buffer .= $value;
        $buffer .= '% !important; }
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.post-content h6, .mceContentBody h6 { font-size: ';
        $value = $this->resolveValue($context->find('h6FontSize'), $context, $indent);
        $buffer .= $value;
        $buffer .= '% !important; }
';
        $buffer .= $indent . '
';
        $buffer .= $indent . 'body,
';
        $buffer .= $indent . 'input[type=text],
';
        $buffer .= $indent . 'input[type=email],
';
        $buffer .= $indent . 'input[type=password],
';
        $buffer .= $indent . 'textarea,
';
        $buffer .= $indent . '.meteor-comments ol.commentlist li.comment footer.comment-meta div.comment-author span.fn .meta-tag,
';
        $buffer .= $indent . '.meteor-skills ul li em,
';
        $buffer .= $indent . '.cute-slider .br-slideinfo .text, .body-font {
';
        $buffer .= $indent . '  font-family: ';
        // 'webfontsEnabled' section
        $buffer .= $this->sectionCca811d3196dbde71506deee7ac0e834($context, $indent, $context->find('webfontsEnabled'));
        // 'webfontsEnabled' inverted section
        $value = $context->find('webfontsEnabled');
        if (empty($value)) {
            
            $buffer .= '"PT Sans", Helvetica, Arial';
        }
        $buffer .= ', sans-serif !important;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . 'h1,
';
        $buffer .= $indent . 'h2,
';
        $buffer .= $indent . 'h3,
';
        $buffer .= $indent . 'h4,
';
        $buffer .= $indent . 'h5,
';
        $buffer .= $indent . 'h6,
';
        $buffer .= $indent . '.sidebar .widget>h3.title,
';
        $buffer .= $indent . 'div.meteor-icon-big span.title,
';
        $buffer .= $indent . '.post-content h1,
';
        $buffer .= $indent . '.post-content h2,
';
        $buffer .= $indent . '.post-content h3,
';
        $buffer .= $indent . '.post-content h4,
';
        $buffer .= $indent . '.post-content h5,
';
        $buffer .= $indent . '.post-content h6,
';
        $buffer .= $indent . 'table th,
';
        $buffer .= $indent . 'header[role=banner] select#mobile-nav,
';
        $buffer .= $indent . 'header[role=banner] select#mobile-nav>*,
';
        $buffer .= $indent . 'header[role=banner] ul#navigation>li>a,
';
        $buffer .= $indent . '#section-title .container h1.title,
';
        $buffer .= $indent . '.meteor-comments ol.commentlist li.comment footer.comment-meta div.comment-author span.fn,
';
        $buffer .= $indent . '.meteor-comments ol.commentlist li.comment footer.comment-meta div.comment-author span.fn a,
';
        $buffer .= $indent . 'div.meteor-post div.single-post-navigation a.post-link,
';
        $buffer .= $indent . 'div.meteor-post div.share-links strong,
';
        $buffer .= $indent . 'div.meteor-post>.post-details>.post-meta h3.post-title,
';
        $buffer .= $indent . 'div.meteor-post.format-status .status-container .post-content,
';
        $buffer .= $indent . '.meteor-button,
';
        $buffer .= $indent . '.meteor-quote,
';
        $buffer .= $indent . '.meteor-tabs>ul.tabs-head li,
';
        $buffer .= $indent . '.meteor-skills ul li strong,
';
        $buffer .= $indent . '.meteor-pricing .pricing-item>div.price span.amount,
';
        $buffer .= $indent . '.meteor-slider .meteor-meta>.container>a h3,
';
        $buffer .= $indent . '.cute-slider .br-slideinfo .title, .alternate-font, .tp-caption {
';
        $buffer .= $indent . '  font-family: ';
        // 'webfontsEnabled' section
        $buffer .= $this->sectionAe0d17e630ea4f48999017ba16d4254c($context, $indent, $context->find('webfontsEnabled'));
        // 'webfontsEnabled' inverted section
        $value = $context->find('webfontsEnabled');
        if (empty($value)) {
            
            $buffer .= '"Raleway", "Helvetica Neue", Helvetica, Arial';
        }
        $buffer .= ', sans-serif !important;
';
        $buffer .= $indent . '}';
        return $buffer;
    }

    private function sectionCca811d3196dbde71506deee7ac0e834(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '"{{{primaryFont}}}"';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= '"';
                $value = $this->resolveValue($context->find('primaryFont'), $context, $indent);
                $buffer .= $value;
                $buffer .= '"';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function sectionAe0d17e630ea4f48999017ba16d4254c(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '"{{{secondaryFont}}}"';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= '"';
                $value = $this->resolveValue($context->find('secondaryFont'), $context, $indent);
                $buffer .= $value;
                $buffer .= '"';
                $context->pop();
            }
        }
        return $buffer;
    }
}
