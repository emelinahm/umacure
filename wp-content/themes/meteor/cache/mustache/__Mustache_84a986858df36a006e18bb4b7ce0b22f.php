<?php
class __Mustache_84a986858df36a006e18bb4b7ce0b22f extends Mustache_Template
{
    private $lambdaHelper;
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="metabox-entry';
        // 'upload' section
        $buffer .= $this->section0132af238a8fc0590cdb5f8bd882c102($context, $indent, $context->find('upload'));
        $buffer .= '" data-upload-label="Upload ';
        $value = $this->resolveValue($context->find('upload'), $context, $indent);
        $buffer .= $value;
        $buffer .= '" data-remove-label="Remove ';
        $value = $this->resolveValue($context->find('upload'), $context, $indent);
        $buffer .= $value;
        $buffer .= '">
';
        $buffer .= $indent . '<label for="';
        $value = $this->resolveValue($context->find('key'), $context, $indent);
        $buffer .= $value;
        $buffer .= '">';
        $value = $this->resolveValue($context->find('title'), $context, $indent);
        $buffer .= $value;
        $buffer .= '</label>
';
        // 'upload' section
        $buffer .= $this->section1e9942afefc83ab69e5e3394c7986c52($context, $indent, $context->find('upload'));
        $buffer .= '<input';
        // 'upload' section
        $buffer .= $this->sectionD77d9d1ff73b19716deaafcd50768a29($context, $indent, $context->find('upload'));
        $buffer .= ' type="text" id="';
        $value = $this->resolveValue($context->find('key'), $context, $indent);
        $buffer .= $value;
        $buffer .= '" name="';
        $value = $this->resolveValue($context->find('key'), $context, $indent);
        $buffer .= $value;
        $buffer .= '" value="';
        $value = $this->resolveValue($context->find('val'), $context, $indent);
        $buffer .= $value;
        $buffer .= '" tabindex="1" autocomplete="off" />';
        // 'upload' section
        $buffer .= $this->section7c16fcd38d0c540cf9cfab46abdd2308($context, $indent, $context->find('upload'));
        $buffer .= '
';
        // 'desc' section
        $buffer .= $this->section9ca88ef42c6daec873b5fd9babd4b00c($context, $indent, $context->find('desc'));
        $buffer .= '
';
        $buffer .= $indent . '</div>';
        return $buffer;
    }

    private function section0132af238a8fc0590cdb5f8bd882c102(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = ' option';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= ' option';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section1e9942afefc83ab69e5e3394c7986c52(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '<p>';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '<p>';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function sectionD77d9d1ff73b19716deaafcd50768a29(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = ' class="upload-input" style="width: 98%;"';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= ' class="upload-input" style="width: 98%;"';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section7c16fcd38d0c540cf9cfab46abdd2308(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '</p>';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= '</p>';
                $context->pop();
            }
        }
        return $buffer;
    }

    private function section9ca88ef42c6daec873b5fd9babd4b00c(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '{{#upload}}<p>{{/upload}}<span class="desc">{{{desc}}}</span>{{#upload}}</p>{{/upload}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                // 'upload' section
                $buffer .= $this->section1e9942afefc83ab69e5e3394c7986c52($context, $indent, $context->find('upload'));
                $buffer .= $indent . '<span class="desc">';
                $value = $this->resolveValue($context->find('desc'), $context, $indent);
                $buffer .= $value;
                $buffer .= '</span>';
                // 'upload' section
                $buffer .= $this->section7c16fcd38d0c540cf9cfab46abdd2308($context, $indent, $context->find('upload'));
                $context->pop();
            }
        }
        return $buffer;
    }
}
