<?php
class __Mustache_ae24d9b900680a5169dd56d16dcacbd0 extends Mustache_Template
{
    private $lambdaHelper;
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'single_post' inverted section
        $value = $context->find('single_post');
        if (empty($value)) {
            
            $buffer .= $indent . '<div class="post-listing ';
            $value = $this->resolveValue($context->find('container_class'), $context, $indent);
            $buffer .= $value;
            // 'visibility' section
            $buffer .= $this->section76fcbda0378b75df8ebb986fc77a0385($context, $indent, $context->find('visibility'));
            $buffer .= '"';
            // 'opt_permalink' section
            $buffer .= $this->section97d8aae54a0c35898ada0850d9c95e90($context, $indent, $context->find('opt_permalink'));
            // 'opt_lightbox' section
            $buffer .= $this->sectionC4607814af50954521a494c717e8bc62($context, $indent, $context->find('opt_lightbox'));
            // 'click_behavior' section
            $buffer .= $this->section554dd704a4f80cfebaf3f0f32bc00b20($context, $indent, $context->find('click_behavior'));
            // 'inline_css' section
            $buffer .= $this->sectionA35e2193df2b0249647838fa2f4ade98($context, $indent, $context->find('inline_css'));
            $buffer .= '>
';
            // 'title_heading' section
            $buffer .= $this->section0980f78ad684ef9d2a1e6823bc3fbc1e($context, $indent, $context->find('title_heading'));
        }
        $buffer .= '
';
        // 'single_post' section
        $buffer .= $this->sectionE8a35f2374255dce4891d54b37ff0e2f($context, $indent, $context->find('single_post'));
        $buffer .= '
';
        // 'posts' section
        $buffer .= $this->section210d997e57446a43efa1a893b17b235d($context, $indent, $context->find('posts'));
        $buffer .= $indent . '
';
        // 'single_post' inverted section
        $value = $context->find('single_post');
        if (empty($value)) {
            
            // 'show_pagination' section
            $buffer .= $this->section99868a19e50e849d2bab92b6e4256987($context, $indent, $context->find('show_pagination'));
        }
        $buffer .= '
';
        $buffer .= $indent . '
';
        // 'no_posts_content' section
        $buffer .= $this->section5786d60ed7364db08ee08abb89ef5ee5($context, $indent, $context->find('no_posts_content'));
        $buffer .= '
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '</div><!-- .post-listing -->';
        return $buffer;
    }

    private function section76fcbda0378b75df8ebb986fc77a0385(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = ' {{{visibility}}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= ' ';
                $value = $this->resolveValue($context->find('visibility'), $context, $indent);
                $buffer .= $value;
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section97d8aae54a0c35898ada0850d9c95e90(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = ' data-permalink="true"';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= ' data-permalink="true"';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section22b7818b328fc284aacf8022114c31d8(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = ' data-gallery="true"';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= ' data-gallery="true"';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function sectionC4607814af50954521a494c717e8bc62(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = ' data-lightbox="true"{{#opt_gallery}} data-gallery="true"{{/opt_gallery}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= ' data-lightbox="true"';
                // 'opt_gallery' section
                $buffer .= $this->section22b7818b328fc284aacf8022114c31d8($context, $indent, $context->find('opt_gallery'));
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section554dd704a4f80cfebaf3f0f32bc00b20(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = ' data-click-behavior="{{{click_behavior}}}"';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= ' data-click-behavior="';
                $value = $this->resolveValue($context->find('click_behavior'), $context, $indent);
                $buffer .= $value;
                $buffer .= '"';
                $context->pop();
            }
        }
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

    private function section0980f78ad684ef9d2a1e6823bc3fbc1e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '<h2 class="title-heading">{{title_heading}}</h2>';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '<h2 class="title-heading">';
                $value = $this->resolveValue($context->find('title_heading'), $context, $indent);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</h2>';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function sectionB414edbcb5a27dc84f4abf7847cfce97(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = ' data-lightbox="true"';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= ' data-lightbox="true"';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function sectionE8a35f2374255dce4891d54b37ff0e2f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '<div class="post-listing {{{container_class}}}"{{#opt_permalink}} data-permalink="true"{{/opt_permalink}}{{#opt_lightbox}} data-lightbox="true"{{/opt_lightbox}}{{#opt_gallery}} data-gallery="true"{{/opt_gallery}}{{#click_behavior}} data-click-behavior="{{{click_behavior}}}"{{/click_behavior}}>';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '<div class="post-listing ';
                $value = $this->resolveValue($context->find('container_class'), $context, $indent);
                $buffer .= $value;
                $buffer .= '"';
                // 'opt_permalink' section
                $buffer .= $this->section97d8aae54a0c35898ada0850d9c95e90($context, $indent, $context->find('opt_permalink'));
                // 'opt_lightbox' section
                $buffer .= $this->sectionB414edbcb5a27dc84f4abf7847cfce97($context, $indent, $context->find('opt_lightbox'));
                // 'opt_gallery' section
                $buffer .= $this->section22b7818b328fc284aacf8022114c31d8($context, $indent, $context->find('opt_gallery'));
                // 'click_behavior' section
                $buffer .= $this->section554dd704a4f80cfebaf3f0f32bc00b20($context, $indent, $context->find('click_behavior'));
                $buffer .= '>';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section3caeca9026effd27aa487afb902754a7(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '{{> meteor_single_post_attrs}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                if ($partial = $this->mustache->loadPartial('meteor_single_post_attrs')) {
                    $buffer .= $partial->renderInternal($context, '');
                }
                $context->pop();
            }
        }
        return $buffer;
    }

    private function sectionCb52925824ac380ceb2e030f2fd1edda(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '{{{image_src}}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $value = $this->resolveValue($context->find('image_src'), $context, $indent);
                $buffer .= $value;
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section8f84250672dba9d12c0ee7d0ba8e4ae8(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '{{{icon}}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $value = $this->resolveValue($context->find('icon'), $context, $indent);
                $buffer .= $value;
                $context->pop();
            }
        }
        return $buffer;
    }

    private function sectionA992b59cd12ea115289c042c70126c33(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '<div class="post-img frame" data-permalink="{{{permalink}}}">
      <a href="{{#opt_lightbox}}{{{image_src}}}{{/opt_lightbox}}{{^opt_lightbox}}{{{permalink}}}{{/opt_lightbox}}"><img alt="{{{title}}}" src="{{{image}}}" /></a>
      <i class="post-icon {{#icon}}{{{icon}}}{{/icon}}{{^icon}}icon-pencil{{/icon}}{{^single_post}} visible-phone{{/single_post}}"></i>
    </div><!-- .post-img -->';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= '<div class="post-img frame" data-permalink="';
                $value = $this->resolveValue($context->find('permalink'), $context, $indent);
                $buffer .= $value;
                $buffer .= '">
';
                $buffer .= $indent . '      <a href="';
                // 'opt_lightbox' section
                $buffer .= $this->sectionCb52925824ac380ceb2e030f2fd1edda($context, $indent, $context->find('opt_lightbox'));
                // 'opt_lightbox' inverted section
                $value = $context->find('opt_lightbox');
                if (empty($value)) {
                    
                    $value = $this->resolveValue($context->find('permalink'), $context, $indent);
                    $buffer .= $value;
                }
                $buffer .= '"><img alt="';
                $value = $this->resolveValue($context->find('title'), $context, $indent);
                $buffer .= $value;
                $buffer .= '" src="';
                $value = $this->resolveValue($context->find('image'), $context, $indent);
                $buffer .= $value;
                $buffer .= '" /></a>
';
                $buffer .= $indent . '      <i class="post-icon ';
                // 'icon' section
                $buffer .= $this->section8f84250672dba9d12c0ee7d0ba8e4ae8($context, $indent, $context->find('icon'));
                // 'icon' inverted section
                $value = $context->find('icon');
                if (empty($value)) {
                    
                    $buffer .= 'icon-pencil';
                }
                // 'single_post' inverted section
                $value = $context->find('single_post');
                if (empty($value)) {
                    
                    $buffer .= ' visible-phone';
                }
                $buffer .= '"></i>
';
                $buffer .= $indent . '    </div><!-- .post-img -->';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section7be615a7663a68a79bc29165403d851e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
{{!

    STANDARD

}}
  <div class="meteor-post item {{{post_class}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    {{^is_attachment}}{{#image}}<div class="post-img frame" data-permalink="{{{permalink}}}">
      <a href="{{#opt_lightbox}}{{{image_src}}}{{/opt_lightbox}}{{^opt_lightbox}}{{{permalink}}}{{/opt_lightbox}}"><img alt="{{{title}}}" src="{{{image}}}" /></a>
      <i class="post-icon {{#icon}}{{{icon}}}{{/icon}}{{^icon}}icon-pencil{{/icon}}{{^single_post}} visible-phone{{/single_post}}"></i>
    </div><!-- .post-img -->{{/image}}{{/is_attachment}}
    {{> meteor_post_details}}
  </div><!-- .meteor-post -->
  ';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '  <div class="meteor-post item ';
                $value = $this->resolveValue($context->find('post_class'), $context, $indent);
                $buffer .= $value;
                $buffer .= '"';
                // 'single_post' section
                $buffer .= $this->section3caeca9026effd27aa487afb902754a7($context, $indent, $context->find('single_post'));
                $buffer .= '>
';
                $buffer .= $indent . '    ';
                // 'is_attachment' inverted section
                $value = $context->find('is_attachment');
                if (empty($value)) {
                    
                    // 'image' section
                    $buffer .= $this->sectionA992b59cd12ea115289c042c70126c33($context, $indent, $context->find('image'));
                }
                $buffer .= '
';
                if ($partial = $this->mustache->loadPartial('meteor_post_details')) {
                    $buffer .= $partial->renderInternal($context, '    ');
                }
                $buffer .= $indent . '  </div><!-- .meteor-post -->
';
                $buffer .= $indent . '  ';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function sectionD3a66900e4520c1843780b16e2e3bb6a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = 'height="{{{height}}}" ';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= 'height="';
                $value = $this->resolveValue($context->find('height'), $context, $indent);
                $buffer .= $value;
                $buffer .= '" ';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function sectionD041d21a2e8d4a7bef80208f6c8417dc(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
  {{! 
    
    STANDARD ASIDE
    
  }}
  <div class="meteor-post thumb-aside item {{{post_class}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    <div class="post-img frame" data-permalink="{{{permalink}}}">
      <a href="{{#opt_lightbox}}{{{image_src}}}{{/opt_lightbox}}{{^opt_lightbox}}{{{permalink}}}{{/opt_lightbox}}"><img style="width: {{{width}}}px;" {{#height}}height="{{{height}}}" {{/height}}src="{{{image}}}" /></a>
      <i class="post-icon icon-pencil"></i>
    </div><!-- .post-img -->
    {{> meteor_post_details}}
  </div><!-- .meteor-post.thumb-aside -->
  ';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= '
';
                $buffer .= $indent . '  <div class="meteor-post thumb-aside item ';
                $value = $this->resolveValue($context->find('post_class'), $context, $indent);
                $buffer .= $value;
                $buffer .= '"';
                // 'single_post' section
                $buffer .= $this->section3caeca9026effd27aa487afb902754a7($context, $indent, $context->find('single_post'));
                $buffer .= '>
';
                $buffer .= $indent . '    <div class="post-img frame" data-permalink="';
                $value = $this->resolveValue($context->find('permalink'), $context, $indent);
                $buffer .= $value;
                $buffer .= '">
';
                $buffer .= $indent . '      <a href="';
                // 'opt_lightbox' section
                $buffer .= $this->sectionCb52925824ac380ceb2e030f2fd1edda($context, $indent, $context->find('opt_lightbox'));
                // 'opt_lightbox' inverted section
                $value = $context->find('opt_lightbox');
                if (empty($value)) {
                    
                    $value = $this->resolveValue($context->find('permalink'), $context, $indent);
                    $buffer .= $value;
                }
                $buffer .= '"><img style="width: ';
                $value = $this->resolveValue($context->find('width'), $context, $indent);
                $buffer .= $value;
                $buffer .= 'px;" ';
                // 'height' section
                $buffer .= $this->sectionD3a66900e4520c1843780b16e2e3bb6a($context, $indent, $context->find('height'));
                $buffer .= 'src="';
                $value = $this->resolveValue($context->find('image'), $context, $indent);
                $buffer .= $value;
                $buffer .= '" /></a>
';
                $buffer .= $indent . '      <i class="post-icon icon-pencil"></i>
';
                $buffer .= $indent . '    </div><!-- .post-img -->
';
                if ($partial = $this->mustache->loadPartial('meteor_post_details')) {
                    $buffer .= $partial->renderInternal($context, '    ');
                }
                $buffer .= $indent . '  </div><!-- .meteor-post.thumb-aside -->
';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function sectionE510b0e5d996ffbd9069594dfd70c5c9(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
  {{#post_style_normal}}
{{!

    STANDARD

}}
  <div class="meteor-post item {{{post_class}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    {{^is_attachment}}{{#image}}<div class="post-img frame" data-permalink="{{{permalink}}}">
      <a href="{{#opt_lightbox}}{{{image_src}}}{{/opt_lightbox}}{{^opt_lightbox}}{{{permalink}}}{{/opt_lightbox}}"><img alt="{{{title}}}" src="{{{image}}}" /></a>
      <i class="post-icon {{#icon}}{{{icon}}}{{/icon}}{{^icon}}icon-pencil{{/icon}}{{^single_post}} visible-phone{{/single_post}}"></i>
    </div><!-- .post-img -->{{/image}}{{/is_attachment}}
    {{> meteor_post_details}}
  </div><!-- .meteor-post -->
  {{/post_style_normal}}{{#post_style_aside}}
  {{! 
    
    STANDARD ASIDE
    
  }}
  <div class="meteor-post thumb-aside item {{{post_class}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    <div class="post-img frame" data-permalink="{{{permalink}}}">
      <a href="{{#opt_lightbox}}{{{image_src}}}{{/opt_lightbox}}{{^opt_lightbox}}{{{permalink}}}{{/opt_lightbox}}"><img style="width: {{{width}}}px;" {{#height}}height="{{{height}}}" {{/height}}src="{{{image}}}" /></a>
      <i class="post-icon icon-pencil"></i>
    </div><!-- .post-img -->
    {{> meteor_post_details}}
  </div><!-- .meteor-post.thumb-aside -->
  {{/post_style_aside}}
';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                // 'post_style_normal' section
                $buffer .= $this->section7be615a7663a68a79bc29165403d851e($context, $indent, $context->find('post_style_normal'));
                // 'post_style_aside' section
                $buffer .= $this->sectionD041d21a2e8d4a7bef80208f6c8417dc($context, $indent, $context->find('post_style_aside'));
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section27a5d560069928301d193edc18ba43f0(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '<a href="{{{meta_quote_url}}}">';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= '<a href="';
                $value = $this->resolveValue($context->find('meta_quote_url'), $context, $indent);
                $buffer .= $value;
                $buffer .= '">';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section3ae39e3dce9945d5872a2a828daa4528(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '</a>';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= '</a>';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function sectionB2f10c3aca3074d55cac39934dde63d9(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '<span class="author">{{#meta_quote_url}}<a href="{{{meta_quote_url}}}">{{/meta_quote_url}}{{meta_quote_author}}{{#meta_quote_url}}</a>{{/meta_quote_url}}</span>';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= '<span class="author">';
                // 'meta_quote_url' section
                $buffer .= $this->section27a5d560069928301d193edc18ba43f0($context, $indent, $context->find('meta_quote_url'));
                $value = $this->resolveValue($context->find('meta_quote_author'), $context, $indent);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                // 'meta_quote_url' section
                $buffer .= $this->section3ae39e3dce9945d5872a2a828daa4528($context, $indent, $context->find('meta_quote_url'));
                $buffer .= '</span>';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function sectionA9b69db1143b1d4ef03e9db53ddd065f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '<span class="title">{{meta_quote_description}}</span>';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= '<span class="title">';
                $value = $this->resolveValue($context->find('meta_quote_description'), $context, $indent);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</span>';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section100dc055888aef855069e1248982ebfd(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
{{!

    QUOTE

}}
  <div class="meteor-post nodetails {{{post_class}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    <div class="meteor-quote">
      <blockquote>
        {{{content}}}
      </blockquote>
      <p class="author clearfix">
        {{#meta_quote_author}}<span class="author">{{#meta_quote_url}}<a href="{{{meta_quote_url}}}">{{/meta_quote_url}}{{meta_quote_author}}{{#meta_quote_url}}</a>{{/meta_quote_url}}</span>{{/meta_quote_author}}
        {{#meta_quote_description}}<span class="title">{{meta_quote_description}}</span>{{/meta_quote_description}}
      </p>
    </div><!--.meteor-quote -->
    {{> meteor_post_nodetails}}
  </div><!-- .meteor-post -->
';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '
';
                $buffer .= $indent . '  <div class="meteor-post nodetails ';
                $value = $this->resolveValue($context->find('post_class'), $context, $indent);
                $buffer .= $value;
                $buffer .= '"';
                // 'single_post' section
                $buffer .= $this->section3caeca9026effd27aa487afb902754a7($context, $indent, $context->find('single_post'));
                $buffer .= '>
';
                $buffer .= $indent . '    <div class="meteor-quote">
';
                $buffer .= $indent . '      <blockquote>
';
                $buffer .= $indent . '        ';
                $value = $this->resolveValue($context->find('content'), $context, $indent);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '      </blockquote>
';
                $buffer .= $indent . '      <p class="author clearfix">
';
                $buffer .= $indent . '        ';
                // 'meta_quote_author' section
                $buffer .= $this->sectionB2f10c3aca3074d55cac39934dde63d9($context, $indent, $context->find('meta_quote_author'));
                $buffer .= '
';
                $buffer .= $indent . '        ';
                // 'meta_quote_description' section
                $buffer .= $this->sectionA9b69db1143b1d4ef03e9db53ddd065f($context, $indent, $context->find('meta_quote_description'));
                $buffer .= '
';
                $buffer .= $indent . '      </p>
';
                $buffer .= $indent . '    </div><!--.meteor-quote -->
';
                if ($partial = $this->mustache->loadPartial('meteor_post_nodetails')) {
                    $buffer .= $partial->renderInternal($context, '    ');
                }
                $buffer .= $indent . '  </div><!-- .meteor-post -->
';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section1f6bb400ef9b5654b9bad52530265e15(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '{{{meta_link_content}}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $value = $this->resolveValue($context->find('meta_link_content'), $context, $indent);
                $buffer .= $value;
                $context->pop();
            }
        }
        return $buffer;
    }

    private function sectionEba3427c2deb7f5577e795cd653f7b0a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
{{!

    LINK

}}
  <div class="meteor-post {{{post_class}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    <div class="meteor-quote">
      <blockquote>
        {{#meta_link_content}}{{{meta_link_content}}}{{/meta_link_content}}
      </blockquote>
      <p class="author clearfix">
        <span class="title">{{{title}}}</span>
      </p>
    </div><!--.meteor-quote -->
    {{> meteor_post_details}}
  </div><!-- .meteor-post -->
';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '
';
                $buffer .= $indent . '  <div class="meteor-post ';
                $value = $this->resolveValue($context->find('post_class'), $context, $indent);
                $buffer .= $value;
                $buffer .= '"';
                // 'single_post' section
                $buffer .= $this->section3caeca9026effd27aa487afb902754a7($context, $indent, $context->find('single_post'));
                $buffer .= '>
';
                $buffer .= $indent . '    <div class="meteor-quote">
';
                $buffer .= $indent . '      <blockquote>
';
                $buffer .= $indent . '        ';
                // 'meta_link_content' section
                $buffer .= $this->section1f6bb400ef9b5654b9bad52530265e15($context, $indent, $context->find('meta_link_content'));
                $buffer .= '
';
                $buffer .= $indent . '      </blockquote>
';
                $buffer .= $indent . '      <p class="author clearfix">
';
                $buffer .= $indent . '        <span class="title">';
                $value = $this->resolveValue($context->find('title'), $context, $indent);
                $buffer .= $value;
                $buffer .= '</span>
';
                $buffer .= $indent . '      </p>
';
                $buffer .= $indent . '    </div><!--.meteor-quote -->
';
                if ($partial = $this->mustache->loadPartial('meteor_post_details')) {
                    $buffer .= $partial->renderInternal($context, '    ');
                }
                $buffer .= $indent . '  </div><!-- .meteor-post -->
';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section5b8a18960a995cb5a74eed7d885d16c2(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
{{!

    ASIDE

}}
  <div class="meteor-post nodetails {{{post_class}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    <div class="meteor-quote">
      <div class="post-content">
        {{{content}}}
      </div>
      <p class="author clearfix"><span class="title">{{{title}}}</span></p>
    </div><!--.meteor-quote -->
    {{> meteor_post_nodetails}}
  </div><!-- .meteor-post -->
';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '
';
                $buffer .= $indent . '  <div class="meteor-post nodetails ';
                $value = $this->resolveValue($context->find('post_class'), $context, $indent);
                $buffer .= $value;
                $buffer .= '"';
                // 'single_post' section
                $buffer .= $this->section3caeca9026effd27aa487afb902754a7($context, $indent, $context->find('single_post'));
                $buffer .= '>
';
                $buffer .= $indent . '    <div class="meteor-quote">
';
                $buffer .= $indent . '      <div class="post-content">
';
                $buffer .= $indent . '        ';
                $value = $this->resolveValue($context->find('content'), $context, $indent);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '      </div>
';
                $buffer .= $indent . '      <p class="author clearfix"><span class="title">';
                $value = $this->resolveValue($context->find('title'), $context, $indent);
                $buffer .= $value;
                $buffer .= '</span></p>
';
                $buffer .= $indent . '    </div><!--.meteor-quote -->
';
                if ($partial = $this->mustache->loadPartial('meteor_post_nodetails')) {
                    $buffer .= $partial->renderInternal($context, '    ');
                }
                $buffer .= $indent . '  </div><!-- .meteor-post -->
';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function sectionD247c97f14a670b8d4e48b4f88a50157(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
{{!

    STATUS

}}
  <div class="meteor-post nodetails {{{post_class}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    <aside class="status-container clearfix">
      <a class="avatar rounded" href="{{{user_url}}}">{{{avatar}}}</a>
      <div class="post-content">
        {{{content}}}
      </div><!-- .post-content -->
    </aside><!-- .chat-container -->
    {{> meteor_post_nodetails}}
  </div><!-- .meteor-post -->
';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '
';
                $buffer .= $indent . '  <div class="meteor-post nodetails ';
                $value = $this->resolveValue($context->find('post_class'), $context, $indent);
                $buffer .= $value;
                $buffer .= '"';
                // 'single_post' section
                $buffer .= $this->section3caeca9026effd27aa487afb902754a7($context, $indent, $context->find('single_post'));
                $buffer .= '>
';
                $buffer .= $indent . '    <aside class="status-container clearfix">
';
                $buffer .= $indent . '      <a class="avatar rounded" href="';
                $value = $this->resolveValue($context->find('user_url'), $context, $indent);
                $buffer .= $value;
                $buffer .= '">';
                $value = $this->resolveValue($context->find('avatar'), $context, $indent);
                $buffer .= $value;
                $buffer .= '</a>
';
                $buffer .= $indent . '      <div class="post-content">
';
                $buffer .= $indent . '        ';
                $value = $this->resolveValue($context->find('content'), $context, $indent);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '      </div><!-- .post-content -->
';
                $buffer .= $indent . '    </aside><!-- .chat-container -->
';
                if ($partial = $this->mustache->loadPartial('meteor_post_nodetails')) {
                    $buffer .= $partial->renderInternal($context, '    ');
                }
                $buffer .= $indent . '  </div><!-- .meteor-post -->
';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function sectionA8bd15d543557bbe7c0f1bb598a9d013(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
{{!

    CHAT

}}
  <div class="meteor-post nodetails {{{post_class}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    <div class="meteor-quote">
      <div class="post-content">
        {{{content}}}
      </div>
      <p class="author clearfix"><span class="title">{{{title}}}</span></p>
    </div><!--.meteor-quote -->
    {{> meteor_post_nodetails}}
  </div><!-- .meteor-post -->
';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '
';
                $buffer .= $indent . '  <div class="meteor-post nodetails ';
                $value = $this->resolveValue($context->find('post_class'), $context, $indent);
                $buffer .= $value;
                $buffer .= '"';
                // 'single_post' section
                $buffer .= $this->section3caeca9026effd27aa487afb902754a7($context, $indent, $context->find('single_post'));
                $buffer .= '>
';
                $buffer .= $indent . '    <div class="meteor-quote">
';
                $buffer .= $indent . '      <div class="post-content">
';
                $buffer .= $indent . '        ';
                $value = $this->resolveValue($context->find('content'), $context, $indent);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '      </div>
';
                $buffer .= $indent . '      <p class="author clearfix"><span class="title">';
                $value = $this->resolveValue($context->find('title'), $context, $indent);
                $buffer .= $value;
                $buffer .= '</span></p>
';
                $buffer .= $indent . '    </div><!--.meteor-quote -->
';
                if ($partial = $this->mustache->loadPartial('meteor_post_nodetails')) {
                    $buffer .= $partial->renderInternal($context, '    ');
                }
                $buffer .= $indent . '  </div><!-- .meteor-post -->
';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section297d3c5fc7f6265ef10734f64bf0ecec(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
{{!

    GALLERY

}}
  <div class="meteor-posts meteor-gallery {{{post_class}}}" data-columns="{{{gallery_columns}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    {{> meteor_gallery}}
    <div class="meteor-post">{{! not all posts have the .meteor-post class (also not .single-post) }}
      {{> meteor_post_details}}
    </div><!-- .meteor-post -->
  </div><!-- .meteor-gallery -->
';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '
';
                $buffer .= $indent . '  <div class="meteor-posts meteor-gallery ';
                $value = $this->resolveValue($context->find('post_class'), $context, $indent);
                $buffer .= $value;
                $buffer .= '" data-columns="';
                $value = $this->resolveValue($context->find('gallery_columns'), $context, $indent);
                $buffer .= $value;
                $buffer .= '"';
                // 'single_post' section
                $buffer .= $this->section3caeca9026effd27aa487afb902754a7($context, $indent, $context->find('single_post'));
                $buffer .= '>
';
                if ($partial = $this->mustache->loadPartial('meteor_gallery')) {
                    $buffer .= $partial->renderInternal($context, '    ');
                }
                $buffer .= $indent . '    <div class="meteor-post">';
                $buffer .= '
';
                if ($partial = $this->mustache->loadPartial('meteor_post_details')) {
                    $buffer .= $partial->renderInternal($context, '      ');
                }
                $buffer .= $indent . '    </div><!-- .meteor-post -->
';
                $buffer .= $indent . '  </div><!-- .meteor-gallery -->
';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section0ab2e6bdbf1491e91e4a4ac4102fc190(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '<div class="post-img frame" data-permalink="{{{permalink}}}">
      <a href="{{#opt_lightbox}}{{{image_src}}}{{/opt_lightbox}}{{^opt_lightbox}}{{{permalink}}}{{/opt_lightbox}}"><img alt="{{{title}}}" src="{{{image}}}" /></a>
      <i class="post-icon icon-camera-retro{{^single_post}} visible-phone{{/single_post}}"></i>
    </div><!-- .post-img -->';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= '<div class="post-img frame" data-permalink="';
                $value = $this->resolveValue($context->find('permalink'), $context, $indent);
                $buffer .= $value;
                $buffer .= '">
';
                $buffer .= $indent . '      <a href="';
                // 'opt_lightbox' section
                $buffer .= $this->sectionCb52925824ac380ceb2e030f2fd1edda($context, $indent, $context->find('opt_lightbox'));
                // 'opt_lightbox' inverted section
                $value = $context->find('opt_lightbox');
                if (empty($value)) {
                    
                    $value = $this->resolveValue($context->find('permalink'), $context, $indent);
                    $buffer .= $value;
                }
                $buffer .= '"><img alt="';
                $value = $this->resolveValue($context->find('title'), $context, $indent);
                $buffer .= $value;
                $buffer .= '" src="';
                $value = $this->resolveValue($context->find('image'), $context, $indent);
                $buffer .= $value;
                $buffer .= '" /></a>
';
                $buffer .= $indent . '      <i class="post-icon icon-camera-retro';
                // 'single_post' inverted section
                $value = $context->find('single_post');
                if (empty($value)) {
                    
                    $buffer .= ' visible-phone';
                }
                $buffer .= '"></i>
';
                $buffer .= $indent . '    </div><!-- .post-img -->';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section2bd58ab653b6dd129664598a77e1dca6(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
{{!

    IMAGE

}}
  <div class="meteor-post item {{{post_class}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    {{#image}}<div class="post-img frame" data-permalink="{{{permalink}}}">
      <a href="{{#opt_lightbox}}{{{image_src}}}{{/opt_lightbox}}{{^opt_lightbox}}{{{permalink}}}{{/opt_lightbox}}"><img alt="{{{title}}}" src="{{{image}}}" /></a>
      <i class="post-icon icon-camera-retro{{^single_post}} visible-phone{{/single_post}}"></i>
    </div><!-- .post-img -->{{/image}}
    {{> meteor_post_details}}
  </div><!-- .meteor-post -->
';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '
';
                $buffer .= $indent . '  <div class="meteor-post item ';
                $value = $this->resolveValue($context->find('post_class'), $context, $indent);
                $buffer .= $value;
                $buffer .= '"';
                // 'single_post' section
                $buffer .= $this->section3caeca9026effd27aa487afb902754a7($context, $indent, $context->find('single_post'));
                $buffer .= '>
';
                $buffer .= $indent . '    ';
                // 'image' section
                $buffer .= $this->section0ab2e6bdbf1491e91e4a4ac4102fc190($context, $indent, $context->find('image'));
                $buffer .= '
';
                if ($partial = $this->mustache->loadPartial('meteor_post_details')) {
                    $buffer .= $partial->renderInternal($context, '    ');
                }
                $buffer .= $indent . '  </div><!-- .meteor-post -->
';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section97667f2275359b47cbc3561240ae7d69(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '<div class="post-img frame" data-permalink="{{{permalink}}}">
      <a href="{{#opt_lightbox}}{{{image_src}}}{{/opt_lightbox}}{{^opt_lightbox}}{{{permalink}}}{{/opt_lightbox}}"><img alt="{{{title}}}" src="{{{image}}}" /></a>
      <i class="post-icon icon-music{{^single_post}} visible-phone{{/single_post}}"></i>
    </div><!-- .post-img -->';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= '<div class="post-img frame" data-permalink="';
                $value = $this->resolveValue($context->find('permalink'), $context, $indent);
                $buffer .= $value;
                $buffer .= '">
';
                $buffer .= $indent . '      <a href="';
                // 'opt_lightbox' section
                $buffer .= $this->sectionCb52925824ac380ceb2e030f2fd1edda($context, $indent, $context->find('opt_lightbox'));
                // 'opt_lightbox' inverted section
                $value = $context->find('opt_lightbox');
                if (empty($value)) {
                    
                    $value = $this->resolveValue($context->find('permalink'), $context, $indent);
                    $buffer .= $value;
                }
                $buffer .= '"><img alt="';
                $value = $this->resolveValue($context->find('title'), $context, $indent);
                $buffer .= $value;
                $buffer .= '" src="';
                $value = $this->resolveValue($context->find('image'), $context, $indent);
                $buffer .= $value;
                $buffer .= '" /></a>
';
                $buffer .= $indent . '      <i class="post-icon icon-music';
                // 'single_post' inverted section
                $value = $context->find('single_post');
                if (empty($value)) {
                    
                    $buffer .= ' visible-phone';
                }
                $buffer .= '"></i>
';
                $buffer .= $indent . '    </div><!-- .post-img -->';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function sectionF0236593f8d7cdfc6dd3d4ab6c16d47f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '<audio src="{{{audio_file}}}" preload="auto" controls="controls" ></audio>';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= '<audio src="';
                $value = $this->resolveValue($context->find('audio_file'), $context, $indent);
                $buffer .= $value;
                $buffer .= '" preload="auto" controls="controls" ></audio>';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section15ebeb5a1ffa7bd2cf3e2dff0b0e85df(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
{{!

    AUDIO

}}
  <div class="meteor-post item {{{post_class}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    {{#image}}<div class="post-img frame" data-permalink="{{{permalink}}}">
      <a href="{{#opt_lightbox}}{{{image_src}}}{{/opt_lightbox}}{{^opt_lightbox}}{{{permalink}}}{{/opt_lightbox}}"><img alt="{{{title}}}" src="{{{image}}}" /></a>
      <i class="post-icon icon-music{{^single_post}} visible-phone{{/single_post}}"></i>
    </div><!-- .post-img -->{{/image}}
    <div class="post-audio standout">
      {{#audio_file}}<audio src="{{{audio_file}}}" preload="auto" controls="controls" ></audio>{{/audio_file}}
    </div><!-- .post-audio -->
    {{> meteor_post_details}}
  </div><!-- .meteor-post -->
';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '
';
                $buffer .= $indent . '  <div class="meteor-post item ';
                $value = $this->resolveValue($context->find('post_class'), $context, $indent);
                $buffer .= $value;
                $buffer .= '"';
                // 'single_post' section
                $buffer .= $this->section3caeca9026effd27aa487afb902754a7($context, $indent, $context->find('single_post'));
                $buffer .= '>
';
                $buffer .= $indent . '    ';
                // 'image' section
                $buffer .= $this->section97667f2275359b47cbc3561240ae7d69($context, $indent, $context->find('image'));
                $buffer .= '
';
                $buffer .= $indent . '    <div class="post-audio standout">
';
                $buffer .= $indent . '      ';
                // 'audio_file' section
                $buffer .= $this->sectionF0236593f8d7cdfc6dd3d4ab6c16d47f($context, $indent, $context->find('audio_file'));
                $buffer .= '
';
                $buffer .= $indent . '    </div><!-- .post-audio -->
';
                if ($partial = $this->mustache->loadPartial('meteor_post_details')) {
                    $buffer .= $partial->renderInternal($context, '    ');
                }
                $buffer .= $indent . '  </div><!-- .meteor-post -->
';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section42650164e07fa90a5bde56338e3553f2(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
{{!

    VIDEO

}}
  <div class="meteor-post {{{post_class}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    {{> meteor_video_player}}
    {{> meteor_post_details}}
  </div><!-- .meteor-post -->
  ';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '
';
                $buffer .= $indent . '  <div class="meteor-post ';
                $value = $this->resolveValue($context->find('post_class'), $context, $indent);
                $buffer .= $value;
                $buffer .= '"';
                // 'single_post' section
                $buffer .= $this->section3caeca9026effd27aa487afb902754a7($context, $indent, $context->find('single_post'));
                $buffer .= '>
';
                if ($partial = $this->mustache->loadPartial('meteor_video_player')) {
                    $buffer .= $partial->renderInternal($context, '    ');
                }
                if ($partial = $this->mustache->loadPartial('meteor_post_details')) {
                    $buffer .= $partial->renderInternal($context, '    ');
                }
                $buffer .= $indent . '  </div><!-- .meteor-post -->
';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section210d997e57446a43efa1a893b17b235d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '

{{#is_standard}}
  {{#post_style_normal}}
{{!

    STANDARD

}}
  <div class="meteor-post item {{{post_class}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    {{^is_attachment}}{{#image}}<div class="post-img frame" data-permalink="{{{permalink}}}">
      <a href="{{#opt_lightbox}}{{{image_src}}}{{/opt_lightbox}}{{^opt_lightbox}}{{{permalink}}}{{/opt_lightbox}}"><img alt="{{{title}}}" src="{{{image}}}" /></a>
      <i class="post-icon {{#icon}}{{{icon}}}{{/icon}}{{^icon}}icon-pencil{{/icon}}{{^single_post}} visible-phone{{/single_post}}"></i>
    </div><!-- .post-img -->{{/image}}{{/is_attachment}}
    {{> meteor_post_details}}
  </div><!-- .meteor-post -->
  {{/post_style_normal}}{{#post_style_aside}}
  {{! 
    
    STANDARD ASIDE
    
  }}
  <div class="meteor-post thumb-aside item {{{post_class}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    <div class="post-img frame" data-permalink="{{{permalink}}}">
      <a href="{{#opt_lightbox}}{{{image_src}}}{{/opt_lightbox}}{{^opt_lightbox}}{{{permalink}}}{{/opt_lightbox}}"><img style="width: {{{width}}}px;" {{#height}}height="{{{height}}}" {{/height}}src="{{{image}}}" /></a>
      <i class="post-icon icon-pencil"></i>
    </div><!-- .post-img -->
    {{> meteor_post_details}}
  </div><!-- .meteor-post.thumb-aside -->
  {{/post_style_aside}}
{{/is_standard}}{{#is_quote}}
{{!

    QUOTE

}}
  <div class="meteor-post nodetails {{{post_class}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    <div class="meteor-quote">
      <blockquote>
        {{{content}}}
      </blockquote>
      <p class="author clearfix">
        {{#meta_quote_author}}<span class="author">{{#meta_quote_url}}<a href="{{{meta_quote_url}}}">{{/meta_quote_url}}{{meta_quote_author}}{{#meta_quote_url}}</a>{{/meta_quote_url}}</span>{{/meta_quote_author}}
        {{#meta_quote_description}}<span class="title">{{meta_quote_description}}</span>{{/meta_quote_description}}
      </p>
    </div><!--.meteor-quote -->
    {{> meteor_post_nodetails}}
  </div><!-- .meteor-post -->
{{/is_quote}}{{#is_link}}
{{!

    LINK

}}
  <div class="meteor-post {{{post_class}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    <div class="meteor-quote">
      <blockquote>
        {{#meta_link_content}}{{{meta_link_content}}}{{/meta_link_content}}
      </blockquote>
      <p class="author clearfix">
        <span class="title">{{{title}}}</span>
      </p>
    </div><!--.meteor-quote -->
    {{> meteor_post_details}}
  </div><!-- .meteor-post -->
{{/is_link}}{{#is_aside}}
{{!

    ASIDE

}}
  <div class="meteor-post nodetails {{{post_class}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    <div class="meteor-quote">
      <div class="post-content">
        {{{content}}}
      </div>
      <p class="author clearfix"><span class="title">{{{title}}}</span></p>
    </div><!--.meteor-quote -->
    {{> meteor_post_nodetails}}
  </div><!-- .meteor-post -->
{{/is_aside}}{{#is_status}}
{{!

    STATUS

}}
  <div class="meteor-post nodetails {{{post_class}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    <aside class="status-container clearfix">
      <a class="avatar rounded" href="{{{user_url}}}">{{{avatar}}}</a>
      <div class="post-content">
        {{{content}}}
      </div><!-- .post-content -->
    </aside><!-- .chat-container -->
    {{> meteor_post_nodetails}}
  </div><!-- .meteor-post -->
{{/is_status}}{{#is_chat}}
{{!

    CHAT

}}
  <div class="meteor-post nodetails {{{post_class}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    <div class="meteor-quote">
      <div class="post-content">
        {{{content}}}
      </div>
      <p class="author clearfix"><span class="title">{{{title}}}</span></p>
    </div><!--.meteor-quote -->
    {{> meteor_post_nodetails}}
  </div><!-- .meteor-post -->
{{/is_chat}}{{#is_gallery}}
{{!

    GALLERY

}}
  <div class="meteor-posts meteor-gallery {{{post_class}}}" data-columns="{{{gallery_columns}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    {{> meteor_gallery}}
    <div class="meteor-post">{{! not all posts have the .meteor-post class (also not .single-post) }}
      {{> meteor_post_details}}
    </div><!-- .meteor-post -->
  </div><!-- .meteor-gallery -->
{{/is_gallery}}{{#is_image}}
{{!

    IMAGE

}}
  <div class="meteor-post item {{{post_class}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    {{#image}}<div class="post-img frame" data-permalink="{{{permalink}}}">
      <a href="{{#opt_lightbox}}{{{image_src}}}{{/opt_lightbox}}{{^opt_lightbox}}{{{permalink}}}{{/opt_lightbox}}"><img alt="{{{title}}}" src="{{{image}}}" /></a>
      <i class="post-icon icon-camera-retro{{^single_post}} visible-phone{{/single_post}}"></i>
    </div><!-- .post-img -->{{/image}}
    {{> meteor_post_details}}
  </div><!-- .meteor-post -->
{{/is_image}}{{#is_audio}}
{{!

    AUDIO

}}
  <div class="meteor-post item {{{post_class}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    {{#image}}<div class="post-img frame" data-permalink="{{{permalink}}}">
      <a href="{{#opt_lightbox}}{{{image_src}}}{{/opt_lightbox}}{{^opt_lightbox}}{{{permalink}}}{{/opt_lightbox}}"><img alt="{{{title}}}" src="{{{image}}}" /></a>
      <i class="post-icon icon-music{{^single_post}} visible-phone{{/single_post}}"></i>
    </div><!-- .post-img -->{{/image}}
    <div class="post-audio standout">
      {{#audio_file}}<audio src="{{{audio_file}}}" preload="auto" controls="controls" ></audio>{{/audio_file}}
    </div><!-- .post-audio -->
    {{> meteor_post_details}}
  </div><!-- .meteor-post -->
{{/is_audio}}{{#is_video}}
{{!

    VIDEO

}}
  <div class="meteor-post {{{post_class}}}"{{#single_post}}{{> meteor_single_post_attrs}}{{/single_post}}>
    {{> meteor_video_player}}
    {{> meteor_post_details}}
  </div><!-- .meteor-post -->
  {{/is_video}}

';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '
';
                // 'is_standard' section
                $buffer .= $this->sectionE510b0e5d996ffbd9069594dfd70c5c9($context, $indent, $context->find('is_standard'));
                // 'is_quote' section
                $buffer .= $this->section100dc055888aef855069e1248982ebfd($context, $indent, $context->find('is_quote'));
                // 'is_link' section
                $buffer .= $this->sectionEba3427c2deb7f5577e795cd653f7b0a($context, $indent, $context->find('is_link'));
                // 'is_aside' section
                $buffer .= $this->section5b8a18960a995cb5a74eed7d885d16c2($context, $indent, $context->find('is_aside'));
                // 'is_status' section
                $buffer .= $this->sectionD247c97f14a670b8d4e48b4f88a50157($context, $indent, $context->find('is_status'));
                // 'is_chat' section
                $buffer .= $this->sectionA8bd15d543557bbe7c0f1bb598a9d013($context, $indent, $context->find('is_chat'));
                // 'is_gallery' section
                $buffer .= $this->section297d3c5fc7f6265ef10734f64bf0ecec($context, $indent, $context->find('is_gallery'));
                // 'is_image' section
                $buffer .= $this->section2bd58ab653b6dd129664598a77e1dca6($context, $indent, $context->find('is_image'));
                // 'is_audio' section
                $buffer .= $this->section15ebeb5a1ffa7bd2cf3e2dff0b0e85df($context, $indent, $context->find('is_audio'));
                // 'is_video' section
                $buffer .= $this->section42650164e07fa90a5bde56338e3553f2($context, $indent, $context->find('is_video'));
                $buffer .= $indent . '
';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function sectionAd5bf68dc2c042c0cdb96998ebd9128d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '{{{pagination}}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $value = $this->resolveValue($context->find('pagination'), $context, $indent);
                $buffer .= $indent . $value;
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section99868a19e50e849d2bab92b6e4256987(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '{{#pagination}}{{{pagination}}}{{/pagination}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                // 'pagination' section
                $buffer .= $this->sectionAd5bf68dc2c042c0cdb96998ebd9128d($context, $indent, $context->find('pagination'));
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section5786d60ed7364db08ee08abb89ef5ee5(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '{{{no_posts_content}}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $value = $this->resolveValue($context->find('no_posts_content'), $context, $indent);
                $buffer .= $indent . $value;
                $context->pop();
            }
        }
        return $buffer;
    }
}
