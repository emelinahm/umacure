<?php
class __Mustache_cd82d3ebc5b316304017597275b45358 extends Mustache_Template
{
    private $lambdaHelper;
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'single_post' section
        $buffer .= $this->section7489f5a86a920300415eeb3833bec432($context, $indent, $context->find('single_post'));
        // 'single_post' inverted section
        $value = $context->find('single_post');
        if (empty($value)) {
            
            $buffer .= $indent . '
';
            // 'icon' section
            $buffer .= $this->section246600fc92c6f4a3edf277845a81014a($context, $indent, $context->find('icon'));
            $buffer .= '
';
            $buffer .= $indent . '  <div class="post-meta';
            // 'mobile_icon' section
            $buffer .= $this->section6a47280fd1d2dc92c92b9411ceae9852($context, $indent, $context->find('mobile_icon'));
            $buffer .= '">
';
            $buffer .= $indent . '    ';
            // 'is_link' inverted section
            $value = $context->find('is_link');
            if (empty($value)) {
                
                $buffer .= '<h3 class="post-title"><a href="';
                $value = $this->resolveValue($context->find('permalink'), $context, $indent);
                $buffer .= $value;
                $buffer .= '">';
                $value = $this->resolveValue($context->find('title'), $context, $indent);
                $buffer .= $value;
                $buffer .= '</a></h3>';
                $value = $this->resolveValue($context->find(''), $context, $indent);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
            }
            $buffer .= '
';
            $buffer .= $indent . '    ';
            // 'excerpt' section
            $buffer .= $this->sectionC885bf94f639bac068a612826260118e($context, $indent, $context->find('excerpt'));
            // 'excerpt' inverted section
            $value = $context->find('excerpt');
            if (empty($value)) {
                
                // 'metadata_all' section
                $buffer .= $this->section8f2f18fef2b0cfd93776f62e8216a97f($context, $indent, $context->find('metadata_all'));
            }
            $buffer .= '
';
            $buffer .= $indent . '  </div><!-- .post-meta -->
';
            $buffer .= $indent . '  ';
            // 'excerpt_or_editlink' section
            $buffer .= $this->sectionDef12a2ed08dc0d2a49e7040b099ecb3($context, $indent, $context->find('excerpt_or_editlink'));
            $buffer .= '
';
            $buffer .= $indent . '  ';
            // 'excerpt' section
            $buffer .= $this->sectionD5bc6f6dc5fd4baf9b47946ef786c998($context, $indent, $context->find('excerpt'));
            $buffer .= '
';
            $buffer .= $indent . '  ';
            // 'excerpt' section
            $buffer .= $this->section3b34089418e2f3d95d4d1bf286684e3d($context, $indent, $context->find('excerpt'));
            $buffer .= '
';
            // 'icon' section
            $buffer .= $this->section8eb16e3c02ee3f45106085cc2c1ac7f9($context, $indent, $context->find('icon'));
            $buffer .= '
';
        }
        return $buffer;
    }

    private function section7489f5a86a920300415eeb3833bec432(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
{{{rand}}}
';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $value = $this->resolveValue($context->find('rand'), $context, $indent);
                $buffer .= $indent . $value;
                $buffer .= '
';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section246600fc92c6f4a3edf277845a81014a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '<aside class="post-details">
  {{^post_style_aside}}<i class="post-icon {{{icon}}} {{^mobile_icon}}hidden-phone{{/mobile_icon}}"></i>{{/post_style_aside}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '<aside class="post-details">
';
                $buffer .= $indent . '  ';
                // 'post_style_aside' inverted section
                $value = $context->find('post_style_aside');
                if (empty($value)) {
                    
                    $buffer .= '<i class="post-icon ';
                    $value = $this->resolveValue($context->find('icon'), $context, $indent);
                    $buffer .= $value;
                    $buffer .= ' ';
                    // 'mobile_icon' inverted section
                    $value = $context->find('mobile_icon');
                    if (empty($value)) {
                        
                        $buffer .= 'hidden-phone';
                    }
                    $buffer .= '"></i>';
                }
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section6a47280fd1d2dc92c92b9411ceae9852(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = ' mobile-icon';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= ' mobile-icon';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section4a27f41c8150fe10c343f9efe1170338(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '{{{metadata}}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $value = $this->resolveValue($context->find('metadata'), $context, $indent);
                $buffer .= $value;
                $context->pop();
            }
        }
        return $buffer;
    }

    private function sectionC885bf94f639bac068a612826260118e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '{{#metadata}}{{{metadata}}}{{/metadata}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                // 'metadata' section
                $buffer .= $this->section4a27f41c8150fe10c343f9efe1170338($context, $indent, $context->find('metadata'));
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
                $buffer .= $value;
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
                $buffer .= $value;
                $context->pop();
            }
        }
        return $buffer;
    }

    private function sectionDef12a2ed08dc0d2a49e7040b099ecb3(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '<div class="post-content">
    {{{excerpt}}}
    {{#edit_post_link}}{{{edit_post_link}}}{{/edit_post_link}}
  </div><!-- .post-content -->';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= '<div class="post-content">
';
                $buffer .= $indent . '    ';
                $value = $this->resolveValue($context->find('excerpt'), $context, $indent);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '    ';
                // 'edit_post_link' section
                $buffer .= $this->sectionC42b3cb45abddae273b801cede2f5046($context, $indent, $context->find('edit_post_link'));
                $buffer .= '
';
                $buffer .= $indent . '  </div><!-- .post-content -->';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section6f9a892f2b9676a39aa80fd02312f9a7(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '{{{metadata_tags}}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $value = $this->resolveValue($context->find('metadata_tags'), $context, $indent);
                $buffer .= $value;
                $context->pop();
            }
        }
        return $buffer;
    }

    private function sectionD5bc6f6dc5fd4baf9b47946ef786c998(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '{{#metadata_tags}}{{{metadata_tags}}}{{/metadata_tags}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                // 'metadata_tags' section
                $buffer .= $this->section6f9a892f2b9676a39aa80fd02312f9a7($context, $indent, $context->find('metadata_tags'));
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section196713513b67fddeb0ffaef6ebe6f6ee(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '<div class="more-link-container">
    <a class="{{{link_class}}}" href="{{{permalink}}}">{{{more_text}}}</a>
  </div><!-- .more-link-container -->';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= '<div class="more-link-container">
';
                $buffer .= $indent . '    <a class="';
                $value = $this->resolveValue($context->find('link_class'), $context, $indent);
                $buffer .= $value;
                $buffer .= '" href="';
                $value = $this->resolveValue($context->find('permalink'), $context, $indent);
                $buffer .= $value;
                $buffer .= '">';
                $value = $this->resolveValue($context->find('more_text'), $context, $indent);
                $buffer .= $value;
                $buffer .= '</a>
';
                $buffer .= $indent . '  </div><!-- .more-link-container -->';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section3b34089418e2f3d95d4d1bf286684e3d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '{{#show_link}}<div class="more-link-container">
    <a class="{{{link_class}}}" href="{{{permalink}}}">{{{more_text}}}</a>
  </div><!-- .more-link-container -->{{/show_link}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                // 'show_link' section
                $buffer .= $this->section196713513b67fddeb0ffaef6ebe6f6ee($context, $indent, $context->find('show_link'));
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section8eb16e3c02ee3f45106085cc2c1ac7f9(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '</aside><!-- .post-details -->';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '</aside><!-- .post-details -->';
                $context->pop();
            }
        }
        return $buffer;
    }
}
